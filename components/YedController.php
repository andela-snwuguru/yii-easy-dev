<?php

/**
 * YedController is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *@property array $sectionTitles Title in each section. index one is for section one and so on.
 *@property array $sectionColumns Number of columns in each section. index one is for section one and so on.
 *@property string $formType form display type; (vertical, horizontal and inline)
 *@property string $formClass css class to apply in form tag
 *@property string $formId form id attribute value
 *@property string $sectionClass css class to apply in each section wrapper
 *@property string $submitFooterClass css class to apply in submit button wrapper
 *@property string $modelName model class name for CRUD operation
 *@property string $modelTitle spaced words of modelName
 *@property string $alias path to default YED views
 *@property boolean $isUpload set to true if the model has a file field
 *@property array $pageHeaders configure controller action title
 *@property array $subMenuTitles configure sub menu titles by action id (action_id=>title)
 *@property array $additionalMenu additional menu configuration
 *@property boolean $disableCreate if enabled, the create action will not be accessible
 *@property boolean $disableUpdate if enabled, the update action will not be accessible
 *@property boolean $disableView if enabled, the view action will not be accessible
 *@property boolean $disableDelete if enabled, the delete action will not be accessible
 *@property boolean $disableAdmin if enabled, the admin action will not be accessible
 *@property boolean $disableIndex if enabled, the index action will not be accessible
 *@property boolean $disableLog if disabled, every controller action visit will be logged
 *@property boolean $addActionButtons if disabled, action buttons in admin view will not be visible
 *@property array $viewColumns list of columns to display in detail view
 *@property array $adminColumns list of columns to display in admin view
 *@property array $indexColumns list of columns to display in list view
 *@property string $prepend element to display at the top of a view
 *@property string $append element to display at the bottom of a view
 *
 */
class YedController extends Controller
{
    public $sectionTitles = array();
    public $sectionColumns = array();
    public $formType = 'vertical';
    public $formClass = '';
    public $formId= '';
    public $sectionClass= 'well';
    public $submitFooterClass= 'well';
    public $modelName= '';
    public $modelTitle;
    public $alias = 'application.modules.yed.views.default.';
    public $isUpload = false;
    public $pageHeaders = array();
    public $subMenuTitles = array();
    public $additionalMenu = array();
    public $disableCreate = false;
    public $disableUpdate = false;
    public $disableView = false;
    public $disableDelete = false;
    public $disableAdmin = false;
    public $disableIndex = false;
    public $disableLog = true;
    public $addActionButtons = true;
    public $viewColumns = array();
    public $adminColumns = array();
    public $indexColumns = array();
    public $prepend = '';
    public $append = '';


    public function beforeAction($action){
        $this->validatePageAccess();
        $this->modelTitle = YedUtil::spaceCap($this->modelName);
        $this->setPageHeaders();
        $this->setupMenu($action);
        if(!$this->disableLog){
            YedAccessLog::add();
        }
        return parent::beforeAction($action);
    }


    public function filters()
    {
        return array(
            //'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
       return array(); //Y::yumRules();
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new $this->modelName();
        $this->performAjaxValidation($model);
        if(isset($_POST[$this->modelName])){
            $this->beforeCreate($model);
            $model->attributes = $_POST[$this->modelName];
            if($model->validate()){
                if($this->isUpload)
                    $model = $this->checkUpload($model);
                if($model->save()){
                    Y::ok($this->modelTitle.' Saved!');
                    $this->redirect(array('view','id'=>$model->id));
                }
            }

        }

        $this->render($this->alias.'create',array(
            'model'=>$model,
        ));
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if(isset($_POST[$this->modelName]))
        {
            $this->beforeUpdate($model);
            $model->attributes = $_POST[$this->modelName];
            if($model->validate()){
                if($this->isUpload)
                    $model = $this->checkUpload($model);
                if($model->save()){
                    Y::ok($this->modelTitle.' Updated!');
                    $this->redirect(array('view','id'=>$model->id));
                }
            }

        }

        $this->render($this->alias.'update',array(
            'model'=>$model,
        ));
    }


    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->beforeView($model);
        $this->render($this->alias.'view',array(
            'model'=>$model,
        ));
    }


    /**
    * Manages all models.
    */
    public function actionAdmin()
    {
        $model=new $this->modelName('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET[$this->modelName]))
            $model->attributes=$_GET[$this->modelName];

        $this->beforeAdmin($model);
        $this->render($this->alias.'admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest){
            $model = $this->loadModel($id);
            $this->beforeDelete($model);
            $model->delete();
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider($this->modelName);
        $this->render($this->alias.'index',array(
            'dataProvider'=>$dataProvider,
        ));
    }


    /**
    *@return string title of a sub menu
    */
    private function getSubMenuTitle($key, $init){
        return isset($this->subMenuTitles[$key]) ? $this->subMenuTitles[$key] : $init.' '.$this->modelTitle;
    }

    /**
    * Implement this method for access control
    *@param string $action the action id
    */
    public function userCan($action){
        return true;
    }

    /**
    * Check if an action is disabled
    */
    private function validatePageAccess(){
        $flag = 'disable'.ucwords(Yii::app()->controller->action->id);
        if(isset($this->$flag) && $this->$flag){
            Y::exception('Invalid page access');
        }
    }

    /**
     * Sets up the sub menus.
     * @param object $action the current action
     */
    public function setupMenu($action){
        $this->menu=array(
            array(
                'label'=>$this->getSubMenuTitle('index', 'List'),
                'url'=>array('index'),
                'visible'=>$this->userCan('index') && $action->id != 'index' && !$this->disableIndex
                ),
            array(
                'label'=>$this->getSubMenuTitle('create', 'Add'),
                'url'=>array('create'),
                'visible'=>$this->userCan('create') && $action->id != 'create' && !$this->disableCreate
                ),
            array(
                'label'=>$this->getSubMenuTitle('view', 'View'),
                'url'=>array('view','id'=>isset($_GET['id']) ? $_GET['id'] : 0),
                'visible'=>$this->userCan('view') && !$this->disableView && $action->id == 'update'
                ),
            array(
                'label'=>$this->getSubMenuTitle('update', 'Update'),
                'url'=>array('update','id'=>isset($_GET['id']) ? $_GET['id'] : 0),
                'visible'=>$this->userCan('update') && $action->id == 'view' && !$this->disableUpdate
                ),

            array(
                'label'=>$this->getSubMenuTitle('delete', 'Delete'),
                'url'=>'#',
                'linkOptions'=>array(
                    'submit'=>array(
                        'delete',
                        'id'=>isset($_GET['id']) ? $_GET['id'] : 0
                    ),
                    'confirm'=>'Are you sure you want to delete this item?'
                ),
                'visible'=>$this->userCan('delete') && $action->id == 'view' && !$this->disableDelete
                ),
            array(
                'label'=>$this->getSubMenuTitle('admin', 'Manage'),
                'url'=>array('admin'),
                'visible'=>$this->userCan('admin') && $action->id != 'admin' && !$this->disableAdmin
                ),
            array(
                'label'=>$this->getSubMenuTitle('log', 'Logged'),
                'url'=>array('log','id'=>isset($_GET['id']) ? $_GET['id'] : 0),
                'visible'=>$this->userCan('log') && $action->id == 'view' && !$this->disableLog
                ),
        );
        $this->addMenu($this->additionalMenu);
    }

    public function getBreadcrum(){
        $breadcrum = array(
                'view'=>array($this->modelTitle => array('admin'), 'View'),
                'update'=>array($this->modelTitle => array('admin'), 'Update'),
                'create'=>array($this->modelTitle => array('admin'), 'Create'),
                'index'=>array($this->modelTitle => array('admin'), 'List'),
                'admin'=>array($this->modelTitle),
            );
        return $breadcrum[Yii::app()->controller->action->id];
    }

    /*
     * Adds menu to the existing menus configuration
     * @param array $menus the menu configuration to be added
     */
    public function addMenu($menus){
        foreach($menus as $menu){
            $this->menu[] = $menu;
        }
    }

    /**
    * Setup the page headers
    */
    public function setPageHeaders(){
        $default_header = array(
            'view'=>$this->modelTitle.' Details',
            'create'=>'Add '.$this->modelTitle,
            'update'=>'Update '.$this->modelTitle.' Details',
            'admin'=>'Manage '.$this->modelTitle,
        );

        if(!empty($this->pageHeaders)){
            foreach($this->pageHeaders as $key=>$val){
                    $default_header[$key] = $val;
            }
        }

        $this->pageHeaders = $default_header;
    }

    /**
     * Adds element to the bottom of a view.
     * @param string $alias path to the file to append
     * @param array $data the data to pass to the file loaded
     */
    public function append($alias,$data = array()){
        ob_start();
        $this->renderPartial($alias,$data);
        $this->append .= ob_get_clean();
    }

    /**
     * Adds element to the top of a view.
     * @param string $alias path to the file to append
     * @param array $data the data to pass to the file loaded
     */
    public function prepend($alias,$data = array()){
        ob_start();
        $this->renderPartial($alias,$data);
        $this->prepend .= ob_get_clean();
    }

    /*
     * checks if any of the model attributes is a file field and uploads the file
     * @param object $model the loaded model
     */
    public function checkUpload($model){
        $returning_model = $model;
        foreach($model->attributes as $key=>$val){
            $image_folder = get_class($model);
            $image = Y::upload('/'.$image_folder.'/',$model,$key.'_'.rand(),$key);
            if($image){
                $returning_model->$key = $image;
            }
        }
        return $returning_model;
    }

    /*
     * Override this method in the child controller to perform a task before delete action
     * @param object $model the loaded model
     */
    public function beforeDelete(&$model){

    }

    /*
     * Override this method in the child controller to perform a task before update action
     * @param object $model the loaded model
     */
    public function beforeUpdate(&$model){

    }

    /*
     * Override this method in the child controller to perform a task before create action
     * @param object $model the loaded model
     */
    public function beforeCreate(&$model){

    }

    /*
     * Override this method in the child controller to perform a task before view action
     * @param object $model the loaded model
     */
    public function beforeView(&$model){

    }

    /*
     * Override this method in the child controller to perform a task before admin action
     * @param object $model the loaded model
     */
    public function beforeAdmin(&$model){

    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $modelName = $this->modelName;
        $model = $modelName::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']=== strtolower($this->modelName))
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
?>