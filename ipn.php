<?php

require_once '../../../wp-config.php';
require_once 'classes/database.class.php';
require_once 'classes/paymentprocess.class.php';

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_error.log');

$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
}

//check if mode is sandbox or live so we can assign appropriate url to deal with
if( eleProcessRecords::getMode() == 'sandbox' ){
    $paypal_url     = 'ssl://www.sandbox.paypal.com';
    $paypal_header  = 'www.sandbox.paypal.com';
} else {
    $paypal_url = 'ssl://www.paypal.com';
    $paypal_header  = 'www.paypal.com';
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Host: ".$paypal_header."\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ($paypal_url, 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name          = $_POST['item_name'];
$item_number        = $_POST['item_number'];
$payment_status     = $_POST['payment_status'];
$payment_amount     = $_POST['mc_gross'];
$payment_currency   = $_POST['mc_currency'];
$txn_id             = $_POST['txn_id'];
$receiver_email     = $_POST['receiver_email'];
$payer_email        = $_POST['payer_email'];
$custom_entry_id    = $_POST['custom'];
$invoice            = $_POST['invoice'].$custom_entry_id.time();

if (!$fp) {

    $file = 'ipn_error.log';
    $current = file_get_contents($file);
    $current .= "Error on fsocket\n";
    file_put_contents($file, $current);

} else {

    fputs ($fp, $header . $req);
    while (!feof($fp)) {
        $res = fgets ($fp, 1024);
        if (strcmp ($res, "VERIFIED") == 0) {

            //check if transaction ID already exist
            if( eleProcessRecords::txnExist($txn_id) ){

                $file = 'ipn_error.log';
                $current = file_get_contents($file);
                $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                $current .= "Transaction ID Already Exist!\n";
                file_put_contents($file, $current);
                exit;

            }

            //check if email is not altered
            if( $receiver_email != eleProcessRecords::getBusinessEmail() ){

                $file = 'ipn_error.log';
                $current = file_get_contents($file);
                $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                $current .= "Reciever email not match!\n";
                file_put_contents($file, $current);
                exit;

            }

            //check if currency is not altered
            if( $payment_currency != eleProcessRecords::getCurrency() ){

                $file = 'ipn_error.log';
                $current = file_get_contents($file);
                $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                $current .= "Currency not match!\n";
                file_put_contents($file, $current);
                exit;

            }

            //check if amount is not altered/try our first regular amount
            if( $payment_amount == eleProcessRecords::getAmount() ){

                //we arrived here, so all is well..lets start the dance
                $result = eleProcessRecords::updateStatus($custom_entry_id,$invoice,$txn_id);

                if( $result ){

                    $data['item_name']      = $item_name;
                    $data['mc_gross']       = $payment_amount;
                    $data['currency']       = $payment_currency;
                    $data['txn_id']         = $txn_id;
                    $data['receiver_email'] = $receiver_email;
                    $data['payer_email']    = $payer_email;
                    $data['invoice']        = $invoice;

                    eleProcessRecords::sendEmail($data);

                    $file = 'ipn_error.log';
                    $current = file_get_contents($file);
                    $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                    $current .= "Payment Successful - TXN ID: ".$txn_id."\n";
                    file_put_contents($file, $current);

                } else {

                    $file = 'ipn_error.log';
                    $current = file_get_contents($file);
                    $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                    $current .= "An Error Has Occured updating the record in database..\n";
                    file_put_contents($file, $current);

                }

                exit;

            }

            //first try no luck, try maybe the price is in packages
            if( $payment_amount == eleProcessRecords::getPackagePrice() ){

                //we arrived here, so all is well..lets start the dance
                $result = eleProcessRecords::updateStatus($custom_entry_id,$invoice,$txn_id);

                if( $result ){

                    $data['item_name']      = $item_name;
                    $data['mc_gross']       = $payment_amount;
                    $data['currency']       = $payment_currency;
                    $data['txn_id']         = $txn_id;
                    $data['receiver_email'] = $receiver_email;
                    $data['payer_email']    = $payer_email;
                    $data['invoice']        = $invoice;

                    eleProcessRecords::sendEmail($data);

                    $file = 'ipn_error.log';
                    $current = file_get_contents($file);
                    $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                    $current .= "Payment Successful - TXN ID: ".$txn_id."\n";
                    file_put_contents($file, $current);

                } else {

                    $file = 'ipn_error.log';
                    $current = file_get_contents($file);
                    $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
                    $current .= "An Error Has Occured updating the record in database..\n";
                    file_put_contents($file, $current);

                }

                exit;

            }

            //the script arrived here, so neither of the 2 prices did match (something's fishy)
            $file = 'ipn_error.log';
            $current = file_get_contents($file);
            $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
            $current .= "Amount not match!\n";
            file_put_contents($file, $current);

        }
        else if (strcmp ($res, "INVALID") == 0) {

            $file = 'ipn_error.log';
            $current = file_get_contents($file);
            $current .= "Time Log: ".date('d-F-Y g:i:s', time())."\n";
            $current .= "the ipn is not verified..\n";
            file_put_contents($file, $current);

        }
    }
    fclose ($fp);
}








