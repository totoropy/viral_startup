<hr />

<?php if(Yii::app()->user->hasFlash('succes')): ?><div class="flash-success"><?php echo Yii::app()->user->getFlash('succes'); ?></div><?php endif; ?>
<?php if(Yii::app()->user->hasFlash('error')): ?><div class="flash-error"><?php echo Yii::app()->user->getFlash('error'); ?></div><?php endif; ?>

<div id="defGallery" class="defaultCfg" title="<?php echo Yii::t('app', 'Load default configuration for Gallery');?>"></div>
<div class="toggleSettings toggleDown"><?php echo Yii::t('app', 'Configure Gallery');?></div>
<?php CController::renderPartial('frmGallery', array('model'=>$galleryConfig, 'field'=>'gallery'));?>

<div id="defFancybox" class="defaultCfg" title="<?php echo Yii::t('app', 'Load default configuration for Fancy Box');?>"></div>
<div class="toggleSettings toggleDown"><?php echo Yii::t('app', 'Configure Fancy Box');?></div>
<?php CController::renderPartial('frmFancyBox', array('model'=>$fancyBoxConfig, 'field'=>'fancybox'));?>

<div id="defUploader" class="defaultCfg" title="<?php echo Yii::t('app', 'Load default configuration for Uploader');?>"></div>
<div class="toggleSettings toggleDown"><?php echo Yii::t('app', 'Configure Uploader');?></div>
<?php CController::renderPartial('frmUploader', array('model'=>$uploaderConfig, 'field'=>'uploader'));?>
