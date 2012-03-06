<?php

class GalleryConfig extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'galleryconfig';
	}

	public function rules()
	{
		return array(
			array('type, config', 'required'),
			array('type', 'length', 'max'=>8),
		);
	}

	public function attributeLabels()
	{
		return array(
			'type' => 'Type',
			'config' => 'Config',
		);
	}
}