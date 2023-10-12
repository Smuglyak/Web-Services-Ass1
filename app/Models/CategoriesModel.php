<?php

namespace Vanier\Api\Models;

use PDO;
use Exception;
use Vanier\Api\Helpers\PaginationHelper;

class CategoriesModel extends BaseModel
{

    private string $table_name = 'category';
    public function __construct()
    {
        parent::__construct();
    }

    public function getFilmsByCategory(int $category_id, array $filters)
    {
        $query_values = [];

        $sql = "SELECT film.*, actor.first_name AS actor_first_name, actor.last_name AS actor_last_name, category.name AS category FROM $this->table_name
        JOIN film_category ON film_category.category_id = category.category_id
        JOIN film ON film.film_id = film_category.film_id
        JOIN film_actor ON film_actor.film_id = film.film_id
        JOIN actor ON actor.actor_id = film_actor.actor_id        
        WHERE 1 
        AND $this->table_name.category_id = $category_id";

        if (isset($filters['rating'])) {
            $sql .= " AND rating LIKE CONCAT(:rating)";
            $query_values[':rating'] = $filters['rating'];
        }
        if(isset($filters['length'])){
            $sql .= " AND film.length <= :length";
            $query_values[':length'] = $filters['length'];
        }   
        return $this->paginate($sql, $query_values);
    }
}
