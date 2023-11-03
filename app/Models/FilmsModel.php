<?php

namespace Vanier\Api\Models;

use PDO;
use Exception;
use Vanier\Api\Helpers\PaginationHelper;


class FilmsModel extends BaseModel
{
    private string $table_name = 'film';
    public function __construct()
    {
        parent::__construct();
    }


    // SELECT film.*, category.name AS category, actor.first_name AS actor_first_name, actor.last_name AS actor_last_name FROM 

    // JOIN film_category ON film.film_id = film_category.film_id
    // JOIN category ON category.category_id = film_category.category_id
    // JOIN film_actor ON film_actor.film_id = film.film_id
    // JOIN actor ON film_actor.actor_id = actor.actor_id        
    // WHERE 1

    function getAll(array $filters)
    {
        $sql = "SELECT film.*, 
        category.name AS Cat_Name, 
        language.name AS Lang_Name,
        GROUP_CONCAT(actor.first_name) AS Actor_FName,
        GROUP_CONCAT(actor.last_name) AS Actor_LName
        FROM $this->table_name
        JOIN film_category ON film.film_id = film_category.film_id
        JOIN category ON film_category.category_id = category.category_id
        JOIN language ON film.language_id = language.language_id
        JOIN film_actor ON film.film_id = film_actor.film_id
        JOIN actor ON film_actor.actor_id = actor.actor_id
        GROUP BY film.film_id";
        $query_values = [];
        if (isset($filters['title'])) {
            $sql .= " AND title LIKE CONCAT( :title, '%')";
            $query_values[':title'] = $filters['title'];
        }
        if (isset($filters['descr'])) {
            $sql .= " AND description LIKE CONCAT('%', :descr, '%')";
            $query_values[':descr'] = $filters['descr'];
        }
        if (isset($filters['rating'])) {
            $sql .= " AND rating LIKE CONCAT(:rating)";
            $query_values[':rating'] = $filters['rating'];
        }
        if (isset($filters['special_features'])) {
            $sql .= " AND special_features LIKE CONCAT('%', :special_features, '%')";
            $query_values[':special_features'] = $filters['special_features'];
        }
        if (isset($filters['category'])) {
            $sql .= " AND category.name LIKE CONCAT(:category)";
            $query_values[':category'] = $filters['category'];
        }
        // echo $sql;
        return $this->paginate($sql, $query_values);
    }

    function getFilmById(int $film_id)
    {
        $sql = "SELECT $this->table_name.*, film_category.category_id, film_category.last_update AS film_cat_last_update, language.language_id, language.name AS language, language.last_update AS language_last_update, film_actor.actor_id AS film_actor_actor_id, film_actor.film_id AS film_actor_film_id, film_actor.last_update AS film_actor_last_update, actor.actor_id, actor.first_name AS actor_fn, actor.last_name AS actor_ln, actor.last_update AS actor_last_update FROM $this->table_name 
        JOIN film_category ON film.film_id = film_category.film_id
        JOIN film_actor ON film_actor.film_id = film.film_id
        JOIN actor ON film_actor.actor_id = actor.actor_id
        JOIN language ON film.language_id = language.language_id
        WHERE film.film_id = :film_id";
        return $this->fetchSingle($sql, [':film_id' => $film_id]);
    }

    public function createFilm(array $new_film)
    {
        $this->insert($this->table_name, $new_film);
    }

    public function updateFilm(int $film_id, array $filmData){
        
    }

    public function deleteFilm(int $film_id){
        $query_values = [];
        $sql = "DELETE FROM $this->table_name WHERE film_id=$film_id";
        return $sql;                       
    }
}
