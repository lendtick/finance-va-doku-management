<?php
/*
    Param needed :
    - shared_key     : from doku mall
    - mall_id        : from doku mall
    - amount         : total amount
    - trans_id       : VA Number
    - currency       : code curenccy in doku
    - name           : name
    - phone          : phone
    - email          : email
    - address        : address
    - chain_merchant : chain_merchant
*/

$response = array('status'=>0, 'message'=>"Tidak diperkenankan akses", 'data' => NULL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('Doku.php');

    date_default_timezone_set('Asia/Jakarta');

    //Doku_Initiate::$sharedKey = 'k8UhY5t4RF4e';
    // Doku_Initiate::$sharedKey = 'eaM6i1JjS19J';
    Doku_Initiate::$sharedKey = $_POST['shared_key'];
    Doku_Initiate::$mallId = $_POST['mall_id'];

    $params = array(
        'amount' => $_POST['amount'],
        'invoice' => $_POST['trans_id'],
        'currency' => $_POST['currency']
    );

    $words = Doku_Library::doCreateWords($params);

    $customer = array(
        'name' => $_POST['name'],
        'data_phone' => $_POST['phone'],
        'data_email' => $_POST['email'],
        'data_address' => $_POST['address']
    );

    $dataPayment = array(
        'req_mall_id' => $_POST['mall_id'],
        'req_chain_merchant' => $_POST['chain_merchant'],
        'req_amount' => $params['amount'],
        'req_words' => $words,
        'req_trans_id_merchant' => $_POST['trans_id'],
        'req_purchase_amount' => $params['amount'],
        'req_request_date_time' => date('YmdHis'),
        'req_session_id' => sha1(date('YmdHis')),
        'req_email' => $customer['data_email'],
        'req_name' => $customer['name']
    );

    $feed = Doku_Api::doGeneratePaycode($dataPayment);

    if($feed->res_response_code == '0000'){
        $feed['message_data'] = 'GENERATE SUCCESS -- ';
        $feed['words'] = $words;
        $feed['session_id'] = $dataPayment['req_session_id'];
    }else{
        $feed['message_data'] = 'GENERATE FAILED -- ';
    }
    
    $response = array_merge($response, array('status' => 1, 'message' => "Anda diperkenankan akses", 'data' => $feed));
}

echo json_encode($response);
die();

?>
