<?php

class YedAccessLog extends YedActiveRecord
{
    public static $_table_name = 'yed_access_log';
    public $log = false;

    public static function setColumns(){
        self::$columns = array(
            'user_id'=>array('field'=>YedColumn::integerField()),
            'action'=>array('field'=>YedColumn::charField()),
            'controller'=>array('field'=>YedColumn::charField()),
            'data'=>array('field'=>YedColumn::longTextField()),
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