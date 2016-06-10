<?php

class YedActionLog extends YedActiveRecord
{
    public static $_table_name = 'yed_action_log';

    public static function setColumns(){
        self::$columns = array(
            'user_id'=>array('field'=>YedColumn::integerField()),
            'model_name'=>array('field'=>YedColumn::charField()),
            'model_id'=>array('field'=>YedColumn::integerField()),
            'action'=>array('field'=>YedColumn::charField()),
            'model_data'=>array('field'=>YedColumn::longTextField()),
            'system_info'=>array('field'=>YedColumn::longTextField()),
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

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return self::_table_name;
    }

}