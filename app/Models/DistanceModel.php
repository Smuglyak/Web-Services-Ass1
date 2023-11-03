<?php

namespace Vanier\Api\Models;

use PDO;
use Exception;
use Vanier\Api\Helpers\PaginationHelper;


class DistanceModel extends BaseModel
{
    private string $table_name = "ca_codes";
    public function __construct()
    {
        parent::__construct();
    }

    function getAll(array $filters)
    {
        $sql = "SELECT * FROM $this->table_name";
        $query_values = [];
        if (isset($filters['title'])) {
            $sql .= " AND title LIKE CONCAT( :title, '%')";
            $query_values[':title'] = $filters['title'];
        }
        return $this->paginate($sql, $query_values);
    }

    function getLengths($from){
        $query_values = [':from'=>$from];
        $sql = "SELECT * FROM ca_codes WHERE postal_code = :from";
        $response = $this->fetchSingle($sql, $query_values);
        return $response;
    }
}
