<?php

class YedColumn
{

    /**
    * Returns valid sql parameters that are common for every type of data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function generalParams($params){
        $null = isset($params['null']) && $params['null'] == true ? 'NULL' : 'NOT NULL';
        $unique = isset($params['unique']) && $params['unique'] == true ? 'UNIQUE INDEX' : '';
        $comment = isset($params['comment']) ? 'COMMENT "'.$params['comment'].'"' : '';
        $default = isset($params['default']) ? 'DEFAULT "'.$params['default'].'"' : '';
        return trim($null.' '.$unique.' '.$comment.' '.$default);
    }

    /**
    * Returns valid sql parameters for text data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function textField($params = array()){
        $max_length = isset($params['max_length']) ? $params['max_length'] : 2000000;
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';
        $return = 'TEXT('.$max_length.') '.self::generalParams($params).' '. $primary_key;
        return trim($return);
    }

    /**
    * Returns valid sql parameters for long text data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function longTextField($params = array()){
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';
        $return = 'LONGTEXT '.self::generalParams($params).' '. $primary_key;
        return trim($return);
    }

    /**
    * Returns valid sql parameters for varchar data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function charField($params = array()){
        $max_length = isset($params['max_length']) ? $params['max_length'] : 255;
        if($max_length > 255){
            $max_length = 255;
        }
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';
        $return = 'VARCHAR('.$max_length.') '.self::generalParams($params).' '. $primary_key;

        return trim($return);
    }

    /**
    * Returns valid sql parameters for integer data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function integerField($params = array()){
        $increment = isset($params['increment']) && $params['increment'] == true ? 'AUTO_INCREMENT' : '';
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';
        $return = 'INT '.self::generalParams($params).' '. $primary_key.' '. $increment;

        return trim($return);
    }

    /**
    * Returns valid sql parameters for float data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function floatField($params = array()){
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';
        $return = 'FLOAT '.self::generalParams($params).' '. $primary_key;
        return trim($return);
    }

    /**
    * Returns valid sql parameters for datetime data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function datetimeField($params = array()){
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';

        $return = 'DATETIME '.self::generalParams($params).' '. $primary_key;
        return trim($return);
    }

    /**
    * Returns valid sql parameters for timestamp data type
    * @param Array $params the parameters specified in the model column configuration
    * @return String
    */
    public static function timestampField($params = array()){
        $primary_key = isset($params['primary_key']) && $params['primary_key'] == true ? 'PRIMARY KEY' : '';

        $return = 'TIMESTAMP '.self::generalParams($params).' '. $primary_key;
        return trim($return);
    }

    /**
    * Returns HAS_MANY relation configuration
    * @param string $model the model name that has the foreign key
    * @param string $column_name the column name in provided model
    * @return array
    */
    public static function many($model, $column_name){
        return array($model::HAS_MANY, $model, $column_name);
    }

    /**
    * Returns HAS_ONE relation configuration
    * @param string $model the model name that has the foreign key
    * @param string $column_name the column name in provided model
    * @return array
    */
    public static function one($model, $column_name){
        return array($model::HAS_ONE, $model, $column_name);
    }

    /**
    * Returns BELONGS_TO relation configuration
    * @param string $model the model name that has the primary key
    * @param string $column_name the column name in provided model
    * @return array
    */
    public static function owner($model, $column_name){
        return array($model::BELONGS_TO, $model, $column_name);
    }
}

?>