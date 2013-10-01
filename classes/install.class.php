<?php

class EleInstall {

    /**
     *------------------------------------------------------
     * Syntax to create ELE Schema
     *------------------------------------------------------
     */
    static $tables = array(

        'wp_ele_entry' => '(
          `ele_entry_id` INT NOT NULL AUTO_INCREMENT ,
          `ele_entry_team_name` VARCHAR(45) NULL ,
          `ele_entry_category_name` VARCHAR(45) NULL ,
          `ele_entry_email` VARCHAR(45) NULL ,
          `ele_entry_invoice` VARCHAR(45) NULL ,
          `ele_entry_txn_id` VARCHAR(45) NULL ,
          `ele_entry_payment_method` VARCHAR(45) NULL ,
          `ele_entry_status` INT NULL DEFAULT 0 ,
          `ele_entry_registration_date` VARCHAR(45) NULL ,
          PRIMARY KEY (`ele_entry_id`) )
        ENGINE = InnoDB;',

        'wp_ele_registrants' => '(
          `ele_registrants_id` INT NOT NULL AUTO_INCREMENT ,
          `ele_registrant_first_name` VARCHAR(45) NULL ,
          `ele_registrants_middle_name` VARCHAR(45) NULL ,
          `ele_registrants_last_name` VARCHAR(45) NULL ,
          `ele_registrants_date_of_birth` VARCHAR(45) NULL ,
          `ele_registrants_gender` VARCHAR(45) NULL ,
          `ele_registrants_mailing_address` VARCHAR(45) NULL ,
          `ele_registrants_phone` VARCHAR(45) NULL ,
          `ele_registrants_email_address` VARCHAR(45) NULL ,
          `ele_registrants_citizenship` VARCHAR(45) NULL ,
          `ele_registrants_athletic_level` VARCHAR(45) NULL ,
          `ele_entry_ele_entry_id` INT NOT NULL ,
          PRIMARY KEY (`ele_registrants_id`) ,
          INDEX `fk_ele_registrants_ele_entry_idx` (`ele_entry_ele_entry_id` ASC) ,
          CONSTRAINT `fk_ele_registrants_ele_entry`
            FOREIGN KEY (`ele_entry_ele_entry_id` )
            REFERENCES `wp_ele_entry` (`ele_entry_id` )
            ON DELETE CASCADE
            ON UPDATE NO ACTION)
        ENGINE = InnoDB;',

    );



    /**
    * ---------------------------------------------------
    * check if the table wp_ele_entry exists
    * so we can know that the app is already install
    * ---------------------------------------------------
    */
    public static function isInstalled(){

        $dbcon = Connection::getInstance();

        $query = "SELECT 1 FROM ".$dbcon->ele_entry."";

        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();

        try {

            $dbcon->execute();
            $dbcon->resetData();
            return true;

        } catch (Exception $e) {

            return false;

        }

    }

    public static function dropSchema(){
        foreach(self::$tables as $table => $def){
            $query = "drop table if exists " . $table;
            self::process($query);
        }
    }

    public static function createSchema(){
        foreach(self::$tables as $table => $def){
            $query = "create table IF NOT EXISTS " . $table . " " . $def;
            self::process($query);
        }
    }

    public static function process( $query ){
        $dbcon = Connection::getInstance();
        $dbcon->initiateConnection();
        $dbcon->query($query);
        $dbcon->prepare();
        $dbcon->execute();
        $dbcon->resetData();
    }

    public static function setOptions($eleSet){

        $site_url   = $eleSet['site_url'];
        $ELE_URI    = $eleSet['ELE_URI'];

        $options = array(
            'paypal_settings' => array(
                'mode'          => 'sandbox',
                'business'      => 'engrdarrylfernandez@gmail.com',
                'notify_url'    => $ELE_URI.'ipn.php',
                'item_name'     => 'Registration Fee',
                'currency'      => 'USD',
                'item_number'   => 123,
                'amount'        => 10,
                'invoice'       => 'APRSKY1945',
                'return'        => $site_url.'/success/',
                'cancel_return' => $site_url.'/cancel/'
            ),
            'athletic_level'	=> array(
                'beginner',
                'intermediate',
                'advanced'
            ),
            'team_category' => array(
                'men',
                'women',
                'mixed',
                'intermediate'
            ),
            'ele_settings' => array(
                'version'       => '1.0',
                'ele_safe_mode' => 'enabled',
                'tos_link'      => $site_url.'/terms-and-condition/',
                'western_union' => $site_url.'/pay-via-western-union/',
                'paging_display'=> 20,
                'package_1'     => 650
            )
        );

        foreach ($options as $key => $value){
            $value = serialize($value);
            add_option($key,$value );
        }

    }


}