<?php

class FItem  extends CFormModel
{
	public $image;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Vote the static model class
	 */
	// public static function model($className=__CLASS__)
	// {
		// return parent::model($className);
	// }


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image', 'file', 'types'=>'jpg', 'maxSize'=>1024 * 1024 * 5, 'tooLarge'=>'Soubor musí být typu .jpg menší než 5MB.'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	
}