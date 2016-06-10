<?php

class InstallController extends Controller
{
    /**
    * Run this action to install yed default models
    */
	public function actionIndex(){
        $params = Y::getModule();
        if(!$params->install){
            Y::info('You need to enable install in the module configuration');
        }else{
            foreach ($params->getDefaultModels() as $model) {
                YedOperation::createTable($model);
            }
        }
        Y::flashes();
	}

    /**
    * Run this action to migrate registered yed models
    */
    public function actionMigrate($model = '', $code = ''){
        $params = Y::getModule();
        if(empty($params->models)){
            die('No model registered');
        }
        if(!empty($model)){
            $this->singleMigration($model, $code);
        }else{

            foreach ($params->models as $model_name) {
                $this->singleMigration($model_name);
            }
        }
        Y::flashes();
    }

    public function singleMigration($model, $code = ''){
        $params = array(
                'condition'=>'model = "'.$model.'"',
                'order'=>'id DESC'
            );
        if(!empty($code)){
            $params['condition'] += ' and code = "'.$code.'"';
        }
        $migrate = YedMigration::model()->find($params);
        $columns_params = YedOperation::getColumns($model);
        if(!empty($migrate)){
            if(json_encode($columns_params) == $migrate->params){
                Y::info('No Changes to column parameters');
                return false;
            }else{
                YedOperation::dropRemovedColumns($model, $columns_params, json_decode($migrate->params));
            }
        }
        YedOperation::createTable($model, true);
    }
}