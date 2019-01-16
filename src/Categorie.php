<?php

namespace td_orm ;

use td_orm\Model;

class Categorie extends Model {

    protected static $table = 'categorie';
    protected static $idColumn = 'id';

    public function articles(){
        return $this->has_many('Article', 'id_categ');
    }
}