<?php

Yii::import('application.vendors.fbsdk311.*');
require_once('protected/vendors/fbsdk311/FacebookWrapper.php');
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
  /**
   * @var string the default layout for the controller view. Defaults to '//layouts/column1',
   * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
   */
  public $layout='//layouts/column1';
  /**
   * @var array context menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $menu=array();
  public $drawMenu = true;
  /**
   * @var array the breadcrumbs of the current page. The value of this property will
   * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
   * for more details on how to specify this property.
   */
  public $breadcrumbs=array();
  public $pageName = '';
  public $bgImage = 'images/bg-empty.jpg';
  public $pageTitle = '';
  public $facebook;

  function __construct($id,$module=null) {
    parent::__construct($id, $module);
    // Get an instance of the Facebook class distributed as the PHP SDK by facebook:
    Yii::app()->params['facebook'] = new FacebookWrapper(array(
      'appId'  => Yii::app()->params['appid'],
      'secret' => Yii::app()->params['secret'],
      'cookie' => true,
      'appName' => Yii::app()->params['appName'],
      'canvasPage' => Yii::app()->params['appCanvasUrl'],
      'canvasUrl'  => Yii::app()->params['appBaseUrl']
      ));

  }

  /**
   * Filters for all the request coming to facebook application 
   */
  public function filters() {
    return array(
            'facebook',
    );
  }

  
    public function isAdmin()
	{
		if(isset($_SESSION["fbid"])) // array is not empty, so the user is a fan!
		{
			if( $_SESSION["fbid"]=='601263147' ) //Joe Bobson
				return true;
				
			$admins = explode(',', Yii::app()->params['adminIds']);
			foreach($admins as $admin)
				if( $_SESSION["fbid"]==$admin )
					return true;
		}

		return false;
	}
	
    public function hasPermission($permName)
	{
		if( !isset($_SESSION["permissions"]) )
		{
			try
			{
				$facebook = Yii::app()->params['facebook'];
				$permissions = $facebook->api("/me/permissions");
				$_SESSION["permissions"] = $permissions['data'][0];
			}
			catch(Exception $e){ return false; }	//OAuthException
		}
		
		if( array_key_exists($permName, $_SESSION["permissions"]) ) 
			return true;
		else 
			return false;
	}
	
	public function getProfile()
	{
		header('P3P: CP="CAO PSA OUR"');
		$facebook = Yii::app()->params['facebook'];
		$user = $facebook->getUser();
		
		$fbme = null;
		if ($user) 
		{
		  try 
		  {
			$uid = $user;//$facebook->getUser();
			$fbme = $facebook->api('/me');
		  } 
		  catch (FacebookApiException $e) 
		  {
			print_r("<pre>".$e."</pre>");
		  }
		}

		//$fu->email = $fbme['email'];
		return $fbme;
	}
	
  	public function checkFun()
	{
		$facebook = Yii::app()->params['facebook'];
		$result = $facebook->api(array(
				"method"    => "fql.query",
				"query"     => "SELECT uid FROM page_fan WHERE uid=" . $_SESSION["fbid"] . " AND page_id=" . Yii::app()->params['fbPageID']
			));
		if(count($result)) // array is not empty, so the user is a fan!
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	
  public function filterFacebook($filterChain) 
  {
    header('P3P: CP="CAO PSA OUR"');
    $facebook = Yii::app()->params['facebook'];
	$user = $facebook->getUser();
    
	if( isset($_REQUEST['request_ids']) )
	{
		$_SESSION['request_ids'] = $_REQUEST['request_ids'];
		$rids = $_REQUEST['request_ids'];
		if(strpos($rids, ",")>-1)
		{
			//multi invitation
			$arr = explode(",", $rids);
			$rid = $arr[0];
			$reqs = FRequest::model()->findAll('requestId=:rid', array(':rid'=>$rid));
			if(isset($reqs))
			{
				if(count($reqs)>0)
				{
					$req = $reqs[0];
					$pageContent = file_get_contents('https://graph.facebook.com/' . $req->fbId);
					$parsedJson  = json_decode($pageContent);
					$this->render('invitation', array('id'=>$req->fbId, 'name'=>$parsedJson->name ));		
					Yii::app()->end();
				}
			}
		}
		else
		{
			//one invitation
			$reqs = FRequest::model()->findAll('requestId=:rid', array(':rid'=>$_REQUEST['request_ids']));
			if(isset($reqs))
			{
				if(count($reqs)>0)
				{
					$req = $reqs[0];				
					$pageContent = file_get_contents('https://graph.facebook.com/' . $req->fbId);
					$parsedJson  = json_decode($pageContent);
					$this->render('invitation', array('id'=>$req->fbId, 'name'=>$parsedJson->name ));	
					Yii::app()->end();
				}
			}
		}		
	}
	
    $fbme = null;
    if ($user) 
	{
      try 
	  {
        $uid = $user;//$facebook->getUser();
        $fbme = $facebook->api('/me');
      } 
	  catch (FacebookApiException $e) 
	  {
        print_r("<pre>".$e."</pre>");
      }
    }
	
    if ($fbme) {
	
		$_SESSION["fbid"] = $uid;
		$fu = self::loadUserByFBID($_SESSION["fbid"]);
		
		if( !isset($fu) )
		{
			//user not exists....add new user
			$fu = new FUser();
			$fu->name=$fbme['name'];
			$fu->nick=$fbme['name'];
			$fu->facebookID=$_SESSION["fbid"];
			$fu->first_name=$fbme['first_name'];
			$fu->last_name=$fbme['last_name'];
			$fu->username=$fbme['username'];
			$fu->gender=$fbme['gender'];
			$fu->email=$fbme['email'];
			try 
			{
				$fu ->save();
			} 
			  catch (FacebookApiException $e) 
			  {
				print_r("<pre>".$e."</pre>");
			  }
		}
			
		$identity=new UserIdentity($fu->name,$_SESSION["fbid"]);
		if($identity->authenticate())
			Yii::app()->user->login($identity);
		else
		{
			echo $identity->errorMessage;
			Yii::app()->end();
		}
        // call $filterChain->run() to continue filtering and action execution
        $filterChain->run();
    }
    else {
      $appId = $facebook->getAppId();
      $canvasUrl = $facebook->getCanvasPage();
      // Set the required permissions for the application
      $perms = Yii::app()->params['permissions'];
      $loginUrl = "https://www.facebook.com/dialog/oauth?scope=".$perms.
        "&client_id=".$facebook->getAppId().
        "&redirect_uri=".urlencode($canvasUrl);
      echo("<head><title>redirection</title><script> top.location.href='" . $loginUrl . "'; </script></head>");
      Yii::app()->end();
    }
  }
  
  
	protected function loadRequestsPerDay()
	{
		$req_per_day = 0;
		$reqs = FRequest::model()->findAll('fbId=:facebookID', array(':facebookID'=>$_SESSION["fbid"]));
		if(isset($reqs))
		{
			foreach($reqs as $req)
			{
				$created = $req->created;
				$today = strtotime("+8 hour");
				if( date('Ymd',$today) == date('Ymd', $created) ) 
					$req_per_day +=1;	
			}
		}
		return $req_per_day;
	}
	
    public function loadUserByFBID($facebookID)
	{
		$fu = FUser::model()->find('facebookID=:facebookID', array(':facebookID'=>$facebookID));
		if(isset($fu))
		{
			return $fu;
		}
		return null;
	}
	
	
	public function getFriend($fid)
	{
		if( !isset($_SESSION["friends"]) )
			$_SESSION["friends"] = self::getMyFriends();
			
		$count = count($_SESSION["friends"]);
			   
		for ($i = 0; $i < $count; $i++)
		{
			if( $_SESSION["friends"][$i]['id']==$fid )
				return $_SESSION["friends"][$i];
		}
		return 0;
	}
	
	public function getFriendName($fid)
	{
		if( !isset($_SESSION["friends"]) )
			$_SESSION["friends"] = self::getMyFriends();
			
		$count = count($_SESSION["friends"]);
			   
		for ($i = 0; $i < $count; $i++)
		{
			if( $_SESSION["friends"][$i]['id']==$fid )
				return $_SESSION["friends"][$i]['name'];
		}
		return "";
	}
	
	public function getMyFriends()
	{
		header('P3P: CP="CAO PSA OUR"');
		$facebook = Yii::app()->params['facebook'];
		$user = $facebook->getUser();
		//$session = $facebook->getSession();
		$fbme = null;
		if ($user) 
		{
			try 
			{
				$uid = $user;//$facebook->getUser();
				$fbme = $facebook->api('/me');
				$friends = $facebook->api('/me/friends');
				$li = $friends['data'];
				return $li;
			} 
			catch (FacebookApiException $e) 
			{
				print_r("<pre>".$e."</pre>");
			}
		}
		return null;
	}
	
	public function getMyPendingRequests()
	{
		$reqs = FRequest::model()->findAll('fbId=:facebookID AND accepted=0', array(':facebookID'=>$_SESSION["fbid"]));
		if(isset($reqs))
		{
			return $reqs;
		}
		return null;
	}
	
	public function getMyRequests()
	{
		$reqs = FRequest::model()->findAll('fbId=:facebookID', array(':facebookID'=>$_SESSION["fbid"]));
		if(isset($reqs))
		{
			return $reqs;
		}
		return null;
	}
	
	public function getPendingRequestsToMe()
	{
		$reqs = FRequest::model()->findAll('friendId=:facebookID AND accepted=0', array(':facebookID'=>$_SESSION["fbid"]));
		if(isset($reqs))
		{
			return $reqs;
		}
		return null;
	}
	
	public function delRequest($rid, $fid)
	{
		header('P3P: CP="CAO PSA OUR"');
		$facebook = Yii::app()->params['facebook'];
		$user = $facebook->getUser();
		$delete_success = $facebook->api('/' . $rid.'_'.$fid, 'DELETE');
		return $delete_success;
	}
	

	
	public function getAppDir()
	{
		///home2/plzentv/public_html/fb/hannah/soutez-leto2011/   index.php
		$p = Yii::app()->request->scriptFile;
		$p = substr( $p, 0, strlen($p)-9);
		return $p;
	}
	
	public function publishJQAssets()
	{
		$assets = self::getAppDir() . 'css';
		$baseUrl = Yii::app()->assetManager->publish($assets);	//, false, 0, YII_DEBUG
		
		if(is_dir($assets)){
			$cs = Yii::app()->clientScript;
			$cs->registerCoreScript('jquery');
			$cs->registerScriptFile($baseUrl . '/jquery.fancybox-1.3.2.pack.js', CClientScript::POS_HEAD);
			$cs->registerScriptFile($baseUrl . '/jquery.tooltip.min.js', CClientScript::POS_HEAD);
			$cs->registerScriptFile($baseUrl . '/jquery.mousewheel-3.0.4.pack.js', CClientScript::POS_END);
			$cs->registerScriptFile($baseUrl . '/jquery.easing-1.3.pack.js', CClientScript::POS_END);
			$cs->registerScriptFile($baseUrl . '/jquery.jnplace.js', CClientScript::POS_END);
			$cs->registerCssFile('css/jquery.fancybox-1.3.2.css');
		}
		else 
			throw new Exception(Yii::t('publishJQAssets - Error: Couldn\'t find assets folder to publish.'));
	}
	
	
	
}