<?php

class YedLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return YedLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'yed_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

		);
	}


    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'model_name' => 'Model Name',
			'model_id' => 'Model',
			'action' => 'Action',
			'model_data' => 'Model Data',
			'system_info' => 'System Info',
			'date_time' => 'Date Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($flag = true,$user_id = 0)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

        $user_id = $user_id ? $user_id : '';
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$user_id);
		$criteria->compare('model_name',$this->model_name,$flag);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('action',$this->action,$flag);
		$criteria->compare('model_data',$this->model_data,true);
		$criteria->compare('system_info',$this->system_info,true);
		$criteria->compare('date_time',$this->date_time,true);
        $criteria->order = 'id DESC';

        if(Y::is('subscribe',true)){
            $criteria->addCondition('model_name = "Notice" OR model_name = "CashRequest" OR model_name = "BankDetail" OR model_name = "Ticket" OR model_name = "Work" OR model_name = "Education" OR model_name = "Profile"','AND');
            //$criteria->addCondition('model_name != "AuditScore" AND model_name != "NotificationLogs" AND model_name != "RequestLog" AND model_name != "SocialReputation" AND model_name != "FacebookData"','AND');
            //$criteria->addCondition('action != "modified"','AND');
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}