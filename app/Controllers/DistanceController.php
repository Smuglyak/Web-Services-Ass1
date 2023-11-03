<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Vanier\Api\Models\DistanceModel;
use Vanier\Api\Exceptions\HttpInvalidInputsException;
use Vanier\Api\Helpers\Input;
use Vanier\Api\Helpers\Calculator;

class DistanceController extends BaseController
{
    private $distance_model = null;

    public function __construct()
    {
        $this->distance_model = new DistanceModel();
    }

    // Get the list of distance
    public function handleGetDistance(Request $request, Response $response, array $uri_args)
    {
        $filters = $request->getQueryParams();
        $body = $request->getParsedBody();

        // Step 2) Validate the body data
        //!Check-1) if body is empty
        if (!isset($body)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The list of films is empty!"
            );
        }

        var_dump($body);
        
        //Get the latitudes and longitudes from model by the two points provided in body($body['from'] and $body['to'])
        $lengths = $this->distance_model->getLengths($body['from']);
        var_dump($lengths);

        //Get the distance between two points
        // $calculator = new Calculator();
        // $distance = $calculator->calculate(
        //     $from_latitude,
        //     $from_longitude,
        //     $to_latitude,
        //     $to_longitude
        // )->getDistance();

        $distance = array(
            "code" => HttpCode::STATUS_OK,
            "message" => "The distance was calculated"
            // "data" => $this->distance_model->getAll($filters)
        );                  
        return $this->prepareOkResponse($response, $distance);
    }

}
