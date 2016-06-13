<?php

class YedAccessLog extends YedActiveRecord
{
    public static $_table_name = 'yed_access_log';
    public $log = false;

    public static function setColumns(){
        self::$columns = array(
            'user_id'=>array('field'=>array('type'=>'integerField')),
            'action'=>array('field'=>array('type'=>'charField')),
            'controller'=>array('field'=>array('type'=>'charField')),
            'data'=>array('field'=>array('type'=>'longTextField')),
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

    public static function add(){
        $controller_id = Yii::app()->controller->id;
        $actionid = Yii::app()->controller->action->id;
        $model = new self;
        $model->user_id = Y::userId();
        $model->action = $actionid;
        $model->controller = $controller_id;
        $model->data = Y::getRequest();
        $model->save(false);
    }

}