<?php

/**
 * This is the main module class.
 *
 * The followings are the available configurable properties:
 * @property array $models list of registered models extending YedActiveRecord
 * @property boolean $install set to true for fresh installation
 * @property boolean $dropTable set to true for dropping existing table during migration
 * @property boolean $dropColumn set to true for dropping existing column during migration
 * @property string $buttonLabelCreate the label of submit button during new record if you are using YedFormRender
 * @property string $buttonLabelUpdate the label of submit button during update if you are using YedFormRender
 * @property boolean $useDefaultColumns allow default columns to be applied to model migration
 * @property string $default_order sort order for data provider
 * @property array $default_columns list of columns to apply automatically to all registered models
 */
class YedModule extends CWebModule
{
	public $models = array();
	public $install = false;
	public $dropTable = false;
	public $dropColumn = false;
	public $buttonLabelCreate = 'Create';
	public $buttonLabelUpdate = 'Update';
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
