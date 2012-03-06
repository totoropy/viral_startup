<?php

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property integer $id
 * @property string $created
 * @property string $name
 * @property string $path
 * @property string $fbid
 * @property integer $votes
 * @property integer $views
 * @property string $caption
 * @property string $place
 * @property string $time
 * @property string $who
 * @property integer $width
 * @property integer $height
 */
class FImage extends CActiveRecord
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
		return 'images';
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
			array('votes, state, views, width, height', 'numerical', 'integerOnly'=>true),
			array('name, place, time, who', 'length', 'max'=>100),
			array('path, caption', 'length', 'max'=>250),
			array('caption, place, time, who', 'filter', 'filter'=>array( $this, 'filterText' )),
			array('fbid', 'length', 'max'=>50),
			array('state', 'length', 'max'=>1),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created, name, path, fbid, votes, views, caption, place, time, who, width, height', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'created' => 'Created',
			'name' => 'Název',
			'path' => 'Path',
			'fbid' => 'Fbid',
			'votes' => 'Votes',
			'state' => 'Stav',
			'views' => 'Views',
			'caption' => 'Popis',
			'place' => 'Kde',
			'time' => 'Kdy',
			'who' => 'Kdo',
			'width' => 'Width',
			'height' => 'Height',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('fbid',$this->fbid,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('votes',$this->votes);
		$criteria->compare('views',$this->views);
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('place',$this->place,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('who',$this->who,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}