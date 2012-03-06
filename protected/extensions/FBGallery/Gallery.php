<?php

class Gallery extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'galleries';
	}

	public function rules()
	{
		return array(
			array('pid, imgsOrder', 'required'),
			array('pid', 'numerical', 'integerOnly'=>true),
			array('id, pid, inf', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => 'Pid',
			'imgsOrder' => 'Images Order',
		);
	}
}