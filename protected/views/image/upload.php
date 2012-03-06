<?php
$this->pageTitle=Yii::app()->name . ' - Vložit fotku';
$this->pageName="upload";
$this->breadcrumbs=array(
	'Vložit fotku',
);
?>

<div style="width:510px;height:510px;padding:4px;margin:25px 25%;background-color:#fff;border:0px">
<div style="width:500px;height:500px;padding:4px;margin:0px;background-color:#fff;border:solid 1px #dddddd">
<br/>
<h2>Nahraj fotku</h2>

<?php if(Yii::app()->user->hasFlash('upload')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('upload'); ?>
	</div>
<?php endif; ?>



	<div id="fbguploader" class="fbguploader" style="display:block;margin: 0px 10%;text-align:left;">
		<div class="uploaderTitle"></div>

		<div class="form">
			<?php echo CHtml::beginForm($uploaderConfig['action'], 'post', array('enctype'=>'multipart/form-data'));?>   
			<?php $this->widget('CMultiFileUpload', 
						array(
							'name'=>'uploader',
							'max'=>$max,
							'accept'=>$uploaderConfig['accept'],
							'duplicate'=>Yii::t('app', $uploaderConfig['duplicate']),
							'denied'=>Yii::t('app', $uploaderConfig['denied']),
							'remove'=>'<img src="'.$assetUrl.$uploaderConfig['remove'].'" height="16" width="16" alt="x" />',
							'selected'=>'ai ales o poze'
						)
				);?>
			<div class="row"><?php echo CHtml::submitButton(Yii::t('app', $uploaderConfig['submit']));?></div>
		</form>
		</div>
	</div>

</div>
</div>
