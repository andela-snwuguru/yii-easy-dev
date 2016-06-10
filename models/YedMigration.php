<?php

class YedMigration extends YedActiveRecord
{
    public static $_table_name = 'yed_migration';
    public $log = false;

    public static function setColumns(){
        self::$columns = array(
            'user_id'=>array('field'=>YedColumn::integerField()),
            'code'=>array('field'=>YedColumn::charField()),
            'model'=>array('field'=>YedColumn::charField()),
            'params'=>array('field'=>YedColumn::longTextField()),
        );
        return self::$columns;
    }


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return YedLog the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function add($model_name, $params){
        $model = new self;
        $model->code = time();
        $model->user_id = Y::userId();
        $model->model = $model_name;
        $model->params = json_encode($params);
        $model->save(false);
    }

}