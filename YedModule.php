<?php

class YedModule extends CWebModule
{
	public $models = array('Migration', 'YedLogger');
	public $install = true;
	public $dropTable = false;
	public $dropColumn = false;
	public $useDefaultColumns = true;
	public $default_columns = array(
            'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'date_time' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'yed.models.*',
			'yed.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
