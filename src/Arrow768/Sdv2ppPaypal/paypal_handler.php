<?php

namespace Arrow768\Sdv2ppPaypal;

/**
 * PayPal Handler
 *
 * A class to create new paypal payments
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
class paypal_handler
{

    /**
     * Fields sent to paypal
     * 
     * $fields stores fields (key => value) that will be sent to paypal
     */
    var $fields = array();

    /**
     * Adds a field to the array that is sent to paypal
     * 
     * @param string $field Name of the field that is sent to paypal
     * @param string $value Value of the field that is sent to paypal
     */
    function add_field($field, $value)
    {
        //Adds a field that will be sent to paypal
        $this->fields["$field"] = $value;
    }

    /**
     * Creates the paypal payment
     * 
     * Posts the fields in $fields to paypal
     */
    function submit_paypal_post()
    {
        echo "<html>\n<head><title>Processing Payment...</title></head>\n";
        echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
        echo "<center><h2>Please wait, your order is being processed and you will be redirected to the paypal website.</h2></center>\n";
        echo "<form method=\"post\" name=\"paypal_form\" action=\"" . $this->paypal_url . "\">\n";
        foreach ($this->fields as $name => $value)
        {
            echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
        echo "<center><br/><br/>If you are not automatically redirected to paypal within 5 seconds...<br/><br/>\n";
        echo "<input type=\"submit\" value=\"Click Here\"></center>\n";
        echo "</form>\n</body></html>\n";
    }

    /**
     * Prints the fields in $fields at the redirect page
     */
    function dump_fields()
    {
        echo "<h3>paypal_class->dump_fields() Output:</h3>";
        echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";

        ksort($this->fields);
        foreach ($this->fields as $key => $value)
        {
            echo "<tr><td>$key</td><td>" . urldecode($value) . "&nbsp;</td></tr>";
        }

        echo "</table><br>";
    }

}
