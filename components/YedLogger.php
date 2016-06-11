<?php

 class YedLogger extends CActiveRecordBehavior{

    /**
    * Logs records after save action
    */
    public function afterSave($event) {
        $model = $this->getOwner();
        if ($model->getIsNewRecord()) {
            $this->log('created',$model);
        }else{
            $this->log('modified',$model);
        }
    }

    /**
    * Logs records before deletion
    */
    public function beforeDelete($event){
        $this->log('deleted',$this->getOwner());
    }

    /**
    * Logs actions performed in a model
    * @param String $action the action performed
    * @model ActiveRecord $model the loaded instance of the model in which the action was performed
    * @return False if log attribute is set to false
    */
    function log($action,$model){
        $model = $this->getOwner();
        if(!$model->log)
            return false;

        YedActionLog::add($action, $model);
    }

 }