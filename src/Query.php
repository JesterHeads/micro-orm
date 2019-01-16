<?php

namespace td_orm ;

class Query {
    private $sqltable;
    private $fields = '*';
    private $where = null;
    private $args = [];
    private $sql = ''; // requête complète sous forme de string

    public static function table( string $table) {
        $query = new Query(); 
        $query->sqltable= $table; 
        return $query;
    }

    public function select( array $fields = ['*']) {
        $this->fields = implode( ',', $fields); 
        return $this;
    }

    public function where($col, $op, $val , $bool=' AND ') { 
        $this->args[] = $val;
        $this->where[] = ['cond' => "$col $op ?", 'bool' => $bool];
        return $this;
    }
    /**
     * Construit la requête à partir des différents attributs qui ont été passé
     * à la requête avec les autres fonctions
     */
    public function get()  {
        $this->sql  .= 'SELECT '.$this->fields.
                        ' FROM ' .$this->sqltable;
        if(!empty($this->where)){
            $this->sql .= ' WHERE ';
            foreach($this->where as $i => $where){
                $this->sql .= $where['cond'] . ($i == (count($this->where)-1)? '' : $where['bool']);
            }
        }
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(){
        $this->sql = 'DELETE FROM '.$this->sqltable;
        if(!empty($this->where)){
            $this->sql .= ' WHERE ';
            foreach($this->where as $i => $where){
                $this->sql .= $where['cond'] . ($i == (count($this->where)-1)? '' : $where['bool']);
            }
        }
        $pdo = ConnectionFactory::getConnection();
        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
    }

    public function insert(array $data){
        $this->sql = 'INSERT INTO ' . $this->sqltable;
        $columns = '';
        $values = '';
        foreach($data as $key => $value){
            $columns .= "$key";
            if(gettype($value)=="string"){
                $values .= "'$value'";
            } else {
                $values .= "$value";
            }
            
            if(end($data) != $value){
                $values .= ",";
                $columns .= ",";
            }
        }
        $this->sql .= "(".$columns.") VALUES (".$values.")";
        $pdo = ConnectionFactory::getConnection();
        $stmt=$pdo->prepare($this->sql);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

}

?>