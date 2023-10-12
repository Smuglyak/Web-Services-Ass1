<?php

namespace Vanier\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\CustomersModel;
use Vanier\Api\Exceptions\HttpInvalidInputsException;
use Vanier\Api\Helpers\Input;

class CustomersController extends BaseController
{
    private $customers_model = null;

    public function __construct()
    {
        $this->customers_model = new CustomersModel();
    }

    // Get the list of customers
    public function handleGetCustomers(Request $request, Response $response, array $uri_args)
    {
        $page = 1; // Default page number if 'page' is not provided
        $page_size = 10; // Default page size if 'page_size' is not provided

        // Apply selected filters on the customers collection.
        $filters = $request->getQueryParams();

        // TODO: VALIDATE the paging params.
        if (!Input::isInt(($page))) {
            throw new HttpInvalidInputsException(
                $request,
                "The provided page number was invalid."
            );
        }

        $this->customers_model->setPaginationOptions($page, $page_size);

        // Get the full list of the customers from the DB
        $customers = $this->customers_model->getAll($filters);
        //$customers = json_encode($customers);
        // $response->getBody()->Write($customers);
        return $this->prepareOkResponse($response, (array)$customers);
    }

    public function handleGetFilmsByCustomer(Request $request, Response $response, array $uri_args)
    {
        $filters = $request->getQueryParams();
        $customer_id = $uri_args['customer_id'];
        $customer_films = $this->customers_model->getFilmsByCustomer($customer_id, $filters);        
        // prepare the http response
        return $this->prepareOkResponse($response, (array)$customer_films);
    }

    // Creates a customer and adds it to the  db
    public function handleUpdateCustomers(Request $request, Response $response, array $uri_args)
    {
        // Step 1) Get the received data from the request body
        $customers = $request->getParsedBody();

        // Step 2) Validate data;
        foreach ($customers as $key => $customer) {
            // Step 3) Insert the customer into the db
            $this->customers_model->updateCustomer();
        }
        // Step 4) Prepare a response
        $response_data = array(
            "code" => HttpCode::STATUS_CREATED,
            "message" => "The list of customers has been successfully created"
        );
        return $this->prepareOkResponse($response, $response_data, HttpCode::STATUS_CREATED);
    }

    // Deletes a customer from the db
    public function deleteCustomerById(Request $request, Response $response, array $uri_args)
    {
        $customer_id = $uri_args['customer_id'];
        // get the customer info from the db
         // Step 2) Validate the data
        //!Check-1) if body is empty
        if (!isset($customer_id)) {
            throw new HttpBadRequestException(
                $request,
                "Could not process the request... The list of films is empty!"
            );
        }
        $customer_info = $this->customers_model->deleteCustomerById($customer_id);
        // prepare the http response        
        // Step 4) Prepare a response
        $response_data = array(
            "code" => HttpCode::STATUS_ACCEPTED,
            "message" => "The customer has been deleted"
        );
        return $this->prepareOkResponse($response, $response_data, HttpCode::STATUS_ACCEPTED);
    }
}
