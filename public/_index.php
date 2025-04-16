<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require "../vendor/autoload.php";
use Cmcintosh\Httpstack\DOM\Document;
use Cmcintosh\Httpstack\Http\Request;
use Cmcintosh\Httpstack\Http\Response;
use Phroute\Phroute;

$req = new Request();
$res = new Response();
$router = new Phroute\RouteCollector();
$router->get('/', function (Request $request, Response $response) {
    $response->setBody('<h1>Welcome to the Home Page</h1>');
    return $response;
});
$dispatcher = new Phroute\Dispatcher($router->getData());
$httpResponse = $dispatcher->dispatch($req->getMethod(), $req->getUri());
if($httpResponse instanceof Response) {
    $httpResponse->send();
} else {
    echo $httpResponse;
}
?>