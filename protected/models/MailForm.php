<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class MailForm extends CFormModel
{
	public $fbid;
	public $from;
	public $to;
	public $body;
	public $imageId;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('fbid, from, to, body, imageId', 'safe'),
			// to is email
			//array('to, from', 'email'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'from'=>'Od',
			'to'=>'Komu',
			'body'=>'Zpráva',
		);
	}



	public function sendMail()
	{

	}
}
