<div class="formCfg">

	<?php $form=$this->beginWidget('CActiveForm'); ?>
	<?php echo Chtml::hiddenField("scenario", $model->scenario); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'titlePosition'); ?>
		<?php echo $form->dropDownList($model, 'titlePosition', array('inside'=>Yii::t('app', 'Inside'),'outside'=>Yii::t('app', 'Outside'),'over'=>Yii::t('app', 'Over')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'easingEnabled'); ?>
		<?php echo $form->dropDownList($model, 'easingEnabled', array(0=>Yii::t('app', 'Without efect'),1=>Yii::t('app', 'With efect')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mouseEnabled'); ?>
		<?php echo $form->dropDownList($model, 'mouseEnabled', array(0=>Yii::t('app', 'Without mouse support'),1=>Yii::t('app', 'With mouse support')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transitionIn'); ?>
		<?php echo $form->dropDownList($model, 'transitionIn', array('none'=>Yii::t('app', 'Without efect'),'elastic'=>Yii::t('app', 'Elastic'),'fade'=>Yii::t('app', 'Fade')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transitionOut'); ?>
		<?php echo $form->dropDownList($model, 'transitionOut', array('none'=>Yii::t('app', 'Without efect'),'elastic'=>Yii::t('app', 'Elastic'),'fade'=>Yii::t('app', 'Fade')), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'speedIn'); ?>
		<?php echo $form->dropDownList($model, 'speedIn', array(100=>'100', 200=>'200', 300=>'300', 400=>'400', 500=>'500', 600=>'600', 700=>'700', 800=>'800', 900=>'900'), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'speedOut'); ?>
		<?php echo $form->dropDownList($model, 'speedOut', array(100=>'100', 200=>'200', 300=>'300', 400=>'400', 500=>'500', 600=>'600', 700=>'700', 800=>'800', 900=>'900' ), array()); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'overlayShow'); ?>
		<?php echo $form->dropDownList($model, 'overlayShow', array(0=>Yii::t('app', 'Without efect'),1=>Yii::t('app', 'With efect')), array()); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','Save')); ?>
	</div>


	<?php $this->endWidget(); ?>

</div><!-- form -->







