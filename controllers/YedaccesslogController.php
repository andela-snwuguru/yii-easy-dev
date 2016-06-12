<?php

class YedaccesslogController extends YedController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $modelName = 'YedAccessLog';
	public $viewColumns = array(
			'id','user_id', 'controller', 'action', 'date_time'
		);
	public $adminColumns = array(
			'user_id', 'controller', 'action', 'date_time'
		);
	public $disableIndex = true;
	public $disableCreate = true;
	public $disableUpdate = true;

	public function beforeView(&$model)
	{
		$this->append($this->alias.'_json_data', array('json_data'=>$model->data,'title'=>'Request Data'));
	}

}
