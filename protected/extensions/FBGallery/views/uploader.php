	<div class="toggleUploader toggleDown"><?php echo Yii::t('app', 'Vybrat fotografii');?></div>
	<div id="fbguploader" class="fbguploader">
		<div class="uploaderTitle"><?php echo $this->uploaderConfig['title']; ?></div>

		<div class="form">
			<?php echo CHtml::beginForm($this->uploaderConfig['action'], 'post', array('enctype'=>'multipart/form-data'));?>   
			<?php $this->widget('CMultiFileUpload', 
						array(
							'name'=>'uploader',
							'max'=>$max,
							'accept'=>$this->uploaderConfig['accept'],
							'duplicate'=>Yii::t('app', $this->uploaderConfig['duplicate']),
							'denied'=>Yii::t('app', $this->uploaderConfig['denied']),
							'remove'=>'<img src="'.$this->assetUrl.$this->uploaderConfig['remove'].'" height="16" width="16" alt="x" />',
							'selected'=>'ai ales o poze',
							'options'=>array(
								  'afterFileSelect'=>'function(e, v, m){ $("#uploader_F1").attr("disabled","disabled")  }',
								  ),
						)
				);?>
			<div class="row"><?php echo CHtml::submitButton(Yii::t('app', $this->uploaderConfig['submit']));?></div>
		</form>
		</div>
	</div>


