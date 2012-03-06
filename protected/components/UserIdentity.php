<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	// Facebook ID
	public $fbid = "";
	
	
	public function getFbId()
	{
		return $this->fbid;
	}
	
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		// $users=array(
		//	username => password
			// 'demo'=>'demo',
			// 'admin'=>'admin',
		// );
		
		$fu = FUser::model()->find('facebookID=:facebookID', array(':facebookID'=>$_SESSION["fbid"]));
		
		if( !isset($fu) )
			$this->errorCode=self::ERROR_USERNAME_INVALID;	//$fu==null
		else if(!isset($_SESSION["fbid"]))					//$_SESSION["fbid"]==null
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
			
		return !$this->errorCode;
	}
	
	public function isAuthenticated()
	{
		if(isset($_SESSION["fbid"]))
			return true;
		else
			return false;
	}
	
	public function isAdmin()
	{
		if( isAuthenticated() )
		{
			if( $_SESSION["fbid"]=="123aaa" || $_SESSION["fbid"]=="123aaa" )
				return true;
		}
		return false;
	}
}