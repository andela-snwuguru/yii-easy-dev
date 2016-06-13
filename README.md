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
    'default_columns' => array( # list of columns to apply automatically to all registered models
            'id' => array(
                    'field'=>array('type'=>'integerField','primary_key'=>true, 'increment'=>true)
                ),
            'date_time' => array(
                    'field'=>array('type'=>'timestampField', 'default'=>'CURRENT_TIMESTAMP')
                ),
        ),
    )
```

## Model Columns Configurations
See below for all available configurable parameters of models extending YedActiveRecord class with their default values or sample values. <br/>

### Sample Columns Configuration
```
/**
* Configure model columns setting within this method
*/
public static function setColumns(){
    self::$columns = array(
        'column_name'=>array(
            'field'=>array('type'=>'textField'),
            'owner'=>array('model'=>'ModelName','key'=>'relationAccessKey'),
            'validation'=>array('numerical'=>true,'custom'=>array('unique')),
            'label'=>'Column Custom Label',
            'like'=>true,
            'form'=>array(
                    'type'=>'dropdown',
                    'section'=>1,
                    'data'=>'ModelName::listData()',
                    'widgetOptions'=>array(
                        'htmlOptions'=>array(
                            'prompt'=>'Select...'
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

### Field Configuration
Field holds the configuration for the database column<br/>

```
'field'=>array(
        'type'=>'textField', # Column type, see Field type parameters section
        'null'=>false, # allow blank insert
        'unique'=>false, # allow duplicate record
        'comment'=>'', # database field comment
        'default'=>'', # column default value
        'max_length'=>255, # column max length
        'primary_key'=>false, # enable for a primary key index
        'increment'=>false, # enable for auto increment column
    )
```
Note: null, unique, and max_length are used as part of validation rules generated

### Owner configuration
``owner`` is used to create `BELONGS_TO` relationship between two models i.e the column is a primary key of the specified model.

```
 'owner'=>array(
    'model'=>'ModelName', # The model name the has this column as it primary key
    'key'=>'relationAccessKey' # key to access the relation value
    ),

```
### Validation Configuration
``validation`` is used to generate the model validation rules. If validation is not defined in a column, it will be added to safe list<br/>

```
'validation'=>array(
    'required'=>true,
    'numerical'=>true,
    'length'=>array('min'=>0, 'max'=>255, 'message'=>'custom error message'),
    'pattern'=>array('match'=>'', 'message'=>'custom error message'),
    'file'=>array('type'=>'jpg, jpeg, png, gif', 'allowEmpty'=>true),
    'custom'=>array('unique') # List of custom validators
    ),
```

### Label
``label`` is used to customize column name for readability

### Form Configuration
``form`` is used to configure how the form view will be generated.<br/>

```
'form'=>array(
    'type'=>'dropdown', # type of form element, see complete list below
    'section'=>1,   # the section to place the form element
    'like'=>true,   # set this to false if you don't want like operator search in data provider
    'data'=>'ModelName::listData()', # PHP statement that will return an array of data for drop down. Only required for dropdown
    'widgetOptions'=>array( # This is same for <a href="http://yiibooster.clevertech.biz/">Yiibooster</a>  widgetOptions
        'htmlOptions'=>array(
            'prompt'=>'Select...'
        )
    ),
)

```

# Contribution
Feel free to improve the solution or fix any issues found in the solution and raise pull request.
