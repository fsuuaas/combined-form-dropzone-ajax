<?php
ob_get_contents();
ob_end_clean();



if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0)
{
    $displayMaxSize = ini_get('post_max_size');
    switch (substr($displayMaxSize,-1)) {
        case 'G':
            $displayMaxSize = $displayMaxSize * 1024;
        case 'M':
            $displayMaxSize = $displayMaxSize * 1024;
        case 'K':
            $displayMaxSize = $displayMaxSize * 1024;
    }   
    $CONTENT_LENGTH = $_SERVER['CONTENT_LENGTH'];

    $error = 'Posted data is too large. '.$CONTENT_LENGTH.' bytes exceeds the maximum size of '.$displayMaxSize.' bytes.';
    responseHandler(false, $error);
    /*if( $_SERVER['CONTENT_LENGTH'] > ((int) ini_get('post_max_size') * 1024 * 1024) ) {
        responseHandler(false, "Max post content exceeded, Please upload smaller files OR Increase your max POST_CONTENT_LENGTH.");
    }*/

}

require_once('./app/config/config.php');
require_once("./autoload.php");

if (!empty($_POST)) {
    try {
        $app = new App();
        $data = [
            'data' => $_POST,
            'files' => $_FILES
        ];
        $app->save($data);
        responseHandler(true, "Image uploaded successfully..");
    } catch (Exception $e) {
        responseHandler(false, $e->getMessage());
    }
    
}


/**
 * ResponseHandler
 * 
 * @param boolean $status true|false
 * 
 * @param string $msg
 */
function responseHandler($status, $msg){
    
    if($status){
        http_response_code(200);
        $response = [
            "code" => 200,
            "message" => $msg
        ];
        echo json_encode($response); 
    } else {
        http_response_code(500);
        $response = [
            "code" => 500,
            "message" => $msg
        ];
        echo json_encode($response);
    }
    exit;
}