<?php

class Migration extends YedActiveRecord
{
    public static $_table_name = 'yed_migration';

    public static function setColumns(){
        self::$columns = array(
            'code'=>YedColumn::charField()
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