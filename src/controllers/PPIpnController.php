<?php
/**
 * Controller for testing and handling the IPN
 *
 * This controller is used for testing the library and handling the ipn sent from paypal
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
class PPIpnController extends BaseController
{
    public function test_payment()
    {
        $payment_paypal = new Arrow768\Sdv2ppPaypal\payment_paypal();
        $payment_paypal->initiate_payment("12.0", "test-12345", "EUR"); 
    }
    
    public function process_ipn()
    {
        $payment_paypal = new Arrow768\Sdv2ppPaypal\payment_paypal();
        $payment_paypal->process_ipn();
    }
}
