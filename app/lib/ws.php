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
    $request_params = getJsonParam();
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        $app = \Slim\Slim::getInstance();

        echoResponse(400, null, 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty . Given ' . print_r($request_params, true));
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
        echoResponse(400, $response);
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

/**
 * Get the JSON Request parameter value
 * @param Key : key of the parameter
 * @param Default : default value if parameter doesn't exists
 */
function getJsonParam($key = null, $default = null)
{
    $result = $default;
    try{
        $app = \Slim\Slim::getInstance();
        $params = json_decode($app->request()->getBody(), true);

        if(!is_null($key))
        {
            if(isset($params[$key]))
            {
                $result = $params[$key];
            }
            else
            {
                $result = $default;
            }
        }
        else
        {
            $result = $params;
        }
    }
    catch(Exception $e)
    {
        $result = $e->getMessage();
    }

    return $result;
}