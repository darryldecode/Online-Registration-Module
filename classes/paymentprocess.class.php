<?php

/**
 * Class eleProcessRecords
 * @usage: IPN Process Records Transactions
 * @since 1.0
 * @author Darryl Fernandez
 */

class eleProcessRecords {

    /**
     * @usage use to set credentials on connection
     * @return array
     * @since 1.0
     */
    public static function getDBCredentials(){
        $data = array();
        $data['DB_HOST']    = DB_HOST;
        $data['DB_NAME']    = DB_NAME;
        $data['DB_USER']    = DB_USER;
        $data['DB_PASSWORD']= DB_PASSWORD;
        return $data;
    }

    /**
     * @usage use to update status as paid
     * @param $entry_id
     * @param $invoice
     * @param $txn_id
     * @return bool
     * @since 1.0
     */
    public static function updateStatus($entry_id,$invoice,$txn_id){

        $dbcon = new Connection(self::getDBCredentials());

        $query = "UPDATE ".$dbcon->ele_entry." SET ele_entry_status=1, ele_entry_invoice=:ele_entry_invoice, ele_entry_txn_id=:ele_entry_txn_id WHERE ele_entry_id=:ele_entry_id";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->bindValue(':ele_entry_id',$entry_id);
        $dbcon->bindValue(':ele_entry_invoice',$invoice);
        $dbcon->bindValue(':ele_entry_txn_id',$txn_id);

        try {
            $dbcon->execute();
            $dbcon->resetData();
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }
        return $result;
    }

    /**
     * @usage use to get paypal mode saved in database (if sandbox or live)
     * @return mixed
     * @since 1.0
     */
    public static function getMode(){

        $dbcon = new Connection(self::getDBCredentials());

        $query = "SELECT * FROM wp_options WHERE option_name='paypal_settings'";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->execute();
        $result = $dbcon->fetchAssoc();
        $dbcon->resetData();

        $result1        = unserialize( $result[0]['option_value']);
        $result2        = unserialize( $result1 );

        $mode = $result2['mode'];
        return $mode;

    }

    /**
     * @usage use to get the business email save on our database, this is use to check if the
     * transaction business email matches our business email on database, to make sure data is not altered
     *
     * @return mixed
     * @since 1.0
     */
    public static function getBusinessEmail(){

        $dbcon = new Connection(self::getDBCredentials());

        $query = "SELECT * FROM wp_options WHERE option_name='paypal_settings'";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->execute();
        $result = $dbcon->fetchAssoc();
        $dbcon->resetData();

        $result1        = unserialize( $result[0]['option_value']);
        $result2        = unserialize( $result1 );

        $business_email = $result2['business'];
        return $business_email;

    }

    /**
     * @usage use to get amount saved on our settings, this is use to make sure the amount in transaction
     * is not altered
     *
     * @return mixed
     * @since 1.0
     */
    public static function getAmount(){

        $dbcon = new Connection(self::getDBCredentials());

        $query = "SELECT * FROM wp_options WHERE option_name='paypal_settings'";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->execute();
        $result = $dbcon->fetchAssoc();
        $dbcon->resetData();

        $result1        = unserialize( $result[0]['option_value']);
        $result2        = unserialize( $result1 );

        $amount         = $result2['amount'];
        return $amount;

    }

    /**
     * @usage use to get currency on our settings in database, this is use to make sure currency is no altered
     * @return mixed
     * @since 1.0
     */
    public static function getCurrency(){

        $dbcon = new Connection(self::getDBCredentials());

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

    /**
     * @usage use to make sure the transaction is not duplicated
     * @param $txn_id
     * @return bool
     * @since 1.0
     */
    public static function txnExist($txn_id){

        $dbcon = new Connection(self::getDBCredentials());

        $query = "SELECT * FROM wp_ele_entry WHERE ele_entry_txn_id=:ele_entry_txn_id";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->bindValue(':ele_entry_txn_id',$txn_id);
        $dbcon->execute();
        $res = $dbcon->rowCount();
        $dbcon->resetData();

        if( $res != 0 ){
            return true;
        } else {
            return false;
        }

    }

    /**
     * @usage use to get package price save on our database
     * @return mixed
     * @since 1.0
     */
    public static function getPackagePrice(){

        $dbcon = new Connection(self::getDBCredentials());

        $query = "SELECT * FROM wp_options WHERE option_name='ele_settings'";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->execute();
        $result = $dbcon->fetchAssoc();
        $dbcon->resetData();

        $result1        = unserialize( $result[0]['option_value']);
        $result2        = unserialize( $result1 );

        $packagePrice         = $result2['package_1'];
        return $packagePrice;

    }

    /**
     * @usage use to send email
     * @param $data
     * @return void
     * @since 1.0
     */
    public static function sendEmail($data){

        $item_name          = $data['item_name'];
        $payment_amount     = $data['mc_gross'];
        $txn_id             = $data['txn_id'];
        $receiver_email     = $data['receiver_email'];
        $payer_email        = $data['payer_email'];
        $invoice            = $data['invoice'];
        $payment_currency   = $data['currency'];

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: <'.$receiver_email.'>' . "\r\n";

        $reciept = "<h1>EMBRACE LIFE EVENT PAYMENT RECIEPT</h1>";
        $reciept .= "<table>";
        $reciept .= "<tr>";
        $reciept .= "<td colspan='2'>You have successfully paid to: ".$receiver_email."</td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td colspan='2'><h3>PAYMENT INFO:</h3></td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td><strong>Item Name:</strong></td>";
        $reciept .= "<td>".$item_name."</td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td><strong>Amount Paid:</strong></td>";
        $reciept .= "<td>".$payment_amount." ".$payment_currency."</td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td><strong>Transaction ID:</strong></td>";
        $reciept .= "<td>".$txn_id."</td>";
        $reciept .= "</tr>";
        $reciept .= "<tr>";
        $reciept .= "<td><strong>INVOICE ID:</strong></td>";
        $reciept .= "<td>".$invoice."</td>";
        $reciept .= "</tr>";
        $reciept .= "</table>";
        $reciept .= "<p>Present this reciept to <strong>".$receiver_email."</strong> validate your payment.</p>";

        $payment = "<h1>EMBRACE LIFE EVENTS PAYMENT DETAILS</h1><br>";
        $payment .= "<strong>Notice:</strong> A payment has been made By: ".$payer_email."<br>";
        $payment .= "Below is the receipt of the Payer.";
        $payment .= "<hr>";
        $payment .= $reciept;

        mail($payer_email, 'Embrace Life Events Official Reciept', $reciept, $headers);
        mail($receiver_email, 'Embrace Life Events Payment', $payment, $headers);

    }

}