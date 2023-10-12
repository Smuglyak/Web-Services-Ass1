<?php

namespace Vanier\Api\Models;

use PDO;
use Exception;
use Vanier\Api\Helpers\PaginationHelper;

class ActorsModel extends BaseModel
{
    private string $table_name = 'actor';
    public function __construct()
    {
        parent::__construct();
    }

    function getAll(array $filters)
    {
        $sql = "SELECT * FROM $this->table_name WHERE 1";
        $query_values = [];
        if (isset($filters['first_name'])) {
            $sql .= " AND first_name LIKE CONCAT( '%', :first_name, '%')";
            $query_values[':first_name'] = $filters['first_name'];
        }
        if (isset($filters['last_name'])) {
            $sql .= " AND last_name LIKE CONCAT( '%', :last_name, '%')";
            $query_values[':last_name'] = $filters['last_name'];
        }
        return $this->paginate($sql, $query_values);
    }

    function getActorById(int $actor_id)
    {
        $sql = "SELECT * FROM $this->table_name 
        AND actor_id LIKE CONCAT(:actor_id)";
        return $this->fetchSingle($sql, [':actor_id' => $actor_id]);
    }

    function getFilmsByActor(int $actor_id, $filters)
    {
        $query_values = [];
        $sql = "SELECT film.*, actor.* FROM actor
        JOIN film_actor ON film_actor.actor_id = actor.actor_id
        JOIN film ON film_actor.film_id = film.film_id
        JOIN film_category ON film.film_id = film_category.film_id
        JOIN category ON category.category_id = film_category.category_id
        WHERE actor.actor_id = $actor_id
        ";
        if(isset($filters['rating'])){
            $sql .= " AND rating LIKE CONCAT(:rating)";
            $query_values[':rating'] = $filters['rating'];
        }
        if(isset($filters['category'])){
            $sql .= " AND category.name LIKE CONCAT(:category)";
            $query_values[':category'] = $filters['category'];
        }
        if(isset($filters['length'])){
            $sql .= " AND film.length <= :length";
            $query_values[':length'] = $filters['length'];
        }
        
        return $this->paginate($sql, $query_values);
    }

    public function createActor(array $new_actor){
        $this->insert($this->table_name, $new_actor);
    }
}
