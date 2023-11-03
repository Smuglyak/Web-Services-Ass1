<?php

declare(strict_types=1);

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    public function handleAboutApi(Request $request, Response $response, array $uri_args)
    {
        $data = array(
            'about' => 'Welcome, this is a Web service that provides information about a film rental store. You have multiple resources that you can access to see information about movies from the store. Refer to resources to send the requests to',
            'requirements' => 'When sending http requests, you need to put accept header as: application/json. Also, for every http request, you have to put page and page_size uri parameters to see specific page and size of the page.',
            'resources' => '/hello, /films, /films/{film_id}, /films(post), /customers, /customers/{customer_id}, /customers/{customer_id}/films, /customers/{customer_id}(delete), /actors, /actors/{actor_id}/films, /actors(post), /categories/{category_id}/films',
            'localhost/films-api/hello' => 'says hello',
            'localhost/films-api/films/{film_id}' => 'Gets the details of the specified film.',
            'localhost/films-api/films' => 'Gets a list of zero or more film resources that match the request criteria which are title, description, rating, special_features, and category.',
            'localhost/films-api/customers' => 'Gets a list of zero or more customer resources that match the request criteria that are first name, last name, city, and country.',
            'localhost/films-api/films (post)' => 'Creates one or more films. You need to put one or more films in a json format to create',            
            'localhost/films-api/customers/{customer_id}/films' => 'Gets the list of films rented by a given customer. Some of the filters are: Rental data: between two dates, Film rating and special features, Category name.',
            'localhost/films-api/customers/{customer_id} (delete)' => 'Removes a given customer from the database.',
            'localhost/films-api/actors' => 'Gets the list of actors that match the request criteria that are First name and last name: (starts with, or it contains the specified value).',
            'localhost/films-api/actors/{actor_id}/films' => 'Get the list of films in which the specified actor played a role and match the request criteria that are Film category: name of the category, Rating, Film length: equal or greater than the specified value.',
            'localhost/films-api/actors (post)' => 'Creates one or more actors. You need to put one or more films in a json format to create',
            'localhost/films-api/categories/{category_id}/films' => 'Gets the list of films that belong to the specified
            category and match the request criteria that are Length (in minutes), and Rating.',
        );
        return $this->prepareOkResponse($response, $data);
    }
}
