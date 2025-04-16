<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require "../vendor/autoload.php";


use Httpstack\Http\{Request, Response};
use Httpstack\App\{Container, Router};
$objContainer = new Container();
$objContainer->bind("abstractName", function () {
    return new class {
        public function __construct() {
            echo "Abstract Name";
        }
    };
});
$objRouter = new Router($objContainer);
$objRequest = new Request();
$objResponse = new Response();
$objRouter->get('/',["abstractName"], function ($request, $response) {
    var_dump($request);
});
$objRouter->dispatch($objRequest, $objResponse);