<?php

class Cfg extends CFormModel
{
/** Gallery settings */
	public $gFolder;
	public $tempDir;
	public $originalDir;
	public $thumbsDir;
	public $picturesDir;
	public $imgWidth;
	public $thWidth;
	public $quality;
	public $sharpen;
	public $thTitleShow;
	public $keepOriginal;
	public $okButton;
	public $cancelButton;

/** Fancy Box settings */
	public $titlePosition;
	public $easingEnabled;
	public $mouseEnabled;
	public $transitionIn;
	public $transitionOut;
	public $speedIn; 
	public $speedOut; 
	public $overlayShow;

/** Uploader settings */
	public $accept;
	public $title;
	public $duplicate;
	public $denied;
	public $submit;
	public $name;
	public $remove;
	public $max;
	public $action;


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		return array(
			array('gFolder, tempDir, originalDir, thumbsDir, picturesDir, okButton, cancelButton', 'required', 'on'=>'gallery'),
			array('gFolder, tempDir, originalDir, thumbsDir, picturesDir, okButton, cancelButton', 'length', 'max'=>64),
			array('imgWidth, thWidth', 'numerical', 'integerOnly'=>true),
			array('keepOriginal, thTitleShow', 'boolean'),

// 			array('titlePosition, easingEnabled, mouseEnabled', 'required', 'on'=>'fancybox'),
			array('titlePosition, transitionIn, transitionOut', 'length', 'max'=>64),
			array('speedIn, speedOut', 'numerical', 'integerOnly'=>true),
			array('easingEnabled, mouseEnabled, overlayShow', 'boolean'),

			array('title, duplicate, denied, submit', 'required', 'on'=>'uploader'),
			array('title, duplicate, denied, submit, accept', 'length', 'max'=>128),
			array('max', 'numerical', 'integerOnly'=>true),
		);
	}



	public function attributeLabels()
	{
		return array(
			'gFolder' => Yii::t('app', 'Container folder for galleries'),
			'originalDir' => Yii::t('app', 'Folder for original pictures'),
			'picturesDir' => Yii::t('app', 'Folder for large resized images'),
			'tempDir' => Yii::t('app', 'Temp folder for upload'),
			'thumbsDir' => Yii::t('app', 'Folder for thumbs'),
			'imgWidth' => Yii::t('app', 'Width for large images'),
			'thWidth' => Yii::t('app', 'Width for thumbs'),
			'quality' => Yii::t('app', 'Quality image(rec. 75%)'),
			'sharpen' => Yii::t('app', 'Sharpen image(rec. 20%)'),
			'thTitleShow' => Yii::t('app', 'Show title in thumb'),
			'keepOriginal' => Yii::t('app', 'Keep original pictures'),
			'okButton'=>Yii::t('app', 'Text for Ok button'),
			'cancelButton'=>Yii::t('app', 'Text for Cancel button'),

			'easingEnabled'=>Yii::t('app', 'Easing Efect'),
			'mouseEnabled'=>Yii::t('app', 'Use mouse'),
			'titlePosition'=>Yii::t('app', 'Position for image title'),
			'transitionIn'=>Yii::t('app', 'Transition in Efect'),
			'transitionOut'=>Yii::t('app', 'Transition out Efect'),
			'speedIn'=>Yii::t('app', 'Speed in Efect'),
			'speedOut'=>Yii::t('app', 'Speed out Efect'),

			'accept' =>Yii::t('app', 'Accepted files type'),
			'title' => Yii::t('app', 'Uploader title'),
			'duplicate' => Yii::t('app', 'Message for duplicate'),
			'denied' => Yii::t('app', 'Message for denied'),
			'submit' => Yii::t('app', 'Text for submit button'),
			'max' => Yii::t('app', 'Maxim files(-1 unlimited)'),
			'action' => Yii::t('app', 'URL for processing upload'),
		);
	}

	public function defaultGalleryConfig()
	{
		return array(
			'imgWidth'=>700,
			'thWidth'=>200,
			'quality'=>75, 
			'sharpen'=>20,
			'okButton'=> Yii::t('app', 'Ok'),
			'cancelButton'=> Yii::t('app', 'Cancel'),
			'thTitleShow'=>true,
			'keepOriginal'=>false,
			'gFolder'=>'galleries',
// 			'gFolder'=>'images/galleries',
			'tempDir'=>'_tmp',
			'originalDir'=>'original',
			'thumbsDir'=>'thumbs',
			'tempDir'=>'_tmp',
			'picturesDir'=>'pictures',
		);
	}

	public function defaultFancyBoxConfig()
	{
		return array(
			'titlePosition'=>'inside',
			'easingEnabled'=>true,
			'mouseEnabled'=>true,
			'transitionIn'=>'elastic',
			'transitionOut'=>'elastic',
			'speedIn'=>600, 
			'speedOut'=>200, 
			'overlayShow'=>false
		);
	}

	public function defaultUploaderConfig()
	{
		return array(
			'accept'=>'jpg|png|gif',
			'title'=>'Load images',
			'duplicate'=>'Transition in Efect',
			'denied'=>'Invalid type of file',
			'submit'=>'Load',
			'remove'=>'delete16x16.png',
			'max'=>'-1',
			'action' => '',
		);
	}
}
