<?php
$this->breadcrumbs=array(
	'Fusers',
);

$this->menu=array(
	array('label'=>'Create FUser', 'url'=>array('create')),
	array('label'=>'Manage FUser', 'url'=>array('admin')),
);
?>

<h1>Fusers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
