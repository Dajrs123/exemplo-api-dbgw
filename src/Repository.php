<?php
namespace DatabaseGateway;

class Repository {
    protected $tableName;

    public function select(array $criteria = []) {
        $sql = 'SELECT * FROM ' . $this->tableName;
        $parameters = [];
        foreach($criteria as $k => $v) {
            $sql .= (count($parameters) == 0) ? ' WHERE ' : ' AND ';
            $sql .= $k . ' = :' .$k;
            $parameters[':'.$k] = $v;
        }
        $command = DbConnection::get()->prepare($sql);
        if($command->execute($parameters)) {
            http_response_code(200);
            return $command->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            http_response_code(400);
            return $command->errorInfo();
        }
    }

    public function insert(array $values) {
        $parameters = [];
        foreach($values as $k => $v)
            $parameters[':'.$k] = $v;
        $sql = 'INSERT INTO ' . $this->tableName . '(' . implode(array_keys($values)) . ') VALUES (' . implode(array_keys($parameters)) . ')';
        $command = DbConnection::get()->prepare($sql);
        if($command->execute($parameters)) {
            http_response_code(200);
            return ['id' => DbConnection::get()->lastInsertId()];
        } else {
            http_response_code(400);
            return $command->errorInfo();
        }
    }

    public function update(array $values, array $criteria) {
        $sql = 'UPDATE ' . $this->tableName;
        $parameters = [];
        foreach($values as $k => $v) {
            $sql .= (count($parameters) == 0) ? ' SET ' : ', ';
            $sql .= $k . ' = :' .$k;
            $parameters[':'.$k] = $v;
        }
        if(count($criteria) > 0)
            $sql .= ' WHERE ';
        foreach($criteria as $k => $v) {
            if(substr($sql, -7) != ' WHERE ')
                $sql .= ' AND ';
            $sql .= $k . ' = :' .$k;
            $parameters[':'.$k] = $v;
        }
        $command = DbConnection::get()->prepare($sql);
        if($command->execute($parameters)){
            http_response_code(200);
            return $criteria;
        } else {
            http_response_code(400);
            return $command->errorInfo();
        }
    }

    public function delete(array $criteria) {
        $sql = 'DELETE FROM ' . $this->tableName;
        $parameters = [];
        foreach($criteria as $k => $v) {
            $sql .= (count($parameters) == 0) ? ' WHERE ' : ' AND ';
            $sql .= $k . ' = :' .$k;
            $parameters[':'.$k] = $v;
        }
        $command = DbConnection::get()->prepare($sql);
        if($command->execute($parameters)) {
            http_response_code(200);
            return $criteria;
        } else {
            http_response_code(400);
            return $command->errorInfo();
        }
    }

    public function __construct(string $tableName) {
        $this->tableName = $tableName;
    }
}