<?php

class YedMigration extends YedActiveRecord
{
    public static $_table_name = 'yed_migration';
    public $log = false;
    public static $columns = array(
            'user_id'=>array('field'=>array('type'=>'integerField')),
            'code'=>array('field'=>array('type'=>'charField')),
            'model'=>array('field'=>array('type'=>'charField')),
            'params'=>array('field'=>array('type'=>'longTextField')),
        );


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