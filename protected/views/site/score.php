<style>
#score td{vertical-align:middle;}
</style>
<?php
$url =  'https://'.Yii::app()->request->getServerName() . Yii::app()->request->url; //
?>
<div style="width:523px;height:99%;padding:4px;margin:25px 25%;background-color:#fff;border:0px">
<div id="score" style="width:500px;height:99%;padding:10px;margin:0px;background-color:#fff;border:solid 1px #dddddd">

<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider' => $arrayDataProvider,
		'summaryText' => 'zobrazeno {start} - {end} ze {count} záznamů',
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridView.css'),
		'columns' => array(
			array(
				'name' => '',
				'type' => 'raw',
				'value' => 'CHtml::encode($data["id"])'
			),
			array(
				'name' => 'jméno',
				'type' => 'raw',
				'value' => 'CHtml::link( CHtml::image("https://graph.facebook.com/".$data["fbid"]."/picture?type=square","",array("width"=>"25","height"=>"25","style"=>"vertical-align:middle;margin-right:4px")) . CHtml::encode($data["name"]), CHtml::encode("http://www.facebook.com/profile.php?id=".$data["fbid"]), array("target"=>"_blank") )',
			),
			array(
				'name' => 'body',
				'type' => 'raw',
				'value' => 'CHtml::encode($data["points"])'
			),
		),
	));
?>



<div style="margin-top:100px;border:0px;text-align:center;width:500px;padding:4px;background-color:#fff">
	<?php 
		echo "<div class='clearfix'></div>";
		$this->widget('CLinkPager', array( 'pages' => $pages,));
	?>
</div>

<div>
<div>
<div id="fb-root"></div><script src="<?php echo Yii::app()->params['appBaseUrl']; ?>css/all.js"></script>
<script type="text/javascript">

   FB.init({
	 appId  : '<?= Yii::app()->params['appid'] ?>',
	 status : true, // check login status
	 cookie : true, // enable cookies to allow the server to access the session
	 xfbml  : true  // parse XFBML
   });
   FB.Canvas.setSize({ height: 1200 });
</script>
