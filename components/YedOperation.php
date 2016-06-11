<?php

class YedOperation {

    /*
    * Returns the currently used database name
    */
    static function getDbName(){
        $connectionString = Yii::app()->db->connectionString;
        if (preg_match('/dbname=([^;]*)/', $connectionString, $match)) {
            return $match[1];
        }
       return null;
    }

    /**
    * Checks if a column name exists in a table
    * @param String $table_name the name of the table to check
    * @param String $column_name the column name to check
    * @return Boolean
    */
    static function columnExists($table_name, $column_name){
        $command = Yii::app()->db->createCommand();
        $table = Yii::app()->db->schema->getTable($table_name);
        if(!$table)
            return false;

        return isset($table->columns[$column_name]);
    }

    /**
    * Checks if a table name exists in a database
    * @param String $table_name the name of the table to check
    * @return Boolean
    */
    static function tableExists($table_name){
        return Yii::app()->db->schema->getTable($table_name, true) !== null;
    }

    /**
    * Drop an existing table in a database
    * @param String $table_name the name of the table to drop
    * @return Boolean
    */
    static function dropTable($table_name){
        $command = Yii::app()->db->createCommand();
        if(!self::tableExists($table_name))
            return false;

        return $command->dropTable($table_name) === 0;
    }

    /**
    * Returns an array of key value pair of defined columns from a model
    * @param String $model_name the class name of the model
    * @return Array
    */
    static function getColumns($model_name){
        $columns = $model_name::setColumns();
        $parse_columns = array();
        foreach ($columns as $key => $value) {
            if(!isset($value['field']))
                continue;
            $parse_columns[$key] = $value['field'];
        }

        if(Y::getModule()->useDefaultColumns){
            $parse_columns = array_merge($parse_columns, Y::getModule()->default_columns);
        }
        return $parse_columns;
    }

  /**
    * Returns an array of key value pair of defined columns from a model
    * @param String $model_name the class name of the model
    * @return Array
    */
    static function getFormFields($model_name){
        $columns = $model_name::setColumns();
        $parse_columns = array();
        foreach ($columns as $key => $value) {
            if(!isset($value['form']))
                continue;
            $parse_columns[$key] = $value['form'];
        }

        return $parse_columns;
    }

  /**
    * Returns an array of key value pair of defined columns from a model
    * @param String $model_name the class name of the model
    * @return Array
    */
    static function getFormFieldsBySection($model_name){
        $columns = self::getFormFields($model_name);
        $parse_columns = array();
        foreach ($columns as $key => $value) {
            $index = isset($value['section']) ? $value['section'] : 1 ;
            $parse_columns[$index][$key] = $columns[$key];
        }

        return $parse_columns;
    }

    /**
    * Creates table in database with table name specified in the model
    * This method will also create the columns as defined in the model
    * @param String $model_name the class name of the model
    * @param boolean $migration if true, migration data will be logged
    */
    static function createTable($model_name, $migration = false){
        $columns = self::getColumns($model_name);
        $command = Yii::app()->db->createCommand();

        if (!self::tableExists($model_name::$_table_name)) {
            $command->createTable($model_name::$_table_name, $columns);
            Y::info('Table:'.$model_name::$_table_name.' Successfully Created');
            if($migration){
                YedMigration::add($model_name, $columns);
            }
        }else{
            if(Y::getModule()->dropTable){
                self::dropTable($model_name::$_table_name);
                self::createTable($model_name);
            }else{
                Y::info('Table:'.$model_name::$_table_name.' Already Exists');
                self::proccessColumns($model_name, $migration);
            }
        }
    }

    /**
    * Creates the columns as defined in the model
    * @param String $model_name the class name of the model
    * @param boolean $migration if true, migration data will be logged
    */
    public static function proccessColumns($model_name, $migration = false) {
        $columns = self::getColumns($model_name);
        if($migration){
            YedMigration::add($model_name, $columns);
        }

        foreach ($columns as $column_name => $column_params) {
            self::addColumn($model_name, $column_name, $column_params);
        }
    }

    /**
    * Removes column from the database if it has been removed from the current column settings
    * @param String $model_name the class name of the model
    * @param String $column_params the current column parameters
    * @param String $migrate_params last migrated column parameters
    */
    public static function dropRemovedColumns($model_name, $column_params, $migrate_params){
        foreach ($migrate_params as $column_name => $value) {
            if(!isset($column_params[$column_name])){
                if(self::dropColumn($model_name, $column_name)){
                    Y::info($column_name.' has been dropped');
                }
            }
        }
    }

    /**
    * Creates or updates a column
    * @param String $model_name the class name of the model
    * @param String $column_name the name of the column
    * @param String $column_params a valid sql options for a column
    * @return Boolean
    */
    public static function addColumn($model_name, $column_name, $column_params) {
        $command = Yii::app()->db->createCommand();
        if(!self::columnExists($model_name::$_table_name, $column_name)){
            try {
                $command->addColumn($model_name::$_table_name, $column_name, $column_params);
                Y::info($column_name.' Added to '. $model_name::$_table_name);
                return true;
            } catch (Exception $e) {
                Y::err('Unable to add '.$column_name.' to '. $model_name::$_table_name);
                return false;
            }

        }else{
            if(Y::getModule()->dropColumn){
                self::dropColumn($model_name, $column_name);
                return self::addColumn($model_name, $column_name, $column_params);
            }else{
                Y::info('Column:'.$column_name.' Already Exists');
                return self::updateColumn($model_name, $column_name, $column_params);
            }
        }
        return false;
    }

    /**
    * Updates a column
    * @param String $model_name the class name of the model
    * @param String $column_name the name of the column
    * @param String $column_params a valid sql options for a column
    * @return Boolean
    */
    public static function updateColumn($model_name, $column_name, $column_params) {
        if(strpos($column_params, 'PRIMARY'))
            return true;
        try{
            $command = Yii::app()->db->createCommand();
            if(!self::columnExists($model_name::$_table_name, $column_name)){
                return false;
            }
            $command->alterColumn($model_name::$_table_name, $column_name, $column_params);
            Y::info($column_name.' updated');
            return true;
        }catch (Exception $e) {
            Y::err('Unable to update '.$column_name);
            return false;
        }
    }

    /**
    * Drops a column
    * @param String $model_name the class name of the model
    * @param String $column_name the name of the column
    * @return Boolean
    */
    public static function dropColumn($model_name, $column_name){
        $command = Yii::app()->db->createCommand();
        try{
            if(!self::columnExists($model_name::$_table_name, $column_name)){
                return false;
            }
           $command->dropColumn($model_name::$_table_name, $column_name);
           return true;
        }catch (Exception $e) {
            return false;
        }
        return false;
    }

}

?>