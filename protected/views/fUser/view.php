<?php
$this->breadcrumbs=array(
	'Fusers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List FUser', 'url'=>array('index')),
	array('label'=>'Create FUser', 'url'=>array('create')),
	array('label'=>'Update FUser', 'url'=>array('update', 'id'=>$model->uid)),
	array('label'=>'Delete FUser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->uid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FUser', 'url'=>array('admin')),
);
?>

<h1>View FUser #<?php echo $model->uid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'uid',
		'created',
		'facebookID',
		'name',
		'first_name',
		'last_name',
		'username',
		'password',
		'nick',
		'status',
		'birthday',
		'gender',
		'email',
		'cache',
		'requestId',
	),
)); ?>
