<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

//home page probably never getting used
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

//read csv file
$app->get('/read', function (Request $request, Response $response, $args) {
    // get file
    // parse file into cols
    // return parsed file as json
    $response->getBody()->write("Hello world!");
    return $response;
});

//update current csv
$app->get('/update', function (Request $request, Response $response, $args) {
    // check file exists
    // parse update
    // which row and col are we updating
    // validation?
    // return result code
    $response->getBody()->write("Hello world!");
    return $response;
});

//output new csv from subset of data
$app->get('/returnNew', function (Request $request, Response $response, $args) {
    // filter file for only results that match
    // build new file from filtered results
    // return resulting file for download
    $response->getBody()->write("Hello world!");
    return $response;
});

//clear data (don't erase file just clear what we have stored)
$app->get('/returnNew', function (Request $request, Response $response, $args) {
    //clear cache
    //return empty homepage
    $response->getBody()->write("Empty Form aka homepage");
    return $response;
});

//show list of previous files (or whatever we currently have on disk)
$app->get('/returnNew', function (Request $request, Response $response, $args) {
    //find all files in storage
    //return file names and # of rows
    $response->getBody()->write("");
    return $response;
});

$app->run();