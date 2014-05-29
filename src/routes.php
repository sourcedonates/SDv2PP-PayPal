<?php

/*
  |--------------------------------------------------------------------------
  | SDv2PPPayPal Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |

 */

Route::get('/test', function()
{
    return "test";
});

Route::any('/ipn/paypal', 'PPIpnController@process_ipn');
Route::any('/payment/test/paypal','PPIpnController@test_payment');