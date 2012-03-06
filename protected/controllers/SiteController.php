<?php

//Yii::import('application.extensions.FBGallery.Facebook');

class SiteController extends Controller
{
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	
	public function checkRequest($rid, $uid)
	{
		if(isset($rid))
		{
			try
			{
				$req = FRequest::model()->find('requestId=:rid and friendId=:fed', array(':rid'=>$rid, ':fed'=>$uid ));
				if(isset($req))
				{
					$fu = $fu = FUser::model()->find('facebookID=:facebookID', array(':facebookID'=>$req->fbId)); 
					if(isset($fu))
					{						
						$req->accepted = 1;
						$req->save();
						
						$delete_success = self::delRequest($rid, $uid);			
						return true;
					}
				}
			}          
			catch (FacebookApiException $e) 
			{  	}
		}
		return false;
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if( !isset($_SESSION["fbid"]) )
		{
			$this->render('error', "Uživatel není přihlášen do Facebooku.");
			Yii::app()->end();
		}

		//$_SESSION['request_ids'] is set in Controller from $_REQUEST['request_ids']
		if( isset($_SESSION['request_ids']) )
		{
			//user comes from invitation
			$rids = $_SESSION['request_ids'];
			if(strpos($rids, ",")>-1)
			{
				$arr = explode(",", $rids);
				for($i=0;$i<count($arr);$i++)
				{
					self::checkRequest($arr[$i], $_SESSION["fbid"]);
				}
			}
			else
			{
				self::checkRequest($rids, $_SESSION["fbid"]);
			}
			
			$_SESSION['request_ids'] = null;
		}

		if( !self::checkFun() )
		{		
			// go to non-fan.php
			echo "<script type='text/javascript'>top.location.href = '" . Yii::app()->params['appCanvasUrl'] . "non-fan.php';</script>";
			Yii::app()->end();
		}

		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			$model = new FUser;
			//echo $_POST['FUser']['email'];//CActiveForm::validate($model);
			$validator = new CEmailValidator;
			if( $validator->validateValue($_POST['FUser']['email']) )
			{
				$facebookID = $_POST['FUser']['facebookID'];
				$fu = FUser::model()->find('facebookID=:facebookID', array(':facebookID'=>$facebookID));
				if( isset($fu) )
				{
					$fu->nick  = $_POST['FUser']['nick'];
					$fu->email = $_POST['FUser']['email'];
					$fu->save();
					echo "1";
					Yii::app()->end();
				}
				else
					echo "0";
			}
			else
			{
				echo "0";
			}
			Yii::app()->end();	//end for AJAX
		}

		$this->bgImage = "images/bg.jpg";
		$this->drawMenu = true;
			
		self::publishJQAssets();
			
		//GET  -> get user and his image
		if( isset($_SESSION["fbid"]) )
		{
			$fu = self::loadUserByFBID($_SESSION["fbid"]);
			if(Yii::app()->user->isGuest)
				self::autologin();

			
			if( isset($fu) )
			{
				if(empty($fu->nick))
				{
					//fix nick if needed
					if(empty($fu->username))
					{
						$fu->nick = $fu->username;
					}
					else
					{
						if(empty($fu->name))
							$fu->nick = $fu->first_name . "." . $fu->last_name;
						else
							$fu->nick = $fu->name;
					}
					$fu->save();
				}
				
					
				$this->render('index', array('model'=>$fu, 'req_per_day'=>self::loadRequestsPerDay() ));				
			}
			else
			{
				$this->render('error', "Uživatel " . $_SESSION["fbid"] . " nenalezen.");
			}		
		}
		else
		{
			$this->render('error', "Uživatel  není přihlášen do Facebooku.");
		}		
	}

	
	public function actionDashboard()
	{
		if( !isset($_SESSION["fbid"]) )
		{
			$this->render('error', "Uživatel není přihlášen do Facebooku.");
			Yii::app()->end();
		}
		
				
		$this->drawMenu = true;
		
		//JQuery reg.
		self::publishJQAssets();
		
		$i = 0;
		$profiles = array();
		$reqs = self::getMyRequests();
		$sent = 0;
		$accepted = 0;
		foreach($reqs as $req)
		{
			$pageContent = file_get_contents('http://graph.facebook.com/' . $req['friendId']);
			$parsedJson  = json_decode($pageContent);
			$req['id'] = $parsedJson->name;
			$profiles[$i++] = $this->renderPartial('profile', array('req'=>$req), true);
			
			if($req['friendId']==1)
				$accepted++;
			
			$sent++;	
		}
		
		$this->render('dashboard', array('profiles'=>$profiles, 'sent'=>$sent, 'accepted'=>$accepted, 'req_per_day'=>self::loadRequestsPerDay()) );		
	}
	
	

	
	public function actionScore()
	{
		
		$criteria = new CDbCriteria();
		$criteria->order = 'points DESC';
		
		// results per page
		$users = ScoreItem::model()->findAllBySql("SELECT DISTINCT u.facebookID, u.name, COUNT(*) AS points FROM tblrequests r JOIN users u ON r.fbId=u.facebookID WHERE r.accepted=1 GROUP BY r.fbId ORDER BY points DESC");
		if(isset($users))
		{
			$pages = new CPagination(count($users));
			$pages->pageSize=25;
			$pages->applyLimit($criteria);	
		
			$rawData=array();
			$i = 1;
			foreach($users as $user)
			{
				$item = array(
					'id'=>$i,
					'fbid'=>$user->facebookID,
					'name'=>$user->name,
					'points'=>$user->points,
				);
				$rawData[$i++] = $item;
			}
			

			$arrayDataProvider=new CArrayDataProvider($rawData, array(
				'id'=>'id',
				/* 'sort'=>array(
					'attributes'=>array(
						'username', 'email',
					),
				), */
				'pagination'=>array(
					'pageSize'=>3,
				),
			));

			$params =array(	'arrayDataProvider'=>$arrayDataProvider,);

			if(!isset($_GET['ajax'])) 
				$this->render('score', $params);
			else  
				$this->renderPartial('score', $params);
			
		}
	}
	
		
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionReport()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('report','Děkuji za spolupráci. Ozvu se hned, jak to bude možné.');
				$this->refresh();
			}
		}
		$this->render('report',array('model'=>$model));
	}
	
	public function actionMail()
	{
		$model = new MailForm;
		$model->fbid = $_SESSION["fbid"];
		$model->imageId = $_REQUEST['imageId'];
		
		$criteria = new CDbCriteria();
		$criteria->condition = "facebookID='" . $_SESSION["fbid"] . "'";
		$usrs = FUser::model()->findAll($criteria);
		if(isset($usrs))
			$model->from = $usrs[0]->email;

		
		$this->renderPartial('mail', array('model'=>$model) );
	}
	

	public function actionDelete($id)
	{	
		$imageId=(int)$id;
		$img = null;
		if( $imageId>0 )
		{
			//load image
			$img = FImage::model()->find('id=:id', array(':id'=>$imageId));
			if( isset($img) )
			{
				$img->state = 2;	//2=deleted
				$img->save(); 
				$img = null;
			}
		}
		
		$fu = null;
		if( isset($_SESSION["fbid"]) )
		{
			$fu = self::loadUserByFBID($_SESSION["fbid"]);
		}
		$this->render('index', array('model'=>$fu, 'img'=>$img) );			
	}		
	
	
		/**
	 * Displays the contact page
	 */
	public function actionProfile()
	{
		$this->pageName = "profile";
		$this->render('profile');
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function autologin()
	{
		if( isset($_SESSION["fbid"]) )
		{
			$m = new LoginForm;
			$fu=FUser::model()->find('facebookID=:facebookID', array(':facebookID'=>$_SESSION["fbid"]));
			if( isset($fu) )
			{
				$m->fbid = $_SESSION["fbid"];
				$m->username = $fu->username;
				return $m->login();
			}
		}
		return false;
	}
	
	
	
}