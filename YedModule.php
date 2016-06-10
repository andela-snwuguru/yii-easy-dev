<?php

class YedModule extends CWebModule
{
	public $models = array();
	public $install = false;
	public $dropTable = false;
	public $dropColumn = false;
	public $useDefaultColumns = true;
	public $default_order = 'id DESC';
	private $default_models = array(
			'YedMigration',
			'YedActionLog',
			'YedAccessLog',
		);
	public $default_columns = array(
            'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'date_time' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );

	public function init()
	{
		// this method is called when the module is being created
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
			return true;
		}
		else
			return false;
	}

	public function getDefaultModels(){
		return $this->default_models;
	}
}
