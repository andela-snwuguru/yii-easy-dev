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
YED currently works perfect in YII 1.x.x
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

## Model Properties
YedActiveRecord is the base model class to be extended by all models
* @property array $columns model columns configuration.
* @property string $_table_name the name of table to be created in database
* @property boolean $log if enabled, all create, update and delete activity of the model will be logged
* @property boolean $applyDefaultColumns if false, defaultColumns configured in YedModule settings will not be applied to the child model
* @property array $additional_label configure additional label parameters such as relationship chain

## Model Columns Configurations
See below for all available configurable parameters of models extending YedActiveRecord class with their default values or sample values. <br/>

### Sample Columns Configuration
```
    public static $columns = array(
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
        'many'=>array(
            'key1'=>array('ModelName1','column_name1'),
            'key2'=>array('ModelName2','column_name2'),
        ),
        'one'=>array(
            'key1'=>array('ModelName1','column_name1'),
            'key2'=>array('ModelName2','column_name2'),
        ),
    );
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

### Many Configuration
``many`` is used to list all the one to many relation that exist in the model

```
'many'=>array(
    'key1'=>array('ModelName1','column_name1'),
    'key2'=>array('ModelName2','column_name2'),
),

```

### One Configuration
``one`` is used to list all the one to one relation that exist in the model

```
'one'=>array(
    'key1'=>array('ModelName1','column_name1'),
    'key2'=>array('ModelName2','column_name2'),
),
```

# YED Base Controller
YedController is the customized base controller class. All controller classes for your application that needs dynamic CRUD should extend from this base class.

### YED Controller Properties

 * @property array $sectionTitles Title in each section. index one is for section one and so on.
 * @property array $sectionColumns Number of columns in each section. index one is for section one and so on.
 * @property string $formType form display type; (vertical, horizontal and inline)
 * @property string $formClass css class to apply in form tag
 * @property string $formId form id attribute value
 * @property string $sectionClass css class to apply in each section wrapper
 * @property string $submitFooterClass css class to apply in submit button wrapper
 * @property string $modelName model class name for CRUD operation
 * @property string $modelTitle spaced words of modelName
 * @property string $alias path to default YED views
 * @property boolean $isUpload set to true if the model has a file field
 * @property array $pageHeaders configure controller action title
 * @property array $subMenuTitles configure sub menu titles by action id (action_id=>title)
 * @property array $additionalMenu additional menu configuration
 * @property boolean $disableCreate if enabled, the create action will not be accessible
 * @property boolean $disableUpdate if enabled, the update action will not be accessible
 * @property boolean $disableView if enabled, the view action will not be accessible
 * @property boolean $disableDelete if enabled, the delete action will not be accessible
 * @property boolean $disableAdmin if enabled, the admin action will not be accessible
 * @property boolean $disableIndex if enabled, the index action will not be accessible
 * @property boolean $disableLog if disabled, every controller action visit will be logged
 * @property boolean $enableAjaxValidation enable for Ajax validation
 * @property boolean $addActionButtons if disabled, action buttons in admin view will not be visible
 * @property array $viewColumns list of columns to display in detail view
 * @property array $adminColumns list of columns to display in admin view
 * @property array $indexColumns list of columns to display in list view
 * @property array $relations list of has many relations to display in detail view
 * @property string $prepend element to display at the top of a view
 * @property string $append element to display at the bottom of a view

# Utilities
YED provides utility class to facilitate rapid development
* Y class contains helper functions that has to do with YII operations
* YedUtil class contains helper functions for PHP. It good to mention here that some function in this class was implement by different author and has not been properly tested but might be useful to your project.
* Upload class handles file upload. See implementation in Y class
* YedRender class contains several UI elements that you can render directly from any view. Currently supports <a href="http://yiibooster.clevertech.biz/">Yiibooster</a> UI widgets

# Demo
Demo will be available soon. click here to see demo project repository.

# To DOs
- Support for Material UI Element
- YED 2 for YII 2

# Contribution
Feel free to improve this solution or fix any issues found in the solution. You can also volunteer to carry out any of the to do list above.
