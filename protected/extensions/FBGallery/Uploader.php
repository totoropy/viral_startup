<?php
class Uploader{

	public function loadUploaderConfiguration()
	{
		$uploaderConfig = GalleryConfig::model()->find(array('condition'=>"type='uploader'"))->config;
		$this->uploaderConfig = unserialize($uploaderConfig);
		
		$galleryConfig = GalleryConfig::model()->find(array('condition'=>"type='gallery'"))->config;
		$this->galleryConfig = unserialize($galleryConfig);
		
		
		
	}

	public function maxLenghtUploader()
	{
		if($this->uploaderConfig['max'] != '-1')
		{
			$filesInGallery = count($this->imgsOrder);
			if($filesInGallery >= intval($this->uploaderConfig['max']))
				return intval('0');
			else
				return intval($this->uploaderConfig['max']) - $filesInGallery;
		}

		return $this->uploaderConfig['max'];
	}

	public function preUpload()
	{
		if(Yii::app()->user->isGuest)
			return false;

		//if is a new gallery
		if(!file_exists($this->tmpPath))
			self::createFoldersStructure();

		//we resize original pictures and move to image/thumbs folders, then remove originals to save disk space
		if(self::uploadFiles($this->tmpPath, Yii::app()->session['fbid'] ))
			$this->resizeAllNew();
	}

	public function createFoldersStructure()
	{
		$dirs = array(
			$this->originalPath, 
			$this->imgsPath, 
			$this->thPath,
			$this->tmpPath
		);

		foreach($dirs as $dir){
			MyFiles::NewFolder($dir);
		}
	}

	public function uploadFiles($path, $fbid)
	{
		if(isset($_FILES["uploader"]))
		{
			//limit to maxim accepted if not unlimited
			$max = self::maxLenghtUploader();
			$max = $max == '-1' ? count($_FILES["uploader"]['name']): $max;
$max = 1;
			for($i=0; $i < $max; $i++)
			{
				if ($_FILES["uploader"]["error"][$i] == UPLOAD_ERR_OK) 
				{
					$tmp_name = $_FILES["uploader"]["tmp_name"][$i];
					$name = $fbid . "_" . MyFiles::cleanFileName($_FILES["uploader"]["name"][$i]);
					$my_path = $path.DIRECTORY_SEPARATOR.$name;
					move_uploaded_file($tmp_name, $my_path);
				}
				else
					throw new Exception(Yii::t('app', 'Error: Couldn\'t upload files! Please check permissions.'));
			}
			self::save_foto($name, $fbid);
			return true;
		}
	}
	
	public function save_foto( $name, $fbid)
	{
		$path = $this->galleryConfig['gFolder'] . '/1/' . $this->galleryConfig['picturesDir'] . '/' . $name;
		$model = new FImage;
		$model->name = $name;
		$model->path = $path;
		$model->fbid = $fbid;
		$model->votes = 0;
		$model->save();

		//$sql = "INSERT INTO images(name,path,fbid,votes) VALUES('$name','$path','$fbid',0);";
		//$connection=Yii::app()->db;
		//$command=$connection->createCommand($sql);
		
		
		//$connection->active=true;
		//$command->execute();

		//$connection->active=false;  // close connection
	}
	
}
?>