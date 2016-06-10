<?php
abstract class YedActiveRecord extends CActiveRecord
{
    public static $columns = array();
    public static $_table_name = '';
    public $log = true;
    public static $applyDefaultColumns = true;

    public function init(){
        parent::init();
        self::setColumns();
    }


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return static::$_table_name;
    }

    /**
    * Override this function to define model columns
    */
    public static function setColumns(){
        self::$columns = array();
    }


     /**
     * @return array validation rules for model attributes.
     */
    public function rules(){
        return YedColumn::getRules(self);
    }


    /**
     * @return array relational rules.
     */
    public function relations(){
        return YedColumn::getRelations(self);
    }

     /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return YedColumn::getLabels(self);
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */

    public function search()
    {
        return YedColumn::getDataProvider(self);
    }


    public function beforeSave() {
        if ($this->isNewRecord) {
            if(isset($this->date_time) && !$this->date_time)
                $this->date_time = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

    public function behaviors() {
        return array(
            'ActionLoggerBehavior' => array(
                'class' => 'YedLogger',
            ),
        );
    }
}