<?php

class YedactionlogController extends YedController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $modelName = 'YedActionLog';
	public $viewColumns = array(
			'id','model_name', 'model_id', 'action', 'date_time'
		);
	public $adminColumns = array(
			'model_name', 'model_id', 'action', 'date_time'
		);
	public $disableIndex = true;
	public $disableCreate = true;
	public $disableUpdate = true;

	public function beforeView(&$model)
	{
		$this->append($this->alias.'_json_data', array('json_data'=>$model->model_data,'title'=>'Model Data'));
		$this->append($this->alias.'_json_data', array('json_data'=>$model->system_info,'title'=>'System Information'));
	}

}
