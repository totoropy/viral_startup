<?php

Yii::import('application.models.Vote');

include "libmail.php";

class AjaxController extends Controller
{
	/**
         * Declares class-based actions.
         */
        public function actions()
        {
        }

		public function actionisvoted()
		{
			$imageId = $_REQUEST['imageId'];
			if( isset($imageId) )
			{
				$n = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE fbid='".$_SESSION["fbid"]."' AND imageId=".$imageId);
				if(isset($n))
					echo $n;
				else
					echo "0";

				Yii::app()->end();
			}
		}

		public function actionValidateMail()
		{
			$model = new MailForm;
			if(isset($_POST['MailForm']))
				$model->attributes=$_POST['MailForm'];
				
			$validator = new CEmailValidator;
			if( $validator->validateValue($model->to) )
				echo "1";
			else
				echo "0";
		}
		
		

		public function actionSendMail()
		{
			$model = new MailForm;
			if(isset($_POST['MailForm']))
				$model->attributes=$_POST['MailForm'];
				
			$validator = new CEmailValidator;
			if( $validator->validateValue($model->to) && $validator->validateValue($model->from) )
			{
				$m = new Mail; // create the mail
				$m->From($model->from);
				$m->To($model->to);
				$m->Subject( Yii::app()->params['appName'] );
				$m->Body($model->body, "utf-8");    

				//$m->Attach ($_SERVER['DOCUMENT_ROOT'] . "/fb/hannah/soutez-o-stan/images/2011_223_0444_POUKAZKA_NA_FB_stan_spacak_triko_03.jpg", "image/jpg", "attachment", "2011_223_0444_POUKAZKA_NA_FB_stan_spacak_triko_03.jpg"); // attach toto.gif file as fun.gif
				$m->Send(); 						
				echo "1";
			}
			else{
				echo "0";
			}
		}
		

		public function actionRequestSent()
		{
			$reqId = $_REQUEST['reqid'];
			$userIds = $_REQUEST['userids'];
			if( isset($reqId) && isset($userIds))
			{
				if( strpos($userIds, ',') )
				{
					$arr = explode(',',$userIds);
					foreach( $arr as $id )
					{
						if(!empty($id))
						{
							$req = new FRequest;
							$req->requestId = $reqId;
							$req->friendId = $id;
							$req->fbId = $_SESSION["fbid"];
							$req->save();	
						}
					}
				}	
				else
				{
					$req = new FRequest;
					$req->requestId = $reqId;
					$req->friendId = $userIds;
					$req->fbId = $_SESSION["fbid"];
					$req->save();	
				}
				echo $reqId . ':' . $userIds;
			}
			else
			{
				echo 'error:' . $reqId . ':' . $userIds;
			}
		}
		
		
		protected function performAjaxValidation($model)
		{
			if(isset($_POST['ajax']) && $_POST['ajax']==='mail-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
		}
		
		public function actionvote()
		{
			$imageId = $_REQUEST['imageId'];
		
			if( isset($imageId) )
			{
				$n = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE fbid='".$_SESSION["fbid"]."' AND imageId=".$imageId);
				if(isset($n) && $n<1)
				{
					//you can vote
					//only when you have not voted yet
					$vote=new Vote;
					$vote->created=new CDbExpression('NOW()');
					$vote->imageId=$_POST['imageId'];
					$vote->fbid=$_SESSION["fbid"];
					$vote->save();
				}
				$n = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE imageId=".$imageId);
				$image = FImage::model()->findByPk($imageId);
				if(isset($image))
				{
					//update count of votes on image
					$image->votes = $n;
					$image->save();
				}
				
				echo $n; // . "&nbsp;hlasů&nbsp;&nbsp;&nbsp;<span>Už jsi hlasoval/a</span>";
				Yii::app()->end();
			}
		}
		
		public function actionNext()
		{
			Yii::import('application.extensions.FBGallery.GalleryConfig');
			//return json data: {'images':[{'id':'41','src':'galleries/1/thumb/12345678_test.jpg'},{'id':'47','src':'galleries/1/thumb/99856_test777.jpg'}]}
			$imageId = $_REQUEST['imageId'];
			if( isset($imageId) )
			{
				$criteria = new CDbCriteria();
				$criteria->condition = "state=0";
				$criteria->order = "votes desc";
				$models = FImage::model()->findAll($criteria);
				$prev = 0;
				$actu = $imageId ;
				$next = 0;
				$thPrev = '';
				$thActu = '';
				$thNext = '';
				
				$galleryConfig = self::config("gallery");
				$siteUrlBase = Yii::app()->request->hostInfo.Yii::app()->baseUrl.'/';
				$gUrl = $siteUrlBase.$galleryConfig['gFolder'].'/1';
				$thUrl = $gUrl.'/'.$galleryConfig['thumbsDir'].'/';
		
				if( $models[0]->id==$imageId  )
				{
					//first
					$next = $models[1]->id;
					$res = array('images'=>array(
								array('id'=>$prev, 'src'=>''),
								array('id'=>$imageId, 'src'=>$thUrl . $models[0]->name),
								array('id'=>$next, 'src'=>$thUrl . $models[1]->name),
								));
					
					echo json_encode($res);
					Yii::app()->end();
				}
				$last = count($models)-1;
				if( $models[$last]->id==$imageId  )
				{
					//last
					$prev = $prev = $models[$last-1]->id;
					$res = array('images'=>array(
								array('id'=>$prev, 'src'=>$thUrl . $models[$last-1]->name),
								array('id'=>$imageId, 'src'=>$thUrl . $models[$last]->name),
								array('id'=>$next, 'src'=>''),
								));
					
					echo json_encode($res);
					Yii::app()->end();
				}
				
				for($i=0;$i<count($models);$i++ )
				{
					$model = $models[$i];
					if( $model->id==$imageId  )
					{
						$prev = $models[$i-1]->id;
						$next = $models[$i+1]->id;
						break;
					}
				}
				$res = array('images'=>array(
								array('id'=>$prev, 'src'=>$thUrl . $models[$i-1]->name),
								array('id'=>$imageId, 'src'=>$thUrl . $models[$i]->name),
								array('id'=>$next, 'src'=>$thUrl . $models[$i+1]->name),
								));
					
				echo json_encode($res);
				Yii::app()->end();
			}
			$res = array('error'=>'Obrázek nebyl nalezen.');
			echo json_encode($res);
			Yii::app()->end();
		}
		

        public function actionIndex() {         
                $this->render('index');
        }


}
