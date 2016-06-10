<?php

 class YedLogger extends CActiveRecordBehavior{

    public $log_data = true;

    public function afterSave($event) {
        $model = $this->getOwner();
        if ($model->getIsNewRecord()) {
            $this->log('created',$model);
        }else{
            $this->log('modified',$model);
        }

    }

    public function beforeDelete($event){
        $this->log('deleted',$this->getOwner());
    }

    function log($action,$model){
        if(!$this->log_data)
            return false;

        $log = new YedLog();
        $log->model_id = $model->id;
        $log->model_name = get_class($model);
        $log->user_id = Y::userId();
        $log->action = $action;
        $log->model_data = json_encode($model->attributes);
        $log->system_info = json_encode(U::getBrowser());
        $log->save(false);
    }

     public function logUpdate($table_name,$column,$id,$user_id,$date_time = ''){
         $date_time = empty($date_time) ? U::currentDate() : $date_time;
         return Yii::app()->db->createCommand()->update(
             $table_name,
             array($column.'_at'=>$date_time,$column.'_by'=>$user_id),
             'id = :param',
             array(':param'=>$id)
         );
     }

     public function migrateLogs($model){
         $arg = array(
             'condition'=>'model_name = "'.get_class($model).'" AND model_id = '.$model->id,
             'order'=>'id DESC',
             'group'=>'action',
         );
         $logs = CustomTableActionLog::model()->findAll($arg);
         if(!empty($logs)){
             foreach ($logs as $log) {
                 $action = strtolower($log->action);
                 if($action == 'created')
                     continue;

                 $column = str_ireplace(' ','_',$action);
                 $column_at = $column.'_at';
                 if(!isset($model->$column_at)){
                     $this->addLogColumn($column,$model);
                 }
                 if($this->logUpdate($model->tableName(),$column,$model->id,$log->user_id,$log->date_time))
                    $ok = true;
             }
         }else{
             echo 'No Log exists for Model: '.get_class($model).'; ID: '.$model->id.'<br/>';
         }
         return isset($ok);
     }

     public function addLogColumn($column,$model) {
         $command = Yii::app()->db->createCommand();
         $table = Yii::app()->db->schema->getTable($model->tableName());
         if(!$table)
             return false;

         if(!isset($table->columns[$column.'_at'])) {
             // Column doesn't exist
             $type = 'varchar(255) NOT NULL';
             if($r = $command->addColumn($model->tableName(), $column.'_at', $type)){
                 $command->addColumn($model->tableName(), $column.'_by', 'INT(11) NOT NULL');
                 return true;
             }
         }else{
             return true;
         }

         return false;

     }


    static function logAccess($remark=''){
        $controller_id = Yii::app()->controller->id;
        $actionid = Yii::app()->controller->action->id;
        if(empty($remark)){
            $username = Y::userId() ? user()->name : 'Guest';
            $remark = $username.' visited '.$controller_id.'/'.$actionid;
        }
        $model = new CustomTableAccessLog();
        $model->action = $actionid;
        $model->controller = $controller_id;
        $model->data = self::getRequest();
        $model->remarks = $remark;
        $model->save(false);
    }

 }