<?php

class Migration extends YedActiveRecord
{
    public static $_table_name = 'yed_migration';

    public static function setColumns(){
        self::$columns = array(
            'user_id'=>array('field'=>YedColumn::integerField()),
            'code'=>array('field'=>YedColumn::timestampField()),
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

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return self::_table_name;
    }

}