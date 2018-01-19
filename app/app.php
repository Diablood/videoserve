<?php
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
ErrorHandler::register();
ExceptionHandler::register();

$app->register(new Silex\Provider\DoctrineServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'foo' => array('pattern' => '^/foo'), // Exemple d'une url accessible en mode non connecté
        'default' => array(
            'pattern' => '^.*$',
            'anonymous' => true, // Indispensable car la zone de login se trouve dans la zone sécurisée (tout le front-office)
            'form' => array('login_path' => '/', 'check_path' => 'connexion'),
            'logout' => array('logout_path' => '/deconnexion'), // url à appeler pour se déconnecter
            'users' => (function($app) {
                // La classe App\User\UserProvider est spécifique à notre application et est décrite plus bas
                return new App\User\UserProvider($app['db']);
            }),
        ),
    ),
    'security.access_rules' => array(
        // ROLE_USER est défini arbitrairement, vous pouvez le remplacer par le nom que vous voulez
        array('^/.+$', 'ROLE_USER'),
        array('^/foo$', ''), // Cette url est accessible en mode non connecté
    )
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Register JSON data decoder for JSON requests
$app->before(function (Request $request) {
	if (0 === strpos($request->headers->get('Content-Type'), 'application/json;')) {
		$data = json_decode($request->getContent(), true);
		$request->request->replace(is_array($data) ? $data : array());
	}
});

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods','GET,POST,HEAD,DELETE,PUT,PATCH,OPTIONS');
});
