<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $uid
 * @property string $created_at
 * @property string $facebookID
 * @property string $name
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $password
 * @property string $nick
 * @property integer $status
 * @property integer $points
 * @property integer $published
 * @property string $birthday
 * @property string $gender
 * @property string $email
 * @property string $cache
 * @property string $requestId
 */
class FUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FUser the static model class
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
			array('facebookID, name', 'required'),
			array('email', 'email'),
			array('status,points,published', 'numerical', 'integerOnly'=>true),
			array('facebookID, name, first_name, last_name, username, nick, birthday, gender', 'length', 'max'=>100),
			array('password', 'length', 'max'=>50),
			array('cache, requestId', 'length', 'max'=>250),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uid, facebookID, name, username', 'safe', 'on'=>'search'),
		);
	}

	public function hasEmail()
	{
		if( isset($this->email) )
		{
			return self::isValidEmail($this->email);
		}
		
		return false;
	}
	
	function isValidEmail($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
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
			'uid' => 'Uid',
			'created_at' => 'Založeno',
			'facebookID' => 'Facebook',
			'name' => 'Name',
			'first_name' => 'Jméno',
			'last_name' => 'Příjmení',
			'username' => 'Username',
			'password' => 'Heslo',
			'nick' => 'Jméno',
			'status' => 'Status',
			'points' => 'Body',
			'published' => 'publikováno',
			'birthday' => 'Birthday',
			'gender' => 'Pohlaví',
			'email' => 'Email',
			'cache' => 'Cache',
			'requestId' => 'Request',
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

		$criteria->compare('uid',$this->uid);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('facebookID',$this->facebookID,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('nick',$this->nick,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('points',$this->points);
		$criteria->compare('published',$this->published);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('cache',$this->cache,true);
		$criteria->compare('requestId',$this->requestId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}