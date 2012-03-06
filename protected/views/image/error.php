<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>
<br/>

<table style="width:600px;border:0px;background-color:#fff;">
	<tr>
		<td><h2>Upsss, chyba.<?php echo $code; ?></h2></td>
	<tr>
	<tr>
		<td><?php echo CHtml::encode($message); ?></td>
	<tr>
</table>

<a href='?r=site/index'>Zpět na můj profil</a>