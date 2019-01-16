<?php

namespace td_orm ;

use td_orm\Model;

class Article extends Model{

    protected static $table = "article";
    protected static $primary = "id";
    
}

?>