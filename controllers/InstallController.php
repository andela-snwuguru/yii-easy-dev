<?php

class InstallController extends Controller
{
	public function actionIndex(){
        $params = Y::getModule();

        YedUtil::debug($params->test);
	}
}