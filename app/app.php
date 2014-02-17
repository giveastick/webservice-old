<?php

require 'lib/errorhandlers.php';

/**
 * Test
 * url - /ping
 * method - GET
 * params - none
 */
$app->get('/ping', function() use ($app){
    $response['pong'] = 'pong';

    echoResponse(200, $response);
});

/**
 * Test
 * url - /veronique
 * method - POST
 * params - none
 */
$app->post('/veronique', function() use ($app){
    $response['davina'] = 'toutouyoutou...toutouyoutou...toutouyoutouyoutou...toutoutoutoutou';

    echoResponse(200, $response);
});


/**
 * User login
 * url - /login
 * method - GET
 * params - email, password
 */
$app->post('/login', function() use ($app){
    verifyRequiredParams(array('email','password'));

    if(getJsonParam('password') == 'good' && getJsonParam('email') == 'demo@giveastick.com')
    {
        $response['success'] = true;
        $response['user']['email'] = 'demo@giveastick.com';
        $response['user']['nick'] = 'dem0Man';
        $response['user']['group'] = 'IIA2013';
    }
    else
    {
        $response['error'] = true;
        $response['error_id'] = 'login.credidentials.bad';
        $response['error_message'] = 'Identifiants incorrects';
    }

    echoResponse(200, $response);
});

/**
 * Register
 * url - /register
 * method - POST
 * params - email, password, group
 */
$app->post('/register', function() use ($app){
    verifyRequiredParams(array('email','password', 'group', 'nick'));

    $formError = false;

    if(strlen($app->request->post('password')) < 6)
        $formError = 'Merci de fournir un mot de passe superieur à 6 caractères';

    if(is_null($app->request->post('password')))
        $formError = 'Merci de fournir un mot de passe';

    if(is_null($app->request->post('nick')))
        $formError = 'Merci de fournir un pseudo';

    if(is_null($app->request->post('group')))
        $formError = 'Merci de fournir un groupe';

    if(is_null($app->request->post('email')) || !filter_var($app->request->post('email'), FILTER_VALIDATE_EMAIL))
        $formError = 'Merci de fournir un email';

    if($formError)
    {
        $response['error'] = true;
        $response['error_id'] = 'register.form.error';
        $response['error_message'] = $formError;
    }
    else
    {
        $response['success'] = true;
        $response['user']['id'] = 1;
        $response['user']['email'] = 'demo@giveastick.com';
        $response['user']['nick'] = $app->request->post('nick');
        $response['user']['group'] = $app->request->post('group');
    }

    echoResponse(201, $response);
});

/**
 * Sticks list
 * url - /sticks
 * method - GET
 */
$app->get('/sticks', 'authenticate', function() use ($app){

    $users = array('flamby','petitom', 'fifi','alextiti06', 'Maxxx', 'marchombr', 'NikoWoot', 'OverFlo', 'carlito', 'Ta€KWend053SISI', 'frodonic', 'xx-HappyDestroy-xx', 'ch0uK', 'LoCoy', 'FabLaRage', 'MickyMouse', 'goyaVI');
    sort($users);

    $i = 0;
    $list = array();
    foreach($users as $u)
    {
        $list[$i]['userid'] = $i;
        $list[$i]['pseudo'] = $u;
        $list[$i]['sticks'] = rand(0,30);

        if($u == 'alextiti06')
            $list[$i]['sticks'] += 5;

        $i++;
    }

    $response['list'] = $list;
    $response['group'] = 'IIA2013';

    echoResponse(200, $response);
});

/**
 * Send vote for a new stick
 * url - /sticks
 * method - POST
 * params - userid
 */
$app->post('/sticks', 'authenticate', function() use ($app){
    verifyRequiredParams(array('userid'));

    $response['success'] = 'true';

    echoResponse(201, $response);
});

/**
 * Votes for a stick
 * url - /vote
 * method - POST
 * params - stickid
 */
$app->post('/votes', 'authenticate', function() use ($app){
    verifyRequiredParams(array('stickid'));

    $response['success'] = true;
    $response['votes'] = rand(0,20);

    echoResponse(201, $response);
});

/**
 * Sends a sanction
 * url - /sanction
 * method - POST
 */
$app->post('/sanction', 'authenticate', function() use ($app){

    $response['success'] = true;
    $response['sanctions_in_last_minute'] = rand(0,20);

    echoResponse(201, $response);
});

/**
 * Gets a sanction
 * url - /sanction
 * method - GET
 */
$app->get('/sanction', 'authenticate', function() use ($app){

    $words = getWords(3);
    $response['message'] = 'Tu dois placer les mots ' . $words[0] . ', ' . $words[1] . ' et ' . $words[2] . ' dans l\'heure qui suit';

    echoResponse(200, $response);
});

/**
 * Debugs the post data
 * url - /sanction
 * method - POST
 */
$app->post('/debug', function() use ($app){

    var_dump(getJsonParam());
    exit();
    $response['post'] = $_POST;
    $response['get'] = $_GET;
    $response['request'] = $_REQUEST;
    $response['cookies'] = $_COOKIE;
    $response['postSlim'] = $app->request->post();
    $response['jsonParameters'] =  getJsonParam();

    echoResponse(200, $response);
});

/**
 * Debugs the post data
 * url - /sanction
 * method - GET
 */
$app->get('/debug', function() use ($app){

    $response['post'] = $_POST;
    $response['get'] = $_GET;
    $response['request'] = $_REQUEST;
    $response['cookies'] = $_COOKIE;
    $response['postSlim'] = $app->request->post();
    $response['jsonParameters'] =  getJsonParam();

    echoResponse(200, $response);
});

function getWords($nb)
{
    $filename = 'src/fr_dictionnaire.txt';
    $lines = file($filename, FILE_IGNORE_NEW_LINES);

    $indexes = array_rand($lines, $nb);
    $return = array();
    foreach($indexes as $i)
        $return[] = $lines[$i];

    return $return;
}

$app->run();
