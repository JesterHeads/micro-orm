<?php

namespace td_orm ;

use td_orm\Model;

class Article extends Model{

    protected static $table = "article";
    protected static $primary = "id";

    public function categorie(){
        return $this->belongs_to('Categorie','id_categ');
    }
    
}

?>