<?php

namespace Catalog\Models;

use PDO;
use Exception;

class Model
{
	private $table;
	private $columns = [];
  private $pdo;
 
	public function __construct($table)
	{
		try {
			$this->pdo = new PDO($_ENV['DB_DRIVER'].":host=".$_ENV['DB_HOST'].";charset=utf8;
				dbname=".$_ENV['DB_NAME'],
        $_ENV['DB_USERNAME'], 
        $_ENV['DB_PASSWORD'], 
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
			]);
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
			exit;
		}
		$this->table = $table;
		$this->columnsmeta();
		
	}
	
	public function select(array $params = null, array $data = null)
	{
		
		$params = $params ? implode(', ', array_values($params)) : implode(', ', array_keys($this->columns));
		
		$sql = "SELECT " . $params . " FROM ".$this->table;

		if($data){
			$sql .=  " WHERE ";
			foreach ($data as $key => $value) {
				//last element
				if (!next($data)) {
					$sql .= "$key = ? ";
				}else{
					$sql .= "$key = ? AND ";
				}
			}
		}
		
		$stmt = $this->pdo->prepare($sql);

		if($data){
			$index = 1;
			foreach ($data as $key => $value) {
				//last element
				$stmt->bindValue($index, $value, $this->type($this->columns[$key]) );
				$index+=1;
			}
		}

		$this->pdo->beginTransaction();

		$stmt->execute();
		
		$this->pdo->commit();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function selectAll(array $params = null, array $data = null)
	{
		$params = $params ? implode(', ', array_values($params)) 
			: implode(', ', array_keys($this->columns));

		$sql = "SELECT " . $params . " FROM ".$this->table;

		if($data){
			$sql .=  " WHERE ";
			foreach ($data as $key => $value) {
				//last element
				if (!next($data)) {
					$sql .= "$key = ? ";
				}else{
					$sql .= "$key = ? AND ";
				}
			}
		}
		
		$stmt = $this->pdo->prepare($sql);

		if($data){
			$index = 1;
			foreach ($data as $key => $value) {
				//last element
				$stmt->bindValue($index, $value, $this->type($this->columns[$key]) );
				$index+=1;
			}
		}

		$this->pdo->beginTransaction();

		$stmt->execute();
		
		$this->pdo->commit();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insert(array $data)
	{
		$fields = implode(', ', array_keys($data));
		
		$sql = "INSERT INTO " .$this->table. " (". $fields . ") VALUES (";

		foreach ($data as $value) {
			//last element
			if (!next($data)) {
				$sql .= "?)";
			}else{
				$sql .= "?, ";
			}
		}
		
		$stmt = $this->pdo->prepare($sql);

		try {
			$this->pdo->beginTransaction();

			$index = 1;
			foreach ($data as $key => $value) {
				//last element
				$stmt->bindValue($index, $value, $this->type($this->columns[$key]) );
				$index+=1;
			}

			$res = $stmt->execute();

			$this->pdo->commit();

			return $res;

		} catch (Exception $e) {
			$this->pdo->rollBack();
			return $e->getMessage();
		}
		
	}	

  public function update(array $data, int $id)
  {
    $fields = implode(', ', array_keys($data));
		
		$sql = "UPDATE " .$this->table. " SET ";

		foreach ($data as $key => $value) {
			//last element
			if (!next($data)) {
				$sql .= $key." = ?";
			}else{
				$sql .= $key." = ?, ";
			}
		}

		$sql .= " WHERE id = ?";
    
		$stmt = $this->pdo->prepare($sql);
    
		try {
			$this->pdo->beginTransaction();
      $updateArray = [];
			foreach ($data as $value) {
				array_push($updateArray, $value);
			}

      array_push($updateArray, $id);

			$res = $stmt->execute($updateArray);

			$this->pdo->commit();

			return $res;

		} catch (Exception $e) {
			$this->pdo->rollBack();
			return $e->getMessage();
		}
  }

	private function columnsmeta()
	{
		$q = $this->pdo->query("DESCRIBE $this->table");
		
		$result = $q->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($result as $column){
			$str=strpos($column['Type'], "(");
			$row=substr($column['Type'], 0, $str);

			$this->columns[$column['Field']] = $row;;
		}
	}

	/**
	*	Map data type of argument to a PDO constant
	**/
	private function type($val) 
	{
		switch (gettype($val)) {
			case 'NULL':
				return PDO::PARAM_NULL;
			case 'boolean':
				return PDO::PARAM_BOOL;
			case 'int':
				return PDO::PARAM_INT;
			case 'resource':
				return PDO::PARAM_LOB;
			case 'float':
				return self::PARAM_FLOAT;
			default:
				return PDO::PARAM_STR;
		}
	}

}
