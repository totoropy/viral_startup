<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>


<table style="width:600px;border:0px;background-color:#fff;margin:20px auto auto 100px">
	<tr>
		<td><h2>Upsss, chyba.<?php echo $code; ?></h2></td>
	<tr>
	<tr>
		<td><?php echo CHtml::encode($message); ?></td>
	<tr>
</table>