<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Vanier\Api\Models\ActorsModel;
use Vanier\Api\Exceptions\HttpInvalidInputsException;
use Vanier\Api\Helpers\Input;

class ActorsController extends BaseController
{

    private $actors_model = null;

    private array $rules = array(
        // Rules for validating actors' properties


    );

    public function __construct()
    {
        $this->actors_model = new ActorsModel();
    }

    // Get the list of actors
    public function handleGetActors(Request $request, Response $response)
    {
        $page = 1; // Default page number if 'page' is not provided
        $page_size = 10; // Default page size if 'page_size' is not provided

        // Apply selected filters on the actors collection.
        $filters = $request->getQueryParams();

        if (isset($filters['page'])) {
            $page = $filters['page'];
        }

        if (isset($filters['page_size'])) {
            $page_size = $filters['page_size'];
        }

        // TODO: VALIDATE the paging params.
        if (!Input::isInt(($page))) {
            throw new HttpInvalidInputsException(
                $request,
                "The provided page number was invalid."
            );
        }

        $this->actors_model->setPaginationOptions($page, $page_size);

        $actors = array(
            "code" => HttpCode::STATUS_OK,
            "message" => "The list of actors has been acquired",
            "data" => $this->actors_model->getAll($filters)
        );     
        //$actors_json = json_encode($actors);
        // $response->getBody()->Write($actors_json);
        // return $this->prepareOkResponse($response, (array)$actors);
        return $this->prepareOkResponse($response, $actors, HttpCode::STATUS_OK);
    }


    public function handleActorFilms(Request $request, Response $response, array $uri_args)
    {
        $filters = $request->getQueryParams();
        $actor_id = $uri_args['actor_id'];
        //!Check-1) if uri is empty
        if (empty($actor_id)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The id is empty!"
            );
        }

        //!Check-2) if uri is invalid
        if (Input::isInt(($actor_id))) {
            // $actor_info = $this->actors_model->getActorById($actor_id);
            // get the actor info from the db
            $actor_film_info = $this->actors_model->getFilmsByActor($actor_id, $filters);


            return $this->prepareOkResponse($response, (array)$actor_film_info);
        } else {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The id is invalid!"
            );
        }
    }

    public function handleCreateActors(Request $request, Response $response)
    {
        // Step 1) Get the received data from the request body
        $actors = $request->getParsedBody();


        // Step 2) Validate the data
        //!Check-1) if body is empty
        if (!isset($actors)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The list of films is empty!"
            );
        }

        //!Check-2) Validate the all properties of film
        //use a function from the BaseController called isValidData          
        $validation_response = $this->isValidData($actors, $this->rules);
        if ($validation_response === true) {
            foreach ($actors as $actor) {
                $this->actors_model->createActor($actor);
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
}
