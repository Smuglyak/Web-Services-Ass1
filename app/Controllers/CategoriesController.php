<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

    public function handleCategoryFilms(Request $request, Response $response, array $uri_args){
        $filters = $request->getQueryParams();
        $category_id = $uri_args['category_id'];
        $category_films = $this->category_model->getFilmsByCategory($category_id, $filters);        
        // prepare the http response
        return $this->prepareOkResponse($response, (array)$category_films);
    }

}
