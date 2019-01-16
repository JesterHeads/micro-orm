<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 18/08/2018
 * Time: 15:16
 */

namespace td_orm;

use PDO;

class ConnectionFactory
{

    static $pdo;

    public static function makeConnection($conf)
    {   
        if(self::$pdo == null){
            $conf = parse_ini_file($conf);
            $dsn = 'mysql:host='.$conf['host'].';dbname='.$conf['db_name'];
                    self::$pdo = new PDO($dsn,$conf['username'],$conf['password'], 
                        array(PDO::ATTR_PERSISTENT => true,
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_EMULATE_PREPARES=> false,
                            PDO::ATTR_STRINGIFY_FETCHES => false));
        }
        return self::$pdo;
    }

    public static function getConnection(){
        if(self::$pdo != null){
            return self::$pdo;
        }
    }
}