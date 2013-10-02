<?php

//restrict request from other domains
header("Access-Control-Allow-Origin: ".get_site_url());

if( ! defined('ABSPATH')) exit('No direct script access allowed');

if( !session_id()) session_start();


require_once ELE_PATH.'classes/database.class.php';
require_once ELE_PATH.'_model/model.php';

/**
 * Controller for Ajax Handler
 * @note uses php://input because angular xmlhttp requests
 * behaves differently. Angular sends serialized JSON, jQuery sends a query string
 * so we use a raw php parser for requests (post,get,etc) which is php://input
 */
$input  = file_get_contents('php://input');
$input  = json_decode($input);

if( !empty($input) && is_object($input) ){
    $action = $input->action;
}

if(!empty($action)){
    call_user_func($action, $input);
}


function ele_get_entries($input){

    $data = EleModel::getEntries($input);
    echo json_encode($data);
    exit;

}


function ele_get_total_entries(){

    $data = EleModel::getTotalEntries();
    echo json_encode($data);
    exit;

}


function ele_set_paid($input){

    $data = EleModel::setPaid($input);
    echo json_encode($data);
    exit;

}


function ele_add_entry($input){

    if(!EleModel::captchaIsValid($input)){
        echo json_encode(array('success'=>false,'captchaError'=>true));
        exit;
    }

    $data = EleModel::addEntry($input);
    echo json_encode($data);
    exit;

}


function ele_get_paypal_settings(){

    $paypal_options = EleModel::getPaypalSettings();
    echo json_encode( $paypal_options );
    exit;

}


function ele_get_athletic_options(){

    $athletic_options = EleModel::getAthleticOption();
    echo json_encode( $athletic_options );
    exit;

}

function ele_get_team_category_options(){

    $category_options = EleModel::getCategoryOption();
    echo json_encode( $category_options );
    exit;

}

function ele_get_ele_settings(){

    $ele_settings = EleModel::getEleSettings();
    echo json_encode( $ele_settings );
    exit;

}

function ele_update_ele_settings($input){

    $res = EleModel::updateEleSettings($input);
    echo json_encode($res);
    exit;

}


function ele_update_paypal_settings($input){

    $res = EleModel::updatePaypalSettings($input);
    echo json_encode($res);
    exit;

}

function ele_update_athletic_options($input){

    $res = EleModel::updateAthleticOption($input);
    echo json_encode($res);
    exit;

}

function ele_update_category_options($input){

    $res = EleModel::updateCategoryOption($input);
    echo json_encode($res);
    exit;

}
