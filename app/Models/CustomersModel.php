<?php

namespace Vanier\Api\Models;

use PDO;
use Exception;
use Vanier\Api\Helpers\PaginationHelper;

class CustomersModel extends BaseModel
{
    private string $table_name = 'customer';
    public function __construct()
    {
        parent::__construct();
    }

    function getAll(array $filters)
    {
        $sql = "SELECT $this->table_name.*, address.address, address.address_id AS address_id, address.district, address.postal_code, address.phone, address.last_update AS address_last_update, city.city_id, city.city, city.last_update AS city_last_update, country.country_id, country.country, country.last_update AS country_last_update FROM $this->table_name 
        JOIN address ON address.address_id = $this->table_name.address_id
        JOIN city ON city.city_id = address.city_id
        JOIN country ON country.country_id = city.country_id
        WHERE 1";
        $query_values = [];
        if (isset($filters['first_name'])) {
            $sql .= " AND first_name LIKE CONCAT(:first_name)";
            $query_values[':first_name'] = $filters['first_name'];
        }
        if (isset($filters['last_name'])) {
            $sql .= " AND last_name LIKE CONCAT(:last_name)";
            $query_values[':last_name'] = $filters['last_name'];
        }
        if (isset($filters['city'])) {
            $sql .= " AND city LIKE CONCAT(:city)";
            $query_values[':city'] = $filters['city'];
        }
        if (isset($filters['country'])) {
            $sql .= " AND country LIKE CONCAT(:country)";
            $query_values[':country'] = $filters['country'];
        }
        return $this->paginate($sql, $query_values);
    }

    function getFilmsByCustomer(int $customer_id, array $filters)
    {
        $query_values = [];

        $sql = "SELECT film.*, rental.rental_date, category.name AS category FROM $this->table_name
        JOIN rental ON rental.customer_id = customer.customer_id 
        JOIN inventory ON inventory.inventory_id = rental.inventory_id
        JOIN film ON film.film_id = inventory.film_id
        JOIN film_category ON film.film_id = film_category.film_id
        JOIN category ON category.category_id = film_category.category_id
        WHERE 1 
        AND customer.customer_id = $customer_id";

        if (isset($filters['rating'])) {
            $sql .= " AND rating LIKE CONCAT(:rating)";
            $query_values[':rating'] = $filters['rating'];
        }
        if (isset($filters['special_features'])) {
            $sql .= " AND special_features LIKE CONCAT('%', :special_features, '%')";
            $query_values[':special_features'] = $filters['special_features'];
        }
        if (isset($filters['from'], $filters['to'])) {
            $sql .= " AND (rental_date BETWEEN :from AND :to)";
            $query_values[':from'] = $filters['from'];
            $query_values[':to'] = $filters['to'];
        }
        if (isset($filters['category'])) {
            $sql .= " AND category.name LIKE CONCAT(:category)";
            $query_values[':category'] = $filters['category'];
        }
        return $this->paginate($sql, $query_values);
    }

    public function createCustomer(array $new_customer)
    {
        $this->insert($this->table_name, $new_customer);
    }

    public function updateCustomer(){

    }

    public function deleteCustomerById($customer_id)
    {
        $query_values = [];
        $sql = "DELETE FROM $this->table_name WHERE customer_id=$customer_id";
        return $sql;
    }
}
