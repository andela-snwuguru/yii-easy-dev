# YII Easy Development (YED)
YED is a project that facilitate rapid web application development using YII Framework. YED gives your YII Model an ORM feel, you only need to define the columns configurations in the model and execute YED migration.

YED also have a base controller that implements dynamic CRUD which works for all models. The base controller have several class properties that you can configure to your taste.

## Features
- Manages migrations
- Generates form from column configurations
- Provides dynamic CRUD views for all your models
- Currently supports <a href="http://yiibooster.clevertech.biz/">Yiibooster</a> modules for UI elements
- Generates relation rules
- Displays many relations in detail view
- Collection of utility functions for YII and PHP
- Provides access, action and migration logs
- Provides Action logs
- Automatic handling of file uploads

## Installation
- Download yii-easy-dev.zip
- Extract content into yed folder in your project module folder
- Add yed to your module configuration settings<br/>
```
'yed'=> array(
    'install' => true,
    )
```
- add below to your import settings<br/>
```
'import'=>array(
        'application.modules.yed.models.*',
        'application.modules.yed.components.*',
        ...
    ),
```
- Visit ``http://domain/yed/install`` on your browser to run yed default models migration
- set install value to false or remove it totally as the default value is false

## YED Configuration
See below for all available configurable parameters of YED with their default values<br/>
```
'yed'=> array(
    'install' => false, # set to true for fresh installation
    'models' => array() # list of registered models extending YedActiveRecord
    'dropTable' => false, # set to true for dropping existing table during migration
    'dropColumn' => false, # set to true for dropping existing column during migration
    'buttonLabelCreate' => 'Create', # the label of submit button during new record if you are using YedFormRender
    'buttonLabelUpdate' => 'Update', # the label of submit button during update if you are using YedFormRender
    'useDefaultColumns' => true, # allow default columns to be applied to model migration
    'default_order' => 'id DESC', # sort order for data provider
    'default_columns' => array(
            'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'date_time' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ), # list of columns to apply automatically to all registered models
    )
```

## Model Columns Configurations
See below for all available configurable parameters of models extending YedActiveRecord class with their default values. Some of the values are sample values. <br/>

```
/**
* Configure model columns setting within this method
*/
public static function setColumns(){
    self::$columns = array(
        'parent_id'=>array(
            'field'=>YedColumn::integerField(array('default'=>0)),
            'owner'=>array('model'=>'ModelName','key'=>'relationAccessKey'),
            'validation'=>array('numerical'=>true),
            'label'=>'Parent Category',
            'form'=>array(
                    'type'=>'dropdown',
                    'section'=>1,
                    'data'=>'Category::listData();',
                    'prompt'=>'Select Category Parent',
                    'widgetOptions'=>array(
                        'htmlOptions'=>array(
                            'prompt'=>'Select Category Parent'
                        )
                    ),
                )
            ),
        'manyRelation'=>YedColumn::many('ModelName','column_name'),
        'oneRelation'=>YedColumn::one('ModelName','column_name'),
    );

    # Don't remove this return statement
    return self::$columns;
}
```
