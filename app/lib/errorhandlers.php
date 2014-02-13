<?php
function handleError($e, $app){
    echoResponse($e->getCode(), null, $e->getMessage());
    $app->stop();
}

$app->error(function(\Exception $e) use ($app){
    handleError($e, $app);
});

$app->notFound(function() use ($app){
   $e = new \Exception("Resource not found", 404);
   handleError($e, $app);
});