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

    public static function getRules(){
        return array();
    }

    public static function getRelations(){
        return array();
    }

    public static function getLabels(){
        return array();
    }

    public static function getDataPovider($model){
        // $fields = $model
        // $criteria = new CDbCriteria;
        // if(self::$fields)
        //     foreach (self::$fields as $field){
        //         $varname = $field->varname;
        //         $criteria->compare($varname,$this->$varname,true);
        //     }

        // $criteria->order = 'id DESC';

        // return new CActiveDataProvider($this, array(
        //     'criteria'=>$criteria,
        // ));
    }
}

?>