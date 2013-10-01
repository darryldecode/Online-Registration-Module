<?php

class Connection {

    public static $instance;


    /*
    *-----------------------------
    * Database Handlers
    *-----------------------------
    */
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $prefix;
    var $dbhandler = null;


    /*
    *-----------------------------
    * Process
    *-----------------------------
    */
    private $query;
    private $stmt;
    var $isSuccess;

    public $ele_entry;
    public $ele_registrants;


    public function __construct( $data = array() ){

        global $wpdb;
        /*
        *-----------------------------
        * Setup Database Handlers
        *-----------------------------
        */
        if( empty($data) ){
            //if use for wordPress
            $this->host 	= DB_HOST;
            $this->dbname	= DB_NAME;
            $this->username	= DB_USER;
            $this->password	= DB_PASSWORD;
            $this->prefix	= $wpdb->base_prefix;
        } else {
            //if use outside wordPress
            $this->host 	= $data['DB_HOST'];
            $this->dbname	= $data['DB_NAME'];
            $this->username	= $data['DB_USER'];
            $this->password	= $data['DB_PASSWORD'];
            $this->prefix	= $wpdb->base_prefix;
        }


        /*
        *-----------------------------
        * Setup Tables
        *-----------------------------
        */
        $this->ele_entry 	    = 'wp_ele_entry';
        $this->ele_registrants 	= 'wp_ele_registrants';

        self::$instance = $this;

    }

    public static function getInstance(){
        if( !(self::$instance instanceof self) )
        {
            return self::$instance = new self();
        } else {
            return self::$instance;
        }
    }


    function initiateConnection(){

        $host 		= $this->host;
        $dbname 	= $this->dbname;
        $username 	= $this->username;
        $password 	= $this->password;

        $this->dbhandler = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
        $this->dbhandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }


    function query( $query ){
        $this->query = $query;
    }


    function prepare(){
        $query = $this->query;
        $this->stmt = $this->dbhandler->prepare( $query );
    }


    function bindValue( $key , $value ){
        $this->stmt->bindValue( $key ,	$value );
    }


    function bindParam( $key , $value ){
        $this->stmt->bindParam( $key ,	$value );
    }


    function execute(){
        $this->isSuccess = $this->stmt->execute();
    }


    function rowCount(){
        return $this->stmt->rowCount();
    }


    function fetchAssoc(){
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function fetchNum(){
        return $this->stmt->fetchAll(PDO::FETCH_NUM);
    }


    function fetchObj(){
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }


    function beginTransaction(){
        $this->dbhandler->beginTransaction();
    }


    function commit(){
        $this->dbhandler->commit();
    }


    function rollBack(){
        $this->dbhandler->rollBack();
    }


    function lastInsertedID(){
        return $this->dbhandler->lastInsertId();
    }


    function resetData(){
        unset( $this->query );
        unset( $this->stmt );
    }


    function destroyConnection(){
        unset( $this->dbhandler );
    }


}