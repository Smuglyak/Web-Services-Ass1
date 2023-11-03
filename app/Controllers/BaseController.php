<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;

class BaseController
{
    protected function prepareOkResponse(Response $response, array $data, int $status_code = 200)
    {
        // var_dump($data);
        
        $json_data = json_encode($data);
        // $json_data .= '"status_code: 200"';
        //-- Write JSON data into the response's body.        
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    protected function prepareCreatedResponse(Response $response, array $data, int $status_code = 201)
    {
        // var_dump($data);
        $json_data = json_encode($data);
        //-- Write JSON data into the response's body.        
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    protected function isValidData(array $data, array $rules): mixed
    {
        //TODO Use the Valitron library to match the rules against the data
        $validator = new Validator($data, [], 'en');

        $validator->mapFieldsRules($rules);
        if($validator->validate()){
            return true;
        }
        return $validator->errorsToJson();
    }

    protected function isValidPagingParams(array $paging_params)
    {
        $rules = array(
            'page' => [
                'required',
                'numeric',
                ['min', 1]
            ],
            'page_size' => [
                'required',
                'integer',
                ['min', 5],
                ['max', 50]
            ]
        );
        return $this->isValidData($paging_params, $rules);
    }


}
