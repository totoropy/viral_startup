<?php

class ImageController extends Controller
{
	//$this->modelClass = 'FImage';  // the data model of this controller
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
	public $pid = "1";
	public $item;
	public $arrItems=array();
	public $rUri;
	
		
	
	
	protected function getFBGalleryDir()
	{
		return self::getAppDir() . 'protected/extensions/FBGallery';
	}

		public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}


	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','detail','list','mail','myimages','uploader','update'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
			
	public function actionList()
	{
		Yii::import('application.extensions.FBGallery.GalleryConfig');
		$this->pageName = "list";
		$this->bgImage = "images/logo32.jpg";
		$this->drawMenu = false;
		
		$this->rUri = Yii::app()->request->requestUri;
		self::publishJQAssets();
		self::publishAssets();
				
		
		$criteria = new CDbCriteria();
		$criteria->condition = "state=0";
		$criteria->order = "votes desc";

		$count=FImage::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize=20;
		$pages->applyLimit($criteria);
		$images = FImage::model()->findAll($criteria);

		$fancyBoxConfig = self::config("fancybox");
		$galleryConfig  = self::config("gallery");
		
		//Yii::app()->params['appBaseUrl'] . 
		$gUrl = $galleryConfig['gFolder'].'/'.$this->pid;
		$thUrl = $gUrl.'/'.$galleryConfig['thumbsDir'].'/';
		$imgsUrl = $gUrl.'/'.$galleryConfig['picturesDir'].'/';
		$overUrl = $gUrl.'/'.$galleryConfig['overviewsDir'].'/';
		
		
		
		foreach($images as $model)
		{
			$m = 0;	//left margin for vertical pic
			if($model->height>$galleryConfig['imgWidth'])
				$m = 35;
			$this->item = array(
				'fbid'=>$model->fbid,
				'id'=>$model->id,
				'title'=>$model->caption,
				'subtitle'=>$model->place.' '.$model->time,
				'votes'=>$model->votes,
				'rel'=>'1',
				'urlImg'=>$imgsUrl.$model->name,
				'imgSrc'=>$thUrl.$model->name,
				'overSrc'=>$overUrl.$model->name,
				'margin'=>$m,
				'fileName'=>$model->name,
			);

			if( $galleryConfig["thTitleShow"] )
			{
				$this->arrItems[$model->name]= $this->renderPartial('item', $this->item, true);
			}
			else
			{
				$this->arrItems[$model->name]= $this->renderPartial('item-notitle', $this->item, true);
			}
		}
		
		$this->render('list', array(
			'pages' => $pages,
		));
		
		self::javascripts($fancyBoxConfig,$galleryConfig);
	}
	
	public function actionMyImages()
	{
		Yii::import('application.extensions.FBGallery.GalleryConfig');
		$this->pageName = "list";
		$this->rUri = Yii::app()->request->requestUri;
		self::publishJQAssets();
		self::publishAssets();
				
		
		$criteria = new CDbCriteria();
		$criteria->order = "votes desc";
		$criteria->condition = "fbid='" . $_SESSION["fbid"] . "' AND state=0";

		$count = FImage::model()->count($criteria);
		$models = FImage::model()->findAll($criteria);
		$galleryConfig  = self::config("gallery");
			
		$gUrl = $galleryConfig['gFolder'].'/'.$this->pid;
		$thUrl = $gUrl.'/'.$galleryConfig['thumbsDir'].'/';
		$imgsUrl = $gUrl.'/'.$galleryConfig['picturesDir'].'/';
		$overUrl = $gUrl.'/'.$galleryConfig['overviewsDir'].'/';
		
		foreach($models as $model)
		{
			$m = 0;	//left margin for vertical pic
			if($model->height>$galleryConfig['imgWidth'])
				$m = 35;
				
			$this->item = array(
				'fbid'=>$model->fbid,
				'id'=>$model->id,
				'title'=>$model->caption,
				'subtitle'=>$model->place.' '.$model->time,
				'votes'=>$model->votes,
				'rel'=>'1',
				'urlImg'=>$imgsUrl.$model->name,
				'imgSrc'=>$thUrl.$model->name,
				'overSrc'=>$overUrl.$model->name,
				'margin'=>$m,
				'fileName'=>$model->name,
			);

			$this->arrItems[$model->name]= $this->renderPartial('myitem', $this->item, true);
		}
		
		if(count($this->arrItems))
		{
			echo "<div id='img-count' style='display:none'>".count($this->arrItems)."</div><div id='img-line' style='width:4000px;'>";
			foreach($this->arrItems as $item)
			{
				echo $item;
			}
			echo "</div>";
		}
		else
			echo Yii::t('app', 'Galerie je prázdná.');

		//$this->renderPartial('myimages', array('models' => $models	));

	}
	
	
	public function actionUploader()
	{
	
		$this->bgImage = "images/logo32.jpg";
		$this->drawMenu = false;
		
		Yii::import('application.extensions.FBGallery.MyFiles');
		Yii::import('application.extensions.FBGallery.Image');
		Yii::import('application.extensions.FBGallery.Uploader');
		Yii::import('application.extensions.FBGallery.GalleryConfig');
		
		$fancyBoxConfig = self::config("fancybox");
		$galleryConfig  = self::config("gallery");
		$uploaderConfig = self::config("uploader");
		
		self::publishJQAssets();
		self::publishAssets();
		
		$siteUrlBase = Yii::app()->request->hostInfo.Yii::app()->baseUrl.'/';
		$gUrl = $siteUrlBase.$galleryConfig['gFolder'].'/'.$this->pid;
		$gPath = self::getAppDir() . $galleryConfig['gFolder'].DIRECTORY_SEPARATOR.$this->pid;
		$originalPath = $gPath.DIRECTORY_SEPARATOR.$galleryConfig['originalDir'].DIRECTORY_SEPARATOR;
		$imgsPath = $gPath.DIRECTORY_SEPARATOR.$galleryConfig['picturesDir'].DIRECTORY_SEPARATOR;
		$thPath = $gPath.DIRECTORY_SEPARATOR.$galleryConfig['thumbsDir'].DIRECTORY_SEPARATOR;
		$ovPath = $gPath.DIRECTORY_SEPARATOR.$galleryConfig['overviewsDir'].DIRECTORY_SEPARATOR;
		$tmpPath = $gPath.DIRECTORY_SEPARATOR.$galleryConfig['tempDir'].DIRECTORY_SEPARATOR;
		$assetUrl = Yii::app()->getAssetManager()->publish( self::getFBGalleryDir() . '/assets').'/';
		


		//if is a new gallery
		if(!file_exists($tmpPath))
		{
			$dirs = array(
				$originalPath, 
				$imgsPath, 
				$thPath,
				$ovPath,
				$tmpPath
			);

			foreach($dirs as $dir){
				MyFiles::NewFolder($dir);
			}
		}
			
		$myfile = new FItem;
		if(isset($_POST['FItem']))
		{
			$myfile->attributes = $_POST['FItem'];
			$myfile->image = CUploadedFile::getInstance($myfile,'image');	//get image

			$valid = $myfile->validate();
			if($valid) 
			{
				if(isset($myfile->image))
				{
					
					$name = str_replace(' ', '_', $myfile->image);		//replace spaces
					$name = str_replace(')', '', $name);		//replace )
					$name = str_replace('(', '', $name);		//replace (
					$name = Yii::app()->session['fbid'] . "_" . $name;
					
					if( file_exists($imgsPath . $name) ) //check if it already exists then rename it
						$name = $name . "_" . Date("His", Time() ) ;	//get file name with timestamp
						
					$name = strtolower($name);
					$myfile->image->saveAs($tmpPath . $name);	//saveAs to tmp
				}
				else
				{
					$this->render('error', array('message'=>'Soubor nebyl nalezen.'));
					Yii::app()->end();
				}
			}
			else
			{
				
				$this->render('error', array('message'=>'Soubor je příliš velký. ->' . $myfile->image->getSize() ));
				Yii::app()->end();
			}
		}
		
		//check if file exists
		if( !file_exists($tmpPath . $name) )
		{
			$this->render('error', array('message'=>'Soubor nebyl uložen.') );
			Yii::app()->end();
		}
			
		$img_orig = new Image($tmpPath . $name);
		$imgW = $img_orig->__get('width');
		$imgH = $img_orig->__get('height');
		if( $imgW<$galleryConfig['imgWidth'] && $imgH<$galleryConfig['imgHeight'])
		{
			$this->render('error', array('message'=>'Fotografie je příliš malá. Rozměry musí být nejméně ' . $galleryConfig['imgWidth'] . 'x' . $galleryConfig['imgHeight'] . ' bodů.') );
			Yii::app()->end();
		}
		
		// if( $imgH<$galleryConfig['imgHeight'] )
		// {
			// $this->render('error', array('message'=>'Fotografie je příliš malá. Rozměry musí být nejméně ' . $galleryConfig['imgWidth'] . 'x' . $galleryConfig['imgHeight'] . ' bodů.') );
			// Yii::app()->end();
		// }
		
		//Save image
		$path = $galleryConfig['gFolder'] . '/1/' . $galleryConfig['picturesDir'] . '/' . $name;
		$img = new FImage;
		$img->name = $name;
		$img->path = $path;
		$img->fbid = Yii::app()->session['fbid'];
		$img->votes = 0;
		$img->save();
		
		
		//$this->resizeAllNew();
		$fromDir = $tmpPath;
		$toDir = $imgsPath;
		$arrFiles = MyFiles::filesFromDir($fromDir,"jpg");

		$imgWidth = $galleryConfig['imgWidth'];
		$thWidth =  $galleryConfig['thWidth'];
		$quality =  $galleryConfig['quality'];
		$sharpen =  $galleryConfig['sharpen'];

		$arrOfTarget = array(
			'images'=> array(
					'toDir'=>$imgsPath,
					'width'=>$imgWidth
					),
			'thumbs'=> array(
					'toDir'=>$thPath,
					'width'=>$thWidth
					),
			'overviews'=> array(
					'toDir'=>$ovPath,
					'width'=>$thWidth*2
					),	
		);


		foreach($arrFiles as $file)
		{
			self::resizeImg($file, $galleryConfig['imgWidth'], $galleryConfig['imgHeight'], $fromDir, $imgsPath,  $quality, $sharpen);
			//Save width+height
			$image = new Image($imgsPath.$file);
			$imgWidth = $image->__get('width');
			$imgHeight = $image->__get('height');
			$img=$this->loadModel($img->id);
			$img->width = $imgWidth;
			$img->height = $imgHeight;
			$img->save();
			
			//create thumb
			self::resizeImg($file, $galleryConfig['thWidth'], $galleryConfig['thHeight'], $fromDir, $thPath,  $quality, $sharpen);
			
			//double size overview when nouse is above thumb
			self::resizeImg($file, $galleryConfig['thWidth']*2, $galleryConfig['thHeight']*2, $fromDir, $ovPath,  $quality, $sharpen); 
		}


		if($galleryConfig['keepOriginal'])
			MyFiles::moveAllFiles($tmpPath, $originalPath);
		
		MyFiles::emptyFolder($fromDir);
		
		if( isset($img) )
		{
			$imageId = $img->id;
			$n = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE imageId=".$imageId);
			$isvoted = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE fbid='".$_SESSION["fbid"]."' AND imageId=".$imageId);
			$this->render('update', array('img'=>$img, 'votes'=>$n, 'isvoted'=>$isvoted) );
		}
		else
		{
			//$this->render('error', "Položka nebyla nalezena. ");
			$this->pageName = "upload";
			$this->render('error', array('message'=>'Image/Uploader: img was not found in db.'));
		}		
	}

	
	public function resizeImg($file, $endWidth, $endHeight, $fromDir, $toDir, $quality=75, $sharpen=20)
	{
		$image = new Image($fromDir.$file);

		$imgWidth = $image->__get('width');
		$imgHeight = $image->__get('height');
		
		$rateW = $imgWidth/$endWidth;	//2.1
		$rateH = $imgHeight/$endHeight; //1.7
		
		
		if( $rateW>$rateH )
		{
			//široký image
			$endWidth = $imgWidth < $endWidth ? $imgWidth : $endWidth; 
			$image->resize($endWidth,  NULL)->quality($quality)->sharpen($sharpen);
		}
		else
		{
			//vysoký image
			$endHeight = $imgHeight < $endHeight ? $imgHeight : $endHeight; 
			$image->resize( NULL, $endHeight)->quality($quality)->sharpen($sharpen);
		}

		$image->save($toDir.$file, 0666, false);
	}
	

	
	
	public function javascripts($fancyBoxConfig,$galleryConfig)
	{
		$jqcfg = CJavaScript::encode($fancyBoxConfig);

		$okButton = $galleryConfig['okButton'];
		$cancelButton = $galleryConfig['cancelButton'];

		$dialogDeleteImage = Yii::t('app', 'Delete');
		$dialogDeleteImageMessage = Yii::t('app','Chcete odstranit obrázek?');

		Yii::app()->clientScript->registerScript("FBGallery", "
			$('.gImg').fancybox($jqcfg);
			editorForTitle('$this->rUri');
			gDialogs(
				'$dialogDeleteImageMessage', 
				'$dialogDeleteImage ', 
				'$okButton', 
				'$cancelButton', 
				'$this->rUri'
			);
		", CClientScript::POS_END);
	}
	


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->pageName = "imageupdate";
		$this->bgImage = "images/logo32.jpg";
		$this->drawMenu = false;
		
		$imageId=(int)$id;
		
		if( isset($_POST['id']) )
			$imageId=(int)$_POST['id'];
		
		self::publishJQAssets();
		
		if( $imageId>0 )
		{
			self::updateViewCount($imageId);
			
			$img=$this->loadModel($imageId);
			if(isset($_POST['FImage']))
			{
				if( $img->fbid==$_SESSION["fbid"] )
				{
					//POST
					$img->caption  = $_POST['FImage']['caption'];
					$img->place    = $_POST['FImage']['place'];
					$img->time     = $_POST['FImage']['time'];
					$img->who = $_POST['FImage']['who'];
					$img->save();
					
					//Yii::app()->user->setFlash('gallery', 'Uloženo.');
					$this->redirect(array('image/view&id='.$img->id));
					Yii::app()->end();
				}
				else
				{
					throw new CHttpException(400, 'Nemáte oprávnění k editaci popisu této fotografie.');
					//$this->render('error', "Nemáte oprávnění k editaci popisu této fotografie.");
					//Yii::app()->end();
				}
			}

			//GET
			if( isset($img) )
			{
				$name = "";
				$fu = self::loadUserByFBID($img->fbid);
				if(isset($fu))
					$name = $fu->nick;
					
				self::updateViewCount($imageId);
				$n = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE imageId=".$imageId);
				$isvoted = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE fbid='".$_SESSION["fbid"]."' AND imageId=".$imageId);
				
				$this->drawMenu = false;
				if( $img->fbid==$_SESSION["fbid"] )
					$this->render('update', array('img'=>$img, 'name'=>$name, 'votes'=>$n, 'isvoted'=>$isvoted) );	
				else
					$this->render('view', array('img'=>$img, 'name'=>$name, 'votes'=>$n, 'isvoted'=>$isvoted) );				
			}
			else
			{
				throw new CHttpException(400, 'Položka nebyla nalezena.');
			}
		}
		else
		{
			$this->redirect(array('site/index'));
		}
	}

	public function updateViewCount($id)
	{
		if( $id>0 )
		{
			$img = $this->loadModel($id);
			$img->views = $img->views + 1;
			$img->save();
		}
	}
	
	public function actionView($id)
	{
		$this->pageName = "imageview";
		$this->bgImage = "images/logo32.jpg";
		
		self::publishJQAssets();
		
		$imageId=(int)$id;
		if( $imageId>0 )
		{
			//load image
			$img = $this->loadModel($imageId);
			if( isset($img) )
			{
				$name = "";
				$fu = self::loadUserByFBID($img->fbid);
				if(isset($fu))
					$name = $fu->nick;
					
				self::updateViewCount($imageId);
				$n = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE imageId=".$imageId);
				$isvoted = Vote::model()->countBySql("SELECT COUNT(*) FROM votes WHERE fbid='".$_SESSION["fbid"]."' AND imageId=".$imageId);
				
				$this->drawMenu = false;
				$this->render('view', array('img'=>$img, 'name'=>$name, 'votes'=>$n, 'isvoted'=>$isvoted, 'req_per_day'=>self::loadRequestsPerDay() )  );
			}
			else
			{
				throw new CHttpException(400, 'Položka nebyla nalezena.');
			}
		}
		else
		{
			throw new CHttpException(400, 'Položka nebyla nalezena.');
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
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		//if(Yii::app()->request->isPostRequest)	// we only allow deletion via POST request
			
		$img = $this->loadModel($id);
		if(isset($img))
		{
			if( $img->fbid==$_SESSION["fbid"] )
			{
				Yii::import('application.extensions.FBGallery.GalleryConfig');
				$galleryConfig  = self::config("gallery");
				$siteUrlBase = Yii::app()->request->hostInfo.Yii::app()->baseUrl.'/';
				$gPath = self::getAppDir() . $galleryConfig['gFolder'] . DIRECTORY_SEPARATOR . $this->pid;
				try{
					$imgsPath = $gPath . DIRECTORY_SEPARATOR.$galleryConfig['picturesDir'].DIRECTORY_SEPARATOR . $img->name;
					if( file_exists($imgsPath) )
						unlink($imgsPath);
				}catch(Exception $e){}
				
				try{
				$thPath = $gPath . DIRECTORY_SEPARATOR.$galleryConfig['thumbsDir'].DIRECTORY_SEPARATOR . $img->name;
					if( file_exists($thPath) )
						unlink($thPath);
				}catch(Exception $e){}
				
				try{
					$ovPath = $gPath . DIRECTORY_SEPARATOR.$galleryConfig['overviewsDir'].DIRECTORY_SEPARATOR . $img->name;
					if( file_exists($ovPath) )
						unlink($ovPath);
				}catch(Exception $e){}
			
				$img->delete();
				$this->redirect(array('site/index'));
				Yii::app()->end();
			}
			else
			{
				throw new CHttpException(400, 'Nemáte oprávnění odstranit položku.');
			}
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('FImage');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FImage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FImage']))
			$model->attributes=$_GET['FImage'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=FImage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='fimage-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

