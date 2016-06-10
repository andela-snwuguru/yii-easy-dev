<?php

class YedColumn
{
    static $replace = array(
        'null' =>array('true' => 'NULL','false'=>'NOT NULL' )
    );

    public static function pkField(){
        return 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
    }

    public static function textField($params = array()){

    }

    public static function charField($params = array()){
        return 'Varchar test';
    }

    public static function integerField($params = array()){

    }

    public static function getRules(){
        return array();
    }

    public static function getRelations(){
        return array();
    }

    public static function getLabels(){
        return array();
    }

    public static function getDataPovider(){
        return array();
    }
}

?>