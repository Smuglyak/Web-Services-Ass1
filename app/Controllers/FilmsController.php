<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Vanier\Api\Models\FilmsModel;
use Vanier\Api\Exceptions\HttpInvalidInputsException;
use Vanier\Api\Helpers\Input;

class FilmsController extends BaseController
{
    private $films_model = null;

    private array $validation_rules = array(
        // Rules for validating films' properties
        'title' => [
            'required',
            ['lengthMax', 128]
        ],
        'release_year' => [
            'required',
        ],
        'language_id' => [
            'required',
            'integer'
            // You can add more rules here if needed
        ],
        'original_language_id' => [
            'integer',
            'optional'
            // You can add more rules here if needed
        ],
        'rental_duration' => [
            'required',
            'integer',
            ['between', 1, 10]
        ],
        'rental_rate' => [
            'required',
            ['regex', '/^\d+(\.\d{1,2})?$/']
            // You can add more rules here if needed
        ],
        'length' => [
            'integer',
            ['between', 1, 300]
        ],
        'replacement_cost' => [
            'required',
            ['regex', '/^\d+(\.\d{2})?$/']
            // You can add more rules here if needed
        ],
        'rating' => [
            ['in', ['G', 'PG', 'PG-13', 'R', 'NC-17']]
        ],
        'special_features' => [
            ['in', ['Trailers', 'Commentaries', 'Deleted Scenes', 'Behind the Scenes']]
        ]
    );

    public function __construct()
    {
        $this->films_model = new FilmsModel();
    }

    // Get the list of films
    public function handleGetFilms(Request $request, Response $response, array $uri_args)
    {

        // Apply selected filters on the films collection.
        $filters = $request->getQueryParams();
        $page = $filters['page'];
        $page_size = $filters['page_size'];

        $validation_response = $this->isValidPagingParams($filters);
        if ($validation_response === true) {
            $this->films_model->setPaginationOptions($filters['page'], $filters['page_size']);
        } else {
            //throw new HttpBadRequestException($request, $validation_response);
        }


        // TODO: VALIDATE the paging params.
        if (!Input::isInt(($page))) {
            throw new HttpInvalidInputsException(
                $request,
                "The provided page number was invalid."
            );
        }
        if (!Input::isInt(($page_size))) {
            throw new HttpInvalidInputsException(
                $request,
                "The provided page_size was invalid."
            );
        }

        // $this->films_model->setPaginationOptions($page, $page_size);

        // Get the full list of the films from the DB
        $films = $this->films_model->getAll($filters);
        //$films_json = json_encode($films);
        // $response->getBody()->Write($films_json);
        return $this->prepareOkResponse($response, (array)$films);
    }

    // Gets a film by its id
    public function handleGetFilmById(Request $request, Response $response, array $uri_args)
    {
        $film_id = $uri_args['film_id'];

        //!Check-1) if uri is empty
        if (empty($film_id)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The id is empty!"
            );
        }

        //!Check-2) if uri is invalid
        if ($film_id)

            $film_info = $this->films_model->getFilmById($film_id);
        // prepare the http response
        $film_json = json_encode($film_info);
        $response->getBody()->write($film_json);
        return $response;
    }

    // Creates a film and adds it to the  db
    public function handleCreateFilms(Request $request, Response $response)
    {
        // Step 1) Get the received data from the request body
        $films = $request->getParsedBody();


        // Step 2) Validate the data
        //!Check-1) if body is empty
        if (!isset($films)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The list of films is empty!"
            );
        }

        //!Check-2) Validate the all properties of film
        //use a function from the BaseController called isValidData          
        $validation_response = $this->isValidData($films, $this->validation_rules);
        if ($validation_response === true) {
            foreach ($films as $film) {
                $this->films_model->createFilm($film);
            }
        } else {
            //?else keep track of the encountered errors.            

        }

        // Step 3) Prepare a response
        $response_data = array(
            "code" => HttpCode::STATUS_CREATED,
            "message" => "The list of films has been successfully created"
        );

        return $this->prepareOkResponse($response, $response_data, HttpCode::STATUS_CREATED);
    }

    // Updates a film in the db
    public function handleUpdateFilm(Request $request, Response $response, array $uri_args)
    {
        //Step 1 Get data
        $film_data = $request->getParsedBody();

        //Check 1 If empty
        if (empty($film_data) || !isset($film_data)) {
            throw new HttpBadRequestException(
                $request,
                "Couldn't process the request... The list of films was empty"
            );
        }

        // Step 2 validate data
        $html = var_export($film_data, true);
        //!Validate the request's payload
        foreach ($film_data as $key => $film) {
            //? call isValidData($film,$rules);
            // Step 3 Insert into db
            $validation_response = $this->isValidData($film, $this->validation_rules);
            if ($validation_response === true) {
                $this->films_model->createFilm($film);
            } else {
                //!Add list of errors
            }
        }
        // Step 4) Prepare response
        $response_data = array(
            "code" => HttpCodes::STATUS_CREATED,
            "message" => "The list of films has been successfully created"
        );
        return $this->prepareOkResponse($response, $response_data, HttpCodes::STATUS_CREATED);
    }

    // Deletes a film from the db
    public function handleDeleteFilm(Request $request, Response $response)
    {
        // Step 1) Get the received data from the request body
        $films = $request->getParsedBody();


        // Step 2) Validate the data
        //!Check-1) if body is empty
        if (!isset($films)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The list of films is empty!"
            );
        }

        //!Check-2) Validate the all properties of film
        //use a function from the BaseController called isValidData          
        $validation_response = $this->isValidData($films, $this->validation_rules);
        if ($validation_response === true) {
            foreach ($films as $film) {
                $this->films_model->deleteFilm($film);
            }
        } else {
            //?else keep track of the encountered errors.            

        }

        // Step 3) Prepare a response
        $response_data = array(
            "code" => HttpCode::STATUS_CREATED,
            "message" => "The list of films has been successfully created"
        );

        return $this->prepareOkResponse($response, $response_data, HttpCode::STATUS_CREATED);
        // $response->delete();
    }
}
