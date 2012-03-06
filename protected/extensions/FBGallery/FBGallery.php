<?php
	/**
	* FBGallery class file.
	*
	* @author Ovidiu Pop <matricks@webspider.ro>
	* @link http://www.webspider.ro/
	* @copyright Copyright &copy; 2010 Ovidiu Pop
	* Dual licensed under the MIT and GPL licenses:
	* http://www.opensource.org/licenses/mit-license.php
	* http://www.gnu.org/licenses/gpl.html
	*
	* FBGallery
	* - add gallery in a page using fancy box plugin from http://fancybox.net
	* - authenticated user can upload new pictures, delete, rename and sort existing pictures
	* - control panel permit to admin to configure gallery, fancybox and uploader settings.
	*
	* To use FBGallery:
	* Extract archive in your extensions folder
	* Add to your database two tables (see sql/fbgallery.sql)
	*
	* Create folder for your galleries and give write permissions - default to /galleries
	* To include a gallery to a page:
	* for static pages, or pages without id, use next statement:
	* <?php $this->widget('application.extensions.FBGallery.FBGallery', array('pid'=>'1'));?>
	* where pid will be an UNIQUE id for each gallery
	*
	* for pages with id, use next statement
	* <?php $this->widget('application.extensions.FBGallery.FBGallery');?>
	*
	*/

Yii::import('application.extensions.FBGallery.Gallery');
Yii::import('application.extensions.FBGallery.GalleryConfig');

class FBGallery extends CWidget
{
	/** 
	* @var int $pid - identifier for gallery based on page id or unique identifier set in widget params
	*/
	public $pid;

	/** 
	* @var int $thOnLine - number of thumbnails to be in a row
	*/
	public $thOnLine = 4;

	/** 
	* @var int $idPrefix - id prefix for thumbnails (for valide id format);
	*/
	protected $idPrefix = 'fbg_';

	/** @var obj $gmodel - model for gallery*/
	public $gmodel;

	/** @var array $imgsOrder - order of pictures based on sort*/
	public $imgsOrder;

	/** @var array $galleryConfig - settings for gallery*/
	public $galleryConfig;

	/** @var array $fancyBoxConfig - settings for fancybox*/
	public $fancyBoxConfig;

	/** @var array $uploaderConfig - settings for uploader*/
	public $uploaderConfig;

	/** @var string $sitePathBase - basePath */
	public $sitePathBase;

	/** @var string $siteUrlBase - baseUrl */
	public $siteUrlBase;

	/** @var string $gPath - basePath to gallery folder */
	public $gPath;

	/** @var string $gUrl - baseUrl to gallery folder */
	public $gUrl;

	/** @var string $assetUrl - url to assets folder */
	public $assetUrl;

	/** @var string $originalPath - path to folder where we keep(if set) original(unresized) pictures */
	public $originalPath;

	/** @var string $thPath - path to folder where we keep thumbnails */
	public $thPath;

	/** @var string $imgsPath - path to folder where we keep resized pictures */
	public $imgsPath;

	/** @var string $tmpPath - path to folder where we upload pictures before resize. */
	public $tmpPath;

	/** @var array $arrItems - wrapper for our thumbnails. */
	public $arrItems=array();

	/** @var array $item - params for a picture. */
	public $item;

	/** @var string $pageTitle - page title used to create a rel in gallery. 
	* this must be set in function of site settings to reflect real title metatag - usefull for SEO
	*/
	public $pageTitle;
	
	public $pageName;

	/** @var string $okButton - string for OK button in dialogs. */
	public $okButton;

	/** @var string $cancelButton - string for Cancel button in dialogs. */
	public $cancelButton;

	/** @var string $userType - what kind of user is. 
	* visitor - unauthenticated - see pictures
	* editor - authenticated user - upload, delete, rename, sort
	* admin - authenticated user as admin - upload, delete, rename, sort, load default configurations, change actuall configurations
	*/
	public $userType;

	/** @param string $rUri - actuall page url. */
	public $rUri;

	/**
	 * Sets @var $this->rUri.
	 * import files according with $this->userType
	 */
	public function init()
	{
		$this->rUri = Yii::app()->request->requestUri;
		if( isset($_GET["r"]) )
		{
			if( $_GET["r"]=="image/newfoto" )
				$this->pageName = "newfoto";
				
			if( $_GET["r"]=="site/gallery" )
				$this->pageName = "gallery";
		}
		
		$this->setUserType();

		if($this->userType === 'editor' || $this->userType === 'admin' )
		{
			Yii::import('application.extensions.FBGallery.MyFiles');
			Yii::import('application.extensions.FBGallery.Image');
			Yii::import('application.extensions.FBGallery.Uploader');
		}

		if($this->userType === 'admin')
		{
			Yii::import('application.extensions.FBGallery.Cfg');
			Yii::import('application.extensions.FBGallery.FBAdmin');
		}
		
		Yii::import('application.models.FImage');
	}

	/**
	 * Sets @var $this->userType.
	 */
	private function setUserType(){
		if(Yii::app()->user->isGuest)
			$this->userType = 'visitor';

		if(!Yii::app()->user->isGuest)
			$this->userType = 'editor';

		if(!Yii::app()->user->isGuest && Yii::app()->user->name ==='admin')
			$this->userType = 'admin';
	}


	public function run()
	{
		$this->preparations();
		$this->loadGallery();
		$this->loadConfigurations();
		$this->publishAssets();
		$this->preparePaths();
		$this->processPosts();
		$this->renderer();
		$this->javascripts();
	}

	/**
	 * Sets @var $this->pid.
	 * Sets @var $this->pageTitle.
		* @note: if is a static page, must be set an uniqid in widget and this will be used as id
		* if page is based on id, we will use it's id for identify
		* we will set pid to page if where gallery is used.
		* @note:to get a rel for gallery, here will be used propper way to get title metatag of current page
		* changing $this->pageTitle = $this->owner->pageTitle with some like:
		* example: $this->pageTitle = $this->owner->metas->title;
	 */
	private function preparations()
	{
		/** actuall page id from link */
		$pid = "1";//$_GET["id"];
		$this->pid = $this->pid ? $this->pid : $pid;

		$this->pageTitle = $this->owner->pageTitle;
		// $this->pageTitle = $this->owner->metas->title;
	}

	
	/**
	 * Load model for our gallery.
	 * Sets @var $this->gmodel.
	 * Sets order of pictures
	 * Sets @var $this->imgsOrder.
	*/
	public function loadGallery()
	{
		$this->gmodel = Gallery::model()->find(array('condition'=>"pid='$this->pid'"));
		$this->imgsOrder = unserialize($this->gmodel->imgsOrder);
	}


	/**
	 * Load configurations.
	 * Sets @var $this->fancyBoxConfig.
	 * Sets @var $this->galleryConfig.
	*/
	public function loadConfigurations()
	{
		$fancyBoxConfig = GalleryConfig::model()->find(array('condition'=>"type='fancybox'"))->config;
		$this->fancyBoxConfig = unserialize($fancyBoxConfig);

		$galleryConfig = GalleryConfig::model()->find(array('condition'=>"type='gallery'"))->config;
		$this->galleryConfig = unserialize($galleryConfig);

		if($this->userType === 'editor' || $this->userType === 'admin')
		Uploader::loadUploaderConfiguration();
	}


	/**
	 * Publish files for assets.
	 * @throws CException if the assets folder doesn't exists
	*/	
	public function publishAssets()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);

		if(is_dir($assets)){
			Yii::app()->clientScript->registerCoreScript('jquery');
			Yii::app()->clientScript->registerCssFile($baseUrl . '/fbgallery.css');
			Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fancybox-1.3.2.css');

			Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fancybox-1.3.2.pack.js', CClientScript::POS_END);
		
			if ($this->fancyBoxConfig['mouseEnabled'])
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.mousewheel-3.0.4.pack.js', CClientScript::POS_END);
			if ($this->fancyBoxConfig['easingEnabled'])
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.easing-1.3.pack.js', CClientScript::POS_END);

			if(!Yii::app()->user->isGuest)
			{
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.jnplace.js', CClientScript::POS_END);
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/fbgallery.js', CClientScript::POS_END);
				Yii::app()->clientScript->registerScript('startGallery' , 'startGallery()', CClientScript::POS_READY);
			}
		} 
		else 
			throw new Exception(Yii::t('FBGallery - Error: Couldn\'t find assets folder to publish.'));
	}

	/**
	 * Prepare usuals paths and urls.

	 * Sets @var $this->sitePathBase.
	 * /home/user/public_html/site/

	 * Sets @var $this->siteUrlBase.
	 * http://localhost/site

	 * Sets @var $this->gUrl.
	 * http://localhost/site/{gallery folder}/{gallery pid}

	 * Sets @var $this->gPath.
	 * /home/user/public_html/site/{gallery folder}/{gallery pid}

	 * Sets @var $this->originalPath.
	 * /home/user/public_html/site/{gallery folder}/{gallery pid}/{original folder}

	 * Sets @var $this->imgsPath.
	 * /home/user/public_html/site/{gallery folder}/{gallery pid}/{pictures folder}

	 * Sets @var $this->thPath.
	 * /home/user/public_html/site/{gallery folder}/{gallery pid}/{thumbnails folder}

	 * Sets @var $this->tmpPath.
	 * /home/user/public_html/site/{gallery folder}/{gallery pid}/{temporary folder}

	 * Sets @var $this->assetUrl.
	 * path to asset directory
	*/
	private function preparePaths()
	{	
		$this->sitePathBase = dirname(__FILE__).'/../../../';
		$this->siteUrlBase = Yii::app()->request->hostInfo.Yii::app()->baseUrl.'/';
		$this->gUrl = $this->siteUrlBase.$this->galleryConfig['gFolder'].'/'.$this->pid;
		$this->gPath = $this->sitePathBase.$this->galleryConfig['gFolder'].DIRECTORY_SEPARATOR.$this->pid;
		$this->originalPath = $this->gPath.DIRECTORY_SEPARATOR.$this->galleryConfig['originalDir'].DIRECTORY_SEPARATOR;
		$this->imgsPath = $this->gPath.DIRECTORY_SEPARATOR.$this->galleryConfig['picturesDir'].DIRECTORY_SEPARATOR;
		$this->thPath = $this->gPath.DIRECTORY_SEPARATOR.$this->galleryConfig['thumbsDir'].DIRECTORY_SEPARATOR;
		$this->tmpPath = $this->gPath.DIRECTORY_SEPARATOR.$this->galleryConfig['tempDir'].DIRECTORY_SEPARATOR;
		$this->assetUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets').'/';

	}

	/**
	 * Register javascript cores.
	 * dialog ok buttons
	 * Sets @var $this->okButton.
	 * dialog cancel buttons
	 * Sets @var $this->cancelButton.
	 * Sets #dialogDeleteImage title and message
	 * Call javascript function to ready
	*/
	public function javascripts()
	{
		$jqcfg = CJavaScript::encode($this->fancyBoxConfig);

		if($this->userType === 'visitor') 
			Yii::app()->clientScript->registerScript(__CLASS__, "$('.gImg').fancybox($jqcfg);");

		if($this->userType === 'editor' || $this->userType === 'admin' )
		{
			$this->okButton = $this->galleryConfig['okButton'];
			$this->cancelButton = $this->galleryConfig['cancelButton'];

			$dialogDeleteImage = Yii::t('app', 'Delete');
			$dialogDeleteImageMessage = Yii::t('app','Do you delete <strong>xxxxx</strong> image?<br />Will be removed from harddisk!');

			Yii::app()->clientScript->registerScript(__CLASS__, "
				$('.gImg').fancybox($jqcfg);
				editorForTitle('$this->rUri');
				gDialogs(
					'$dialogDeleteImageMessage', 
					'$dialogDeleteImage ', 
					'$this->okButton', 
					'$this->cancelButton', 
					'$this->rUri'
				);
			");
		}
	}

	/**
	* Processate POST and FILES
	*/
	private function processPosts()
	{
		if($this->userType === 'admin')
		{
			if( isset($_POST['loadDefaultConfig'])) FBAdmin::loadDefaultConfig();
			if(isset($_POST['Cfg'])) FBAdmin::updateGalleryConfig();
		}

		if($this->userType === 'editor' || $this->userType === 'admin')
		{
			if(isset($_POST['deleteImg']) && isset($_POST['gImg'])) $this->deleteImage();
			if(isset($_POST['newImgOrder'])) $this->newSort();
			if(isset($_POST['function']) && $_POST['function'] === 'renameItem') $this->renameItem();
			if(isset($_FILES['uploader'])) Uploader::preUpload();
		}
	}


	/**
	* Sets @var $this->arrFiles with existing pictures
	* Render according to userType
	*/
	private function renderer()
	{
		//$this->arrFiles();
		//$this->render('view');
		//$this->render('uploader', array('max'=>Uploader::maxLenghtUploader()));
		
		$images = FImage::model()->findAll(array('order'=>'votes DESC'));
		//$order = $this->imgsOrder;
		$rel = preg_replace('!\-+!', '-', preg_replace("/[^a-z0-9-_.]/", "-", strtolower($this->pageTitle)));
		$text_field = ' text_field';
		$thTitleShow = true;
		$deleteIcon = '';
		if($this->userType === 'admin')
			$deleteIcon = '<img src="'.$this->assetUrl.'delete16x16.png" alt="X" />';
			
		$thUrl = $this->gUrl.'/'.$this->galleryConfig['thumbsDir'].'/';
		$imgsUrl = $this->gUrl.'/'.$this->galleryConfig['picturesDir'].'/';

		if(Yii::app()->user->isGuest){
			$text_field = '';
			$thTitleShow = $this->galleryConfig['thTitleShow'];
			$deleteIcon = '';
		}

		
		foreach($images as $img)
		{
			$this->item = array(
				'id'=>$img->id,
				'votes'=>$img->votes,
				'fbid'=>$img->fbid,
				'thTitleShow'=>$thTitleShow,
				'title'=>$img->caption,
				'text_field'=>$text_field,
				'deleteIcon'=>$deleteIcon,
				'rel'=>$rel,
				'urlImg'=>$imgsUrl.$img->name,
				'imgSrc'=>$thUrl.$img->name,
				'fileName'=>$img->name,
			);

			$this->arrItems[$img->name]= $this->render('_item', array(), true);
		}
		
		
		
		if($this->userType === 'visitor')
		{
			if( $this->pageName=="gallery" )
				$this->render('view');
		}

		if($this->userType === 'editor' || $this->userType === 'admin')
		{
			if( $this->pageName=="gallery" )
				$this->render('viewEditor');
				
			if( $this->pageName=="newfoto" )
				$this->render('uploader', array('max'=>Uploader::maxLenghtUploader()));
				
			//$this->renderEditor();
		}

		if($this->userType === 'admin')
		{
			FBAdmin::cpanel();
		}
	}

	/**
	* Render for editor
	* Render uploader formular
	*/
	public function renderEditor()
	{
		$this->render('viewEditor');
		$this->render('uploader', array('max'=>Uploader::maxLenghtUploader()));
	}

	/**
	* This method delete a picture from gallery.
	* First, remove from db, then from hard
	* If successfully remove, will reload new order in $this->imgsOrder
	*/
	public function deleteImage()
	{
		$folds = array($this->originalPath,$this->imgsPath, $this->thPath);
		$allDeletedSuccessfully = true;
		$pImg = $_POST['gImg'];
		$newOrder = array();

		foreach($this->imgsOrder as $fName => $title)
			if($fName !== $pImg) $newOrder[$fName] = $title;

		foreach($folds as $folder)
			if(!MyFiles::deleteFile($folder, $pImg)) $allDeletedSuccessfully = false;


		if($allDeletedSuccessfully)
			$this->updateOrder($newOrder);
	}	

	/**
	* This method create model according with active scenario.
	* @param string $type - name of scenario
	*/
	public function cfgModel($type)
	{
		$attributes = unserialize(GalleryConfig::model()->find(array('condition'=>"type='$type'"))->config);
		$model = new Cfg($type);

		foreach($model as $attr => $value)
		{
			if(array_key_exists($attr, $attributes))
				$model->$attr = $attributes[$attr];
			else
				unset($model->$attr);
		}

		return $model;
	}

	/**
	* This method populate $this->arrItems with thumbs.
	* If no files exists in gallery, will @return false
	* ' text_field' is identifier class to add jnplace to title (for rename)
	* even if for visitor thTitleShow is set to false, this must be true for editor and admin - (sort and rename)
	
	*/
	private function arrFiles()
	{
		if(!$this->imgsOrder) return false;

		$order = $this->imgsOrder;
		$rel = preg_replace('!\-+!', '-', preg_replace("/[^a-z0-9-_.]/", "-", strtolower($this->pageTitle)));
		$text_field = ' text_field';
		$thTitleShow = true;
		$deleteIcon = '<img src="'.$this->assetUrl.'delete16x16.png" alt="X" />';
		$thUrl = $this->gUrl.'/'.$this->galleryConfig['thumbsDir'].'/';
		$imgsUrl = $this->gUrl.'/'.$this->galleryConfig['picturesDir'].'/';

		if(Yii::app()->user->isGuest){
			$text_field = '';
			$thTitleShow = $this->galleryConfig['thTitleShow'];
			$deleteIcon = '';
		}

		foreach($order as $fName => $title)
		{
			$this->item = array(
				'thTitleShow'=>$thTitleShow,
				'title'=>$title,
				'text_field'=>$text_field,
				'deleteIcon'=>$deleteIcon,
				'rel'=>$rel,
				'urlImg'=>$imgsUrl.$fName,
				'imgSrc'=>$thUrl.$fName,
				'fileName'=>$fName,
			);

			$this->arrItems[$fName]= $this->render('_item', array(), true);
		}
	}
	/** This method is based on image class from Kohana framework
	* Will resize an image, using next parameters:
	* @param string $file - filename to be resized
	* @param integer $width - width for resize
	* @param string $fromDir - folder where image is
	* @param string $toDir - folder where image will be moved after resize
	* @param integer $quality - quality to be used at resize. Default 75
	* @param integer $sharpen - sharpen to be used at resize. Default 20
	*/
	public function resizeImg($file, $width, $fromDir, $toDir, $quality=75, $sharpen=20)
	{
		$image = new Image($fromDir.$file);

		$imgWidth = $image->__get('width');
		$width = $imgWidth < $width ? $imgWidth : $width; 

		$image->resize($width,  NULL)->quality($quality)->sharpen($sharpen);
		$image->save($toDir.$file, 0666, false);
	}

	/** This method is called when all files are uploaded
	* Will resize all pictures from temporary folder
	* If keepOriginal is set to true, after resize, all files are moved to folder where we keep originals 
	* If keepOriginal is set to false, all pictures will be removed from temporary folder 
	*/
	public function resizeAllNew()
	{
			$fromDir = $this->tmpPath;
			$toDir = $this->imgsPath;
			$arrFiles = MyFiles::filesFromDir($fromDir);

			$imgWidth = $this->galleryConfig['imgWidth'];
			$thWidth =  $this->galleryConfig['thWidth'];
			$quality =  $this->galleryConfig['quality'];
			$sharpen =  $this->galleryConfig['sharpen'];

			$arrOfTarget = array(
				'images'=> array(
						'toDir'=>$this->imgsPath,
						'width'=>$imgWidth
						),
				'thumbs'=> array(
						'toDir'=>$this->thPath,
						'width'=>$thWidth
						),
			);

			foreach($arrOfTarget as $type => $target){
				foreach($arrFiles as $file)
				{
					$this->resizeImg($file, $target['width'], $fromDir, $target['toDir'],  $quality, $sharpen);
				}
			}

		$this->addImagesToDB($arrFiles);

		if($this->galleryConfig['keepOriginal'])
			MyFiles::moveAllFiles($this->tmpPath, $this->originalPath);
		
		MyFiles::emptyFolder($fromDir);
	}

	/** This method is used to prepare new order of pictures before save it 
	*/
	private function newSort(){
		$n = $_POST['newImgOrder'];
		$newOrder = array();
		foreach(explode(",", $n) as $fName){
			$newOrder[$fName] = $this->imgsOrder[$fName];
		}
		$this->updateOrder($newOrder);
	}

	/** This method is used to save new order of pictures 
	* @param array $order - array list with order to be saved
	* Before save, the list is serialized
	* After save, the new order is loaded as array in $this->imgsOrder
	*/
	private function updateOrder($order)
	{
		$order = serialize($order);
		$record = $this->gmodel;

		if($record === null){
			$record = new Gallery;
			$record->pid = $this->pid;
			$record->imgsOrder = $order;
			if(!$record->save())
				throw new Exception('FBGallery - '.Yii::t('app', 'Error: I can\'t save to database!'));

		}else{
			$attributes = array("imgsOrder"=> $order);
			$record->saveAttributes($attributes);
		}
		//reload image's order
		$this->imgsOrder = unserialize($order);
	}

	/**  This method is used to prepare and add pictures list from Database
	* @param array $arrFiles - contain picture's filename 
	* After database update, $this->imgsOrder is updated too
	*/
	public function addImagesToDB($arrFiles)
	{
		$arrImgs = array();
		foreach($arrFiles as $fName){
			$arrImgs[$fName] = MyFiles::class2name(MyFiles::RemoveExtension($fName),false,true);
		}

		if($this->imgsOrder)
			$this->updateOrder(array_merge($this->imgsOrder, $arrImgs));
		else
			$this->updateOrder($arrImgs);
	}

	/** This method is used to rename pictures
	* Names of pictures aren't same as filename
	* Names of pictures appear as title for pictures if thTitleShow is set to true
	*/
	public function renameItem()
	{
		$actualName = substr($_POST['arg1'], strlen($this->idPrefix));
		$fName = MyFiles::cleanFileName($actualName);
		$newName = MyFiles::cleanItemTitle($_POST['arg2']);
		$this->imgsOrder[$fName] = $newName;
		$this->updateOrder($this->imgsOrder);
	}

	/** only for debugging */
	public function pre($var){
		echo '<pre>';
		CVarDumper::dump($var);die();
		echo '</pre>';

	}
}