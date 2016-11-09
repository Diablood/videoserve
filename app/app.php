<?php
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
ErrorHandler::register();
ExceptionHandler::register();

$app->register(new Silex\Provider\DoctrineServiceProvider());

// Register JSON data decoder for JSON requests
$app->before(function (Request $request) {
	if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
		$data = json_decode($request->getContent(), true);
		$request->request->replace(is_array($data) ? $data : array());
	}
});


$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods','GET,POST,HEAD,DELETE,PUT,OPTIONS');
});
