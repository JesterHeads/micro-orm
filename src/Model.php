<?php

namespace td_orm ;

use td_orm\Query;

abstract class Model {

    protected static $table;
    protected static $primary = 'id';
    protected $attrs = [];

    public function __construct(array $attrs = null){
        if(!is_null($attrs)) $this->attrs = $attrs;
    }

    public function __get($attrName){
        if(array_key_exists($attrName,$this->attrs)){
            return $this->attrs[$attrName];
        }
        if(in_array($attrName, get_class_methods($this))){
            return $this->$attrName();
        }     
    }

    public function __set($attrName,$attrVal){
            $this->attrs[$attrName] = $attrVal;
    }

    public function insert(){
        $q = new Query;
        $this->attrs[static::$primary] = $q->table(static::$table)->insert($this->attrs);
    }

    public function delete(){
        if(isset($this->attrs[static::$primary])){
            $q = new Query;
            $q->table(static::$table)->where(static::$primary,'=',$this->id)->delete();
            return "l'objet $this->id a été  supprimé de la base";
        }
    }

    public static function all(){
        $q = new Query;
        $res = $q->table(static::$table)->select()->get();
        $class = static::class;
        $modelList = [];
        foreach($res  as $element){
            $modelList[]= new $class($element);
        }
        return $modelList;
    }

    public static function find($parameter,$col = ['*']){
        $q = new Query;
        $res = null;
        if(gettype($parameter) != "array"){
            $res = $q->table(static::$table)->select($col)->where(static::$primary,"=",$parameter)->get();
        }  else {
            $q = $q->table(static::$table)->select($col);
            if(gettype($parameter[0]) != "array"){
                $res = $q->where($parameter[0],$parameter[1],$parameter[2])->get();
            } else {
                foreach($parameter as $param){
                   $q = $q->where($param[0],$param[1],$param[2]);
                }
                $res = $q->get();
            }
        }
        
        $class = static::class;
        $modelList = [];
        foreach($res  as $element){
            $modelList[]= new $class($element);
        }
        return $modelList;
    }

    public static function first($parameter,$col = ['*']){
        $res = self::find($parameter,$col);
        return $res[0];
    }

    public function belongs_to ($modele,$fk){
        $q = new Query;
        $tableBelong = strtolower($modele);
        $modele = "td_orm\\".$modele;
        $idBelong = static::first($this->attrs[static::$primary])->$fk;
        $res = $q->table($tableBelong)->where("id","=",$idBelong)->get();
        return $res = new $modele($res[0]); 
    }

    public function has_many($modele, $fk){
        $q = new Query;
        $tableBelong = strtolower($modele);
        $class = "td_orm\\".$modele;
        $res = $q->table($tableBelong)->where($fk, '=', $this->id)->get();
        $modelList = [];
        foreach($res  as $element){
            $modelList[]= new $class($element);
        }
        return $modelList;
    }
}

?>