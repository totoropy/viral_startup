<?php

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property integer $fbid
 * @property string $name
 * @property integer $points
 */
class ScoreItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FImage the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, fbid', 'required'),
			array('points', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('fbid, name', 'safe', 'on'=>'search'),
		);
	}


	public function  filterText($value)
	{
		$value = preg_replace('/\"/', '', $value);
		$value = preg_replace('/\'/', '', $value);
		$value = preg_replace('/</', '(', $value);
		$value = preg_replace('/>/', ')', $value);
		return $value;
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
			'fbid' => 'ID',
			'name' => 'Název',
			'points' => 'Points',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('fbid',$this->fbid);
		$criteria->compare('name',$this->name,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}