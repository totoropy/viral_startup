<?php

/**
 * This is the model class for table "votes".
 *
 * The followings are the available columns in table 'votes':
 * @property string $id
 * @property integer $imageId
 * @property string $fbid
 * @property string $created
 */
class Vote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Vote the static model class
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
		return 'votes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('imageId, fbid', 'required'),
			array('imageId', 'numerical', 'integerOnly'=>true),
			array('fbid', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, imageId, fbid, created', 'safe', 'on'=>'search'),
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
			'imageId' => 'Image',
			'fbid' => 'Fbid',
			'created' => 'Created',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('imageId',$this->imageId);
		$criteria->compare('fbid',$this->fbid,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function isvoted($fbid, $imageId)
	{
		// $criteria=new CDbCriteria;
		// $criteria->select= "id";
		// $criteria->condition = "fbid='" . $fbid . "' AND imageId=" . $imageId;

		
		$n = $this->countBySql("SELECT id FROM votes WHERE fbid='".$fbid."' AND imageId=".$imageId);
		return $n;
		
		//$condition = array('fbid=:fbid','imageId=:imageId');
		//$params = array(':fbid'=>$fbid, ':imageId'=>$imageId);
		//$n = self::count($condition,$params);
		//return $n;
		
		//return parent::exists('fbid = :fbid AND imageId =:imageId', array(':fbid'=>$fbid, ':imageId'=>$imageId));
		//$arr = $this->find_by_sql("SELECT COUNT(*) as total FROM votes WHERE fbid='".$fbid."' AND imageId=".$imageId);
		//return 0;
	}
}