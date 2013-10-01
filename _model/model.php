<?php

if ( ! defined('ABSPATH')) exit('No direct script access allowed');

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/error.log');


/**
 * EleModel Class
 * @usage: use for database transactions
 * @since: 1.0
 * @author: Darryl Fernandez
 */

class EleModel {


    /**
     * @usage get all entries in database
     * @param $input (object)
     * @return array
     */
    public static function getEntries($input){

        $limit  = $input->limit;
        $offset = $input->offset;

        $dbcon = Connection::getInstance();

        $query = "SELECT * FROM ".$dbcon->ele_entry." ORDER BY ele_entry_id DESC LIMIT ".$offset.",".$limit."";
        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();

        try {

            $dbcon->execute();
            $res1 = $dbcon->fetchAssoc();
            $dbcon->resetData();


        } catch (Exception $e) {

            echo 'error';
            exit;

        }

        //print_r($res1);

        $data = array();
        $x = 0;

        foreach($res1 as $res){

            $query = "SELECT * FROM ".$dbcon->ele_registrants." WHERE ele_entry_ele_entry_id=:ele_entry_ele_entry_id";

            $dbcon->initiateConnection();
            $dbcon->query($query);
            $dbcon->prepare();
            $dbcon->bindValue(':ele_entry_ele_entry_id',$res['ele_entry_id']);

            try {

                $dbcon->execute();
                $res2 = $dbcon->fetchAssoc();
                $dbcon->resetData();

            } catch (Exception $e) {

                echo 'error';
                exit;

            }

            $data[$x] = array_merge($res,$res2);

            $x++;

        }

        return $data;

    }

    /**
     * @usage get total number of entries in database
     * @return array
     */
    public static function getTotalEntries(){

        $dbcon = Connection::getInstance();

        $query = "SELECT * FROM ".$dbcon->ele_entry."";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();

        try {

            $dbcon->execute();
            $total_entries = $dbcon->rowCount();
            $dbcon->resetData();

            return array('success'=>true,'total'=>$total_entries);

        } catch (Exception $e) {

            return array(array('success'=>false));

        }

    }

    /**
     * @usage use to update the registrants into paid status
     * @param $input (object)
     * @return array
     */
    public static function setPaid($input){

        $entry_id = $input->entry_id;

        $dbcon = Connection::getInstance();

        $query = "UPDATE ".$dbcon->ele_entry." SET ele_entry_status=1 WHERE ele_entry_id=:ele_entry_id";
        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->bindValue(':ele_entry_id',$entry_id);

        try {

            $dbcon->execute();
            $dbcon->resetData();

            return array('success'=>true);

        } catch (Exception $e) {

            return array('success'=>false);

        }

    }

    /**
     * @usage adds entry
     * @param $input
     * @return array
     */
    public static function addEntry($input){

        $entry = array();
        foreach( $input->entry_data as $k => $v ){
            $entry[$v->name] = strip_tags( stripslashes( $v->value ) );
        }

        //filter
        if( !filter_var( $entry['receiptEmail'], FILTER_VALIDATE_EMAIL ) ){
            echo json_encode(array('success'=>false,'error'=>'Invalid Email Format!'));
            exit;
        }
        if( !filter_var( $entry['email_1'], FILTER_VALIDATE_EMAIL ) ){
            echo json_encode(array('success'=>false,'error'=>'Invalid Email Format!'));
            exit;
        }
        if( !filter_var( $entry['email_2'], FILTER_VALIDATE_EMAIL ) ){
            echo json_encode(array('success'=>false,'error'=>'Invalid Email Format!'));
            exit;
        }

        $dbcon = Connection::getInstance();

        //team data
        $ele_entry_team_name        = $entry['teamName'];
        $ele_entry_category_name    = $entry['teamCategory'];
        $ele_entry_email            = $entry['receiptEmail'];
        $ele_entry_payment_method   = $entry['payment_method'];
        $ele_entry_invoice          = $entry['invoice'].rand(0,10000);
        $ele_entry_txn_id           = 'TEMP'.time().rand(0,10000);
        $ele_entry_status           = 0;
        $ele_entry_registration_date= date('d-F-Y g:i:s', time());

        $query = "INSERT INTO ".$dbcon->ele_entry." (ele_entry_team_name, ele_entry_category_name, ele_entry_email, ele_entry_invoice, ele_entry_txn_id, ele_entry_payment_method, ele_entry_status, ele_entry_registration_date) VALUES (:ele_entry_team_name, :ele_entry_category_name, :ele_entry_email, :ele_entry_invoice, :ele_entry_txn_id, :ele_entry_payment_method, :ele_entry_status, :ele_entry_registration_date)";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->bindValue(':ele_entry_team_name', $ele_entry_team_name);
        $dbcon->bindValue(':ele_entry_category_name', $ele_entry_category_name);
        $dbcon->bindValue(':ele_entry_email', $ele_entry_email);
        $dbcon->bindValue(':ele_entry_invoice', $ele_entry_invoice);
        $dbcon->bindValue(':ele_entry_txn_id', $ele_entry_txn_id);
        $dbcon->bindValue(':ele_entry_payment_method', $ele_entry_payment_method);
        $dbcon->bindValue(':ele_entry_status', $ele_entry_status);
        $dbcon->bindValue(':ele_entry_registration_date', $ele_entry_registration_date);

        try {

            $dbcon->execute();
            $dbcon->resetData();
            $ID = $dbcon->lastInsertedID();

        } catch (Exception $e) {

            echo json_encode(array('error'=>$e));

        }

        if(!empty($ID)){

            $registrants = array(
                'registrant_1' => array(

                    'ele_registrant_first_name'      => $entry['firstName_1'],
                    'ele_registrants_middle_name'    => $entry['middleName_1'],
                    'ele_registrants_last_name'      => $entry['lastName_1'],
                    'ele_registrants_date_of_birth'  => $entry['dateOfBirth_year_1'].'/'.$entry['dateOfBirth_month_1'].'/'.$entry['dateOfBirth_day_1'],
                    'ele_registrants_gender'         => $entry['gender_1'],
                    'ele_registrants_mailing_address'=> $entry['physicalMail_1'],
                    'ele_registrants_phone'          => $entry['phone_1'],
                    'ele_registrants_email_address'  => $entry['email_1'],
                    'ele_registrants_citizenship'    => $entry['citizenship_1'],
                    'ele_registrants_athletic_level' => $entry['level_1'],
                    'ele_entry_ele_entry_id'         => $ID

                ),

                'registrant_2' => array(

                    'ele_registrant_first_name'      => $entry['firstName_2'],
                    'ele_registrants_middle_name'    => $entry['middleName_2'],
                    'ele_registrants_last_name'      => $entry['lastName_2'],
                    'ele_registrants_date_of_birth'  => $entry['dateOfBirth_year_2'].'/'.$entry['dateOfBirth_month_2'].'/'.$entry['dateOfBirth_day_2'],
                    'ele_registrants_gender'         => $entry['gender_2'],
                    'ele_registrants_mailing_address'=> $entry['physicalMail_2'],
                    'ele_registrants_phone'          => $entry['phone_2'],
                    'ele_registrants_email_address'  => $entry['email_2'],
                    'ele_registrants_citizenship'    => $entry['citizenship_2'],
                    'ele_registrants_athletic_level' => $entry['level_2'],
                    'ele_entry_ele_entry_id'         => $ID

                )
            );

            $error = 0;

            $query2 = "INSERT INTO ".$dbcon->ele_registrants." (ele_registrant_first_name, ele_registrants_middle_name, ele_registrants_last_name, ele_registrants_date_of_birth, ele_registrants_gender, ele_registrants_mailing_address, ele_registrants_phone, ele_registrants_email_address, ele_registrants_citizenship, ele_registrants_athletic_level, ele_entry_ele_entry_id) VALUES (:ele_registrant_first_name, :ele_registrants_middle_name, :ele_registrants_last_name, :ele_registrants_date_of_birth, :ele_registrants_gender, :ele_registrants_mailing_address, :ele_registrants_phone, :ele_registrants_email_address, :ele_registrants_citizenship, :ele_registrants_athletic_level, :ele_entry_ele_entry_id)";

            $dbcon->initiateConnection();
            $dbcon->query($query2);
            $dbcon->prepare();

            foreach($registrants as $registrant){

                $dbcon->bindValue(':ele_registrant_first_name', $registrant['ele_registrant_first_name']);
                $dbcon->bindValue(':ele_registrants_middle_name', $registrant['ele_registrants_middle_name']);
                $dbcon->bindValue(':ele_registrants_last_name', $registrant['ele_registrants_last_name']);
                $dbcon->bindValue(':ele_registrants_date_of_birth', $registrant['ele_registrants_date_of_birth']);
                $dbcon->bindValue(':ele_registrants_gender', $registrant['ele_registrants_gender']);
                $dbcon->bindValue(':ele_registrants_mailing_address', $registrant['ele_registrants_mailing_address']);
                $dbcon->bindValue(':ele_registrants_phone', $registrant['ele_registrants_phone']);
                $dbcon->bindValue(':ele_registrants_email_address', $registrant['ele_registrants_email_address']);
                $dbcon->bindValue(':ele_registrants_citizenship', $registrant['ele_registrants_citizenship']);
                $dbcon->bindValue(':ele_registrants_athletic_level', $registrant['ele_registrants_athletic_level']);
                $dbcon->bindValue(':ele_entry_ele_entry_id', $registrant['ele_entry_ele_entry_id']);

                try {

                    $dbcon->execute();

                } catch (Exception $e) {

                    $error++;

                }

            }

            //send email for invoice ID if the payment method is Western Union
            if( $ele_entry_payment_method == 'wu' ){

                $data['invoice']        = $ele_entry_invoice;
                $data['business']       = $entry['business'];
                $data['receiptEmail']   = $ele_entry_email;
                $data['amount']         = $entry['amount'];
                $data['mc_currency']    = self::getCurrency();
                self::sendMail($data);

            }

            if($error > 0){
                return array('success'=>false);
            } else {
                return array('success'=>true,'entry_id'=>$ID);
            }

        }

    }

    /**
     * @usage emails to both registrant and admin if the payment method is western union
     * @param array $data
     * @since 1.0
     */
    public static function sendMail( $data = array() ){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: <'.$data['business'].'>' . "\r\n";

        $reciept = "<h1>EMBRACE LIFE EVENT PAYMENT VERIFICATION (Western Union)</h1>";
        $reciept .= "<table>";
        $reciept .= "<tr>";
        $reciept .= "<td colspan='2'><h3>REGISTRATION INFO:</h3></td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td><strong>INVOICE ID:</strong></td>";
        $reciept .= "<td>".$data['invoice']."</td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td><strong>AMOUNT TO BE PAID:</strong></td>";
        $reciept .= "<td>".$data['amount']." ".$data['mc_currency']."</td>";
        $reciept .= "</tr>";
        $reciept .= "</table>";
        $reciept .= "<p>Present this email/Invoice ID together with your western union payment details to <strong>".$data['business']."</strong> validate your payment.</p>";

        $payment = "<h1>EMBRACE LIFE EVENTS PAYMENT DETAILS</h1><br>";
        $payment .= "<strong>Notice:</strong>A registration via Western Union has been made By: ".$data['receiptEmail']."<br>";
        $payment .= "Below is the info of the Registrant.";
        $payment .= "<hr>";
        $payment .= $reciept;

        mail($data['receiptEmail'], 'EMBRACE LIFE EVENT PAYMENT VERIFICATION (Western Union)', $reciept, $headers);
        mail($data['business'], 'REGISTRATION INFO', $payment, $headers);
    }

    /**
     * @usage get paypal saved settings in database
     * @return mixed
     * @since 1.0
     */
    public static function getPaypalSettings(){

        $paypal_options = get_option('paypal_settings');
        $paypal_options = unserialize($paypal_options);
        return $paypal_options;

    }

    /**
     * @usage get athletic level options in database
     * @return mixed
     * @since 1.0
     */
    public static function getAthleticOption(){

        $paypal_options = get_option('athletic_level');
        $paypal_options = unserialize($paypal_options);
        return $paypal_options;

    }

    /**
     * @usage get category options in database
     * @return mixed
     * @since 1.0
     */
    public static function getCategoryOption(){

        $paypal_options = get_option('team_category');
        $paypal_options = unserialize($paypal_options);
        return $paypal_options;

    }

    /**
     * @usage get Ele Settings in database
     * @return mixed
     * @since 1.0
     */
    public static function getEleSettings(){

        $ele_settings = get_option('ele_settings');
        $ele_settings = unserialize($ele_settings);
        return $ele_settings;

    }

    /**
     * @usage use to update Ele settings in database
     * @param $input
     * @return array
     * @since 1.0
     */
    public static function updateEleSettings($input){

        $data = array();
        foreach($input->data as $k => $v){
            $data[$k] = $v;
        }

        $data = serialize($data);

        $res = update_option('ele_settings',$data);

        if($res){
            return array('success'=>true);
        } else {
            return array('success'=>false);
        }

    }



    /**
     * @usage use to update paypal settings in database
     * @param $input
     * @return array
     * @since 1.0
     */
    public static function updatePaypalSettings($input){

        $data = array();
        foreach($input->data as $k => $v){
            $data[$k] = $v;
        }

        $data = serialize($data);

        $res = update_option('paypal_settings',$data);

        if($res){
            return array('success'=>true);
        } else {
            return array('success'=>false);
        }

    }

    /**
     * @usage use to update athletic options in database
     * @param $input
     * @return array
     * @since 1.0
     */
    public static function updateAthleticOption($input){

        $data = serialize($input->data);
        $res = update_option('athletic_level',$data);

        if($res){
            return array('success'=>true);
        } else {
            return array('success'=>false);
        }

    }

    /**
     * @usage update category options in database
     * @param $input
     * @return array
     * @since 1.0
     */
    public static function updateCategoryOption($input){

        $data = serialize($input->data);
        $res = update_option('team_category',$data);

        if($res){
            return array('success'=>true);
        } else {
            return array('success'=>false);
        }

    }

    /**
     * @usage check if captcha is valid
     * @param $input
     * @return bool
     * @since 1.0
     */
    public static function captchaIsValid($input){

        $entry = array();
        foreach( $input->entry_data as $k => $v ){
            $entry[$v->name] = strip_tags( stripslashes( $v->value ) );
        }

        if (md5($entry['captcha_code']) != $_SESSION['randomnr2']){
            return false;
        } else {
            return true;
        }

    }

    /**
     * @usage use to verify nonce
     * @param $input
     * @return bool
     * @since 1.0
     */
    public static function nonceIsVerified($input){

        $entry = array();
        foreach( $input->entry_data as $k => $v ){
            $entry[$v->name] = strip_tags( stripslashes( $v->value ) );
        }
        if ( !wp_verify_nonce( $entry['ele_nonce'], "ele_nonce")) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * @usage get currency settings in database (helper)
     * @return mixed
     */
    public static function getCurrency(){

        $dbcon = Connection::getInstance();

        $query = "SELECT * FROM wp_options WHERE option_name='paypal_settings'";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->execute();
        $result = $dbcon->fetchAssoc();
        $dbcon->resetData();

        $result1        = unserialize( $result[0]['option_value']);
        $result2        = unserialize( $result1 );

        $currency         = $result2['currency'];
        return $currency;

    }

}