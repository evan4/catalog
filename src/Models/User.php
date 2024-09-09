<?php 

namespace Catalog\Models;

use Exception;

class User
{
    public function __construct(
      private $db = new Model('users')
      )
    {

    }

    public function getOne(array $params = null,array $data)
    {
        return $this->db->select($params,$data);
    }

    public function saveUser(array $data)
    {
        return $this->db->insert($data);
    }

    public function updateUser(array $data, int $id)
    {
        return $this->db->update($data, $id);
    }
    
}
