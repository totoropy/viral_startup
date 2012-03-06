<div class="formCfg">

	<?php $form=$this->beginWidget('CActiveForm'); ?>
	<?php echo Chtml::hiddenField("scenario", $model->scenario); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duplicate'); ?>
		<?php echo $form->textField($model,'duplicate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'denied'); ?>
		<?php echo $form->textField($model,'denied'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'submit'); ?>
		<?php echo $form->textField($model,'submit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remove'); ?>
		<?php echo $form->textField($model,'remove'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'accept'); ?>
		<?php echo $form->dropDownList($model, 'accept', array('jpg'=>'jpg', 'png'=>'png', 'gif'=>'gif', 'jpg|png'=>'jpg|png', 'jpg|gif'=>'jpg|gif', 'png|gif'=>'png|gif', 'jpg|png|gif'=>'jpg|png|gif' ), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max'); ?>
		<?php 	$this->widget('zii.widgets.jui.CJuiSlider', array(
			'id'=>'maxSlider',
			'value'=>$model->max,
			'options'=>array(
				'min'=>-1,
				'max'=>100,
				'slide'=>'js:function(event, ui) {
					$("#Cfg_max").val(ui.value);
				}',
			),
			'htmlOptions'=>array(
				'style'=>'height:12px;width:140px;margin-top:4px;float:left; margin-right:12px;'
			),
		));?>
		<?php echo $form->textField($model,'max', array('class'=>'valSlider', 'readonly'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'action'); ?>
		<?php echo $form->textField($model,'action'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','Save')); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->







