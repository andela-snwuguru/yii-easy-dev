<?php

/**
* YedActiveRecord is the base model class to be extended by all models
* @property array $columns model columns configuration.
* @property string $_table_name the name of table to be created in database
* @property boolean $log if enabled, all create, update and delete activity of the model will be logged
* @property boolean $applyDefaultColumns if false, defaultColumns configured in YedModule settings will not be applied to the child model
* @property array $additional_label configure additional label parameters such as relationship chain
*/
abstract class YedActiveRecord extends CActiveRecord
{
    public static $columns = array();
    public static $_table_name = '';
    public $log = true;
    public static $applyDefaultColumns = true;
    public $additional_label = array();

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return static::$_table_name;
    }


     /**
     * @return array validation rules for model attributes.
     */
    public function rules(){
        $required = array();
        $numerical = array();
        $rules = array();
        $safe = array();
        $fields = YedOperation::getColumns(get_class($this));
        $columns = static::$columns;
        foreach ($fields as $field=>$value) {
            $field_rule = array();
            $validation = isset($columns[$field]['validation']) ? $columns[$field]['validation'] : '';

            if(isset($value['field']['null']) && $value['field']['null'])
                array_push($required, $field);

            if(isset($value['field']['type']) && $value['field']['type'] == 'integerField')
                array_push($numerical, $field);


            if(isset($value['field']['max_length'])){
                $field_rule = array($field, 'length');
                $field_rule['max'] = $validation['length']['max'];
                array_push($rules,$field_rule);
            }

            if(isset($value['field']['unique']) && $value['field']['unique'] == true){
                array_push($rules,array($field, 'unique'));
            }

            if (empty($validation)){
                array_push($safe, $field);
                continue;
            }

            if(isset($validation['required']) && $validation['required'])
                array_push($required, $field);

            if(isset($validation['numerical']) && $validation['numerical'])
                array_push($numerical, $field);

            if(isset($validation['length'])){
                $field_rule = array($field, 'length');
                if(isset($validation['length']['max'])){
                    $field_rule['max'] = $validation['length']['max'];
                }

                if(isset($validation['length']['min'])){
                    $field_rule['min'] = $validation['length']['min'];
                }

                if(isset($validation['length']['message'])){
                    $field_rule['message'] = $validation['length']['message'];
                }
                array_push($rules,$field_rule);
            }

            if(isset($validation['file'])){
                $field_rule = array(
                    $field,
                    'file',
                    'types'=>isset($validation['file']['type']) ? $validation['file']['type'] : 'jpg, jpeg, png, gif',
                    'allowEmpty'=>isset($validation['file']['empty']) ? $validation['file']['empty'] : true
                    );
                array_push($rules,$field_rule);
            }

            if(isset($validation['pattern'])){
                $field_rule = array($field,'match');
                if(isset($validation['pattern']['match'])){
                    $field_rule['pattern'] = $validation['pattern']['match'];
                }
                if(isset($validation['pattern']['message'])){
                    $field_rule['message'] = $validation['pattern']['message'];
                }
                array_push($rules,$field_rule);
            }

            if(isset($validation['custom'])){
                foreach ($validation['custom'] as $method) {
                    $field_rule = array($field, $method);
                    array_push($rules, $field_rule);
                }
            }

        }

        array_push($rules,
            array(implode(',',$required), 'required'));
        array_push($rules,
            array(implode(',',$numerical), 'numerical', 'integerOnly'=>true));
        array_push($rules,
            array(implode(',',$safe), 'safe'));

        return $rules;
    }


    /**
     * @return array relational rules.
     */
    public function relations(){
        $relations = array();
        $fields = static::$columns;
        if(Y::getModule()->useDefaultColumns){
            $fields = array_merge(Y::getModule()->default_columns, $fields);
        }
        if($fields){
            foreach($fields as $field=>$value) {
                if(isset($value['owner'])){
                    if(!isset($value['owner']['model']))
                        Y::exception($field.' owner model has not been defined');

                    $key = isset($value['owner']['key']) ? $value['owner']['key'] : strtolower($value['owner']['model']);
                    $relations[$key] = YedColumn::owner($value['owner']['model'], $field);
                }
            }
        }

        if(isset($fields['many'])){
            foreach($fields['many'] as $key=>$value) {
                $relations[$key] = YedColumn::many($value[0], $value[1]);
            }
        }

        if(isset($fields['one'])){
            foreach($fields['one'] as $key=>$value) {
                $relations[$key] = YedColumn::one($value[0], $value[1]);
            }
        }

        return $relations;
    }

     /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $labels = array();
        $columns = YedOperation::getColumns(get_class($this));
        foreach ($columns as $column=>$value){
            $labels[$column] = isset(static::$columns[$column]['label']) ? static::$columns[$column]['label'] : ucwords(str_ireplace('_', ' ', $column));
        }

        return array_merge($labels, $this->additional_label);
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */

    public function search()
    {
        $fields = YedOperation::getColumns(get_class($this));
        $criteria = new CDbCriteria;
        foreach ($fields as $field=>$value){
            $like =  isset(static::$columns[$field]['like']) && !static::$columns[$field]['like'] ? false : true;
            $criteria->compare($field, $this->$field, $like);
        }

        $criteria->order = Y::getModule()->default_order;
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function behaviors() {
        return array(
            'ActionLoggerBehavior' => array(
                'class' => 'YedLogger',
            ),
        );
    }
}