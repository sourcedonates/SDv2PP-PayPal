<?php

/**
 *  SDv2PP_Paypal Settings
 *
 *  The settings for the paypal Payment Provider
 *
 *
 *  @package    SDv2PP_PayPal
 *  @author     Werner Maisl
 *  @copyright  (c) 2014 - Werner Maisl
 *  @version    0.1.0
 */
return array(
    /*
     |------------------------------
     | payment_paypal_sandbox
     |------------------------------
     |
     | If the paypal Sandbox Mode should be enabled
     | To enable => 'enabled'
     | To disable => 'disabled'
     |
     */
    'sandbox' => 'enabled',
    /*
     |------------------------------
     | payment_paypal_debug
     |------------------------------
     |
     | If the paypal integration should be debugged
     | To enable => 'enabled'
     | To disable => 'disabled'
     |
     */
    'debug' => 'enabled',
    /*
     |------------------------------
     | payment_paypal_email
     |------------------------------
     |
     | Paypal Email
     |
     */
    'receiver_email' => 'testuser@sbg.com',
);
