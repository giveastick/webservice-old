<?php
function handleError($e, $app){
    $response["error"] = true;
    $response["message"] = $e->getMessage();
    echoResponse($e->getCode(), $response);
    $app->stop();
}

$app->error(function(\Exception $e) use ($app){
    handleError($e, $app);
});

$app->notFound(function() use ($app){
   $e = new \Exception("Resource not found", 404);
   handleError($e, $app);
});