<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Vanier\Api\Models\CategoriesModel;
use Vanier\Api\Exceptions\HttpInvalidInputsException;
use Vanier\Api\Helpers\Input;

class CategoriesController extends BaseController
{
    private $category_model = null;

    public function __construct()
    {
        $this->category_model = new CategoriesModel();
    }

    public function handleCategoryFilms(Request $request, Response $response, array $uri_args)
    {
        $filters = $request->getQueryParams();
        $category_id = $uri_args['category_id'];
        //!Check-1) if uri is empty
        if (empty($category_id)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The id is empty!"
            );
        }

        //!Check-2) if uri is invalid
        if (Input::isInt(($category_id))) {
            $category_films = $this->category_model->getFilmsByCategory($category_id, $filters);
            // prepare the http response
            return $this->prepareOkResponse($response, (array)$category_films);
        } else {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The id is invalid!"
            );
        }
    }
}
