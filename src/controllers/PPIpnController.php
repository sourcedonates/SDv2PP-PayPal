<?php

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
