<?php

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (!isset($headers['Authorization'])) {
        // api key is missing in header
        echoResponse(400, null, "Api key is misssing");
        $app->stop();
    }
}

/**
 *  * Verifying required params posted or not
 *  */
function verifyRequiredParams($required_fields)
{
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();

        echoResponse(400, null, 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty');
        $app->stop();
    }
}


/**
 *  * Validating email address
 *  */
function validateEmail($email)
{
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 *  * Echoing json response to client
 *  * @param String $status_code Http response code
 *  * @param Int $response Json response
 *  */
function echoResponse($status_code, $data, $error_message = null)
{
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    if((int) $status_code >= 200 && (int) $status_code < 300)
    {
        $response['success'] = true;
        $response['data'] = $data;
    }
    else
    {
        $response['error'] = true;

        if(!empty($error_message))
        {
            $response['message'] = $error_message;
        }
    }

    echo json_encode(array('android'=>$response));
}