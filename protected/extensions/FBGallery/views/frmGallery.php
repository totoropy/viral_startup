<div class="formCfg">
	<?php $form=$this->beginWidget('CActiveForm'); ?>
	<?php echo Chtml::hiddenField("scenario", $model->scenario); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'imgWidth'); ?>
		<?php 	$this->widget('zii.widgets.jui.CJuiSlider', array(
			'id'=>'imgWidthSlider',
			'value'=>$model->imgWidth,
			'options'=>array(
				'min'=>10,
				'max'=>1280,
				'slide'=>'js:function(event, ui) {
					$("#Cfg_imgWidth").val(ui.value);
				}',
			),
			'htmlOptions'=>array(
				'style'=>'height:12px;width:140px;margin-top:4px;float:left; margin-right:12px;'
			),
		));?>
		<?php echo $form->textField($model,'imgWidth', array('class'=>'valSlider', 'readonly'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'thWidth'); ?>
		<?php 	$this->widget('zii.widgets.jui.CJuiSlider', array(
			'id'=>'thWidthSlider',
			'value'=>$model->thWidth,
			'options'=>array(
				'min'=>10,
				'max'=>1280,
				'slide'=>'js:function(event, ui) {
					$("#Cfg_thWidth").val(ui.value);
				}',
			),
			'htmlOptions'=>array(
				'style'=>'height:12px;width:140px;margin-top:4px;float:left; margin-right:12px;'
			),
		));?>
		<?php echo $form->textField($model,'thWidth', array('class'=>'valSlider', 'readonly'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'quality'); ?>
		<?php 	$this->widget('zii.widgets.jui.CJuiSlider', array(
			'id'=>'qualitySlider',
			'value'=>$model->quality,
			'options'=>array(
				'min'=>0,
				'max'=>100,
				'slide'=>'js:function(event, ui) {
					$("#Cfg_quality").val(ui.value);
				}',
			),
			'htmlOptions'=>array(
				'style'=>'height:12px;width:140px;margin-top:4px;float:left; margin-right:12px;'
			),
		));?>
		<?php echo $form->textField($model,'quality', array('class'=>'valSlider', 'readonly'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sharpen'); ?>
		<?php 	$this->widget('zii.widgets.jui.CJuiSlider', array(
			'id'=>'sharpenSlider',
			'value'=>$model->sharpen,
			'options'=>array(
				'min'=>0,
				'max'=>100,
				'slide'=>'js:function(event, ui) {
					$("#Cfg_sharpen").val(ui.value);
				}',
			),
			'htmlOptions'=>array(
				'style'=>'height:12px;width:140px;margin-top:4px;float:left; margin-right:12px;'
			),
		));?>
		<?php echo $form->textField($model,'sharpen', array('class'=>'valSlider', 'readonly'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'thTitleShow'); ?>
		<?php echo $form->dropDownList($model, 'thTitleShow', array(false=>Yii::t('app', 'Hide title'),true=>Yii::t('app', 'Show title')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keepOriginal'); ?>
		<?php echo $form->dropDownList($model, 'keepOriginal', array(false=>Yii::t('app', 'Remove'),true=>Yii::t('app', 'Keep')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gFolder'); ?>
		<?php echo $form->textField($model,'gFolder'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tempDir'); ?>
		<?php echo $form->textField($model,'tempDir'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'originalDir'); ?>
		<?php echo $form->textField($model,'originalDir'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'thumbsDir'); ?>
		<?php echo $form->textField($model,'thumbsDir'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'picturesDir'); ?>
		<?php echo $form->textField($model,'picturesDir'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'okButton'); ?>
		<?php echo $form->textField($model,'okButton'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cancelButton'); ?>
		<?php echo $form->textField($model,'cancelButton'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','Save')); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->



