<?php

class InstallController extends Controller
{
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

    public function actionMigrate($model = '', $code = ''){

    }
}