<?php

namespace Arrow768\Sdv2ppPaypal;
/**
 * SDv2 Payment Provider for Paypal
 * 
 * A PayPal Payment Provider for SDv2
 * Contains the required functions that are used by SDv2
 * 
 * This file is Part of SDv2PP-Paypal
 * SDv2PP-Paypal is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @package    SDv2PP-Paypal
 * @author     Werner Maisl
 * @copyright  (c) 2013-2014 - Werner Maisl
 * @license    GNU AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 */
class payment_paypal
{

    /**
     * Returns a array with information about this payment provider
     * 
     * @return mixed Returns a Array of the handled payment providers
     * 
     */
    function get_paymentprovider_info()
    {
        $provider = array(
            "id" => "paypal",
            "name" => "PayPal",
            "description" => "PayPal is a global e-commerce business allowing payments and money transfers to be made through the Internet.",
        );
        return $provider;
    }

    /**
     * Initiates the payment with the selected payment provider
     * 
     * @param int $amount The amount of the payment
     * @param string $transaction_id The transaction ID of the payment
     * @param string $currency The currency of the Transaction 3Chars
     * @param mixed $attrs Array with optional attrs
     */
    function initiate_payment($amount, $transaction_id, $currency, $attrs = array())
    {
        $item_name = "Order ID: " . $transaction_id;

        $p = new paypal_handler;

        if (\Config::get("sdv2pp_paypal.sandbox") == "enabled")
        {
            $p->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        }
        else
        {
            $p->paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

        $p->add_field("custom", $transaction_id); // Add the transaction ID here
        $p->add_field("no_shipping", '1'); //No shipping is needed for the payment
        $p->add_field("business", \Config::get('sdv2pp_paypal.receiver_email')); //the receiver of the payment
        $p->add_field("return", \URL::to('payment/success')); // redirect user to the success page when he made the paypal payment
        $p->add_field("cancel_return", \URL::to('payment/cancel')); // redirect user to the cancel page when he has aborted the payment
        $p->add_field("notify_url", \URL::to('ipn/paypal')); // the IPN Processer
        $p->add_field("item_name", $item_name); //the name of the item
        $p->add_field("amount", $amount); //the price of the item
        $p->add_field("currency_code", $currency); //the currency of the price
        $p->add_field("rm", '2'); // the return method; 2 = Post
        $p->add_field("cmd", '_donations'); //the payment is a donation
        $p->submit_paypal_post(); //submits the post
        if (\Config::get("sdv2pp_paypal.debug") == "enabled")
        {
            $p->dump_fields();
        }
    }

    /**
     * Processes the IPN
     * 
     * TODO: Perform Fraund checks
     * 
     * @return string Returns valid or invalid if the payment is valid/invalid
     */
    function process_ipn()
    {

        $logfile = "pp_ipn_log.txt";
        $fh = fopen($logfile, 'w');
        fwrite($fh, "New IPN \n");

        $listener = new ipnlistener; // Create a new ipn listener

        if (\Config::get("sdv2pp_paypal.sandbox") == "enabled")
            $listener->use_sandbox = true; //check if sandbox mode is enabled

        try
        {
            $verified = $listener->processIpn(); // try to verify the IPN
        }
        catch (Exception $e)
        {
            fwrite($fh, $e);
            exit(0);
        }

        fwrite($fh, $listener->getTextReport());

        if ($verified)
        {
            fwrite($fh, "\n verified \n");

            $error_num = 0;
            $error_text = "";

            //Get the details for the payment from the paypal post
            $transaction_id = \Illuminate\Support\Facades\Input::get("custom");
            $currency = \Illuminate\Support\Facades\Input::get("mc_currency");
            $price = \Illuminate\Support\Facades\Input::get("mc_gross");
            $business = \Illuminate\Support\Facades\Input::get("business");
            $payer_email = \Illuminate\Support\Facades\Input::get("payer_email");
            $pp_txnid = \Illuminate\Support\Facades\Input::get("txn_id");
            fwrite($fh, "\n $transaction_id: " . $transaction_id . "\n");

            //Get the transaction id from the database
            $transaction = \SDPaymentTransaction::find($transaction_id);
            fwrite($fh, "\n $transaction: " . var_dump($transaction) . "\n");

            //Check if the amount matches the stored amount
            if ($transaction->price != $price)
            {
                $error_num += 1;
                $error_text .= "Invalid Price \r\n";
            }

            //Check if the currency matches the stored currency
            if ($transaction->currency != $currency)
            {
                $error_num += 1;
                $error_text .= "Invalid Currency \r\n";
            }

            //Check if the currency matches the stored currency
            if ($transaction->status != "sent")
            {
                $error_num += 1;
                $error_text .= "Invalid Status: " . $transaction->status . " \r\n";
            }

            //Check if the Business Matches the paypal email
            if (\Config::get('sdv2pp_paypal.receiver_email') != $business)
            {
                $error_num += 1;
                $error_text .= "Invalid Receiver \r\n";
            }

            if ($error_num == 0)
            {
                //Continue Processing the IPN
                //Update the Database
                $transaction->status = "confirmed";
                $transaction->save();
                
                //Add the transaction to the queue
                \Queue::push('PaymentQueueWorker',array("transaction"=>$transaction->id));
                
                fwrite($fh, "\n valid \n\n\n");
                return "valid";
            }
            else
            {
                fwrite($fh, '\n invalid \n\n\n');
                return "invalid";
            }
        }
        else
        {
            fwrite($fh, '\n invalid \n\n\n');
            return "invalid";
        }
    }

}

?>