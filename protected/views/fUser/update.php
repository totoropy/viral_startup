<?php
$this->breadcrumbs=array(
	'Fusers'=>array('index'),
	$model->name=>array('view','id'=>$model->uid),
	'Update',
);

$this->menu=array(
	array('label'=>'List FUser', 'url'=>array('index')),
	array('label'=>'Create FUser', 'url'=>array('create')),
	array('label'=>'View FUser', 'url'=>array('view', 'id'=>$model->uid)),
	array('label'=>'Manage FUser', 'url'=>array('admin')),
);
?>

<h1>Update FUser <?php echo $model->uid; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>