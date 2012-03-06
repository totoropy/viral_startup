<?php

/**
 * This is the model class for table "votes".
 *
 * The followings are the available columns in table 'votes':
 * @property string $id
 * @property string $fbId
 * @property string $created
 * @property string $requestId
 * @property string $accepted
 * @property string $friendId
 */
class FRequest extends CActiveRecord
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
		return 'tblrequests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('requestId, fbId', 'required'),
			array('fbId', 'length', 'max'=>50),
			array('created, accepted, friendId', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, requestId, fbId, created', 'safe', 'on'=>'search'),
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
			'requestId' => 'requestId',
			'friendId' => 'friendId',
			'fbId' => 'FbId',
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
		$criteria->compare('requestId',$this->requestId);
		$criteria->compare('fbId',$this->fbid,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function myRequestsCount($fbid)
	{
		$n = $this->countBySql("SELECT id FROM tblrequests WHERE fbId='" . $fbid . "'");
		return $n;

	}
}