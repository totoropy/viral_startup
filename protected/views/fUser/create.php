<?php
$this->breadcrumbs=array(
	'Fusers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FUser', 'url'=>array('index')),
	array('label'=>'Manage FUser', 'url'=>array('admin')),
);
?>

<h1>Create FUser</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>