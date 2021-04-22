<?php
namespace App\Dao;
class Conexao{
    public static $instance;

    public static function Inst() {
        $srv = "localhost";
        $usr = 'basesgrandejog';
        $db = 'serie_login';
        $pwd = 'ondeumvai@99T';
        $dsn = 'mysql:dbname='.$db.';host='.$srv;     
        if (!isset(self::$instance)) {
            self::$instance = new \PDO($dsn, $usr, $pwd ,array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        return self::$instance;
    }
    
}
?>