<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\CategoriesController;
use Vanier\Api\Controllers\FilmsController;
use Vanier\Api\Controllers\CustomersController;
use Vanier\Api\Controllers\ActorsController;

// Import the app instance into this file's scope.
global $app;

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// ROUTE: GET /
$app->get('/', [AboutController::class, 'handleAboutApi']);

// ROUTE: GET /films
$app->get('/films', [FilmsController::class, 'handleGetFilms']);

// ROUTE: GET /hello
$app->get('/hello', function (Request $request, Response $response, $args) {

    $response->getBody()->write("Reporting! Hello there!");
    return $response;
});

// ROUTE: GET /films/{film_id}
$app->get('/films/{film_id}', [FilmsController::class, 'handleGetFilmById']);

//ROUTE: POST /films
$app->post('/films', [FilmsController::class, 'handleCreateFilms']);

//ROUTE: GET /customers
$app->get('/customers', [CustomersController::class, 'handleGetCustomers']);

// ROUTE: GET /customers/{customer_id}
$app->get('/customers/{customer_id}', [CustomersController::class, 'handleGetCustomerById']);

// ROUTE: GET /customers/{customer_id}/films
$app->get('/customers/{customer_id}/films', [CustomersController::class, 'handleGetFilmsByCustomer']);

// ROUTE: DELETE /customers/{customer_id}/films
$app->delete('/customers/{customer_id}', [CustomersController::class, 'deleteCustomerById']);

//ROUTE: GET /actors
$app->get('/actors', [ActorsController::class, 'handleGetActors']);

//ROUTE: GET /actors/{actor_id}/films
$app->get('/actors/{actor_id}/films', [ActorsController::class, 'handleActorFilms']);

//ROUTE: POST /actors/
$app->post('/actors', [ActorsController::class, 'handleCreateActors']);

//ROUTE: GET /categories/{category_id}/films
$app->get('/categories/{category_id}/films', [CategoriesController::class, 'handleCategoryFilms']);
