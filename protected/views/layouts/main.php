<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" lang="cz">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="cz" />
	<meta property="fb:app_id" content="<?php echo Yii::app()->params['appid']; ?>"/>

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fb-buttons.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
			
	<title><?php echo CHtml::encode(Yii::app()->params['appName']); ?></title>
</head>

   
<?php 
	if( $this->bgImage=='images/bg-empty.jpg' )
		echo "<body style='background:#ffffff url(" . $this->bgImage . ");padding:0px; margin:0px;overflow:hidden;'>";
	else
		echo "<body style='background:#ffffff url(" . $this->bgImage . ") no-repeat 0px 0px;padding:0px; margin:0px;overflow:hidden;'>";
		
	echo "<div class='fbHeader' style='border:solid 0px green'>";
	if(Yii::app()->params['isAppClosed']) 
			echo "<div style='height:24px'>SOUTĚŽ BYLA UKONČENA DNE 14.10.2011</div>";
	else
		echo "<div style='height:24px'>&nbsp;</div>";
			
	echo "</div>";
	if( $this->drawMenu ) 
	{
		echo "<div id='mainmenu' style='margin-left:240px;margin-top:-35px;width:480px;border:solid 0px red'>";
		$this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'MŮJ PROFIL', 'url'=>array('/site/index')),
				array('label'=>'PŘÁTELÉ', 'url'=>array('/site/dashboard')),
				array('label'=>'PRAVIDLA', 'url'=>array('/site/page', 'view'=>'rules')),
				array('label'=>'VÝHRY', 'url'=>array('/site/page', 'view'=>'prize')),
				array('label'=>'ŽEBŘÍČEK', 'url'=>array('/site/score')),
				array('label'=>'FÓRUM', 'url'=>array('/site/page', 'view'=>'report')),
			),
		)); 
		echo "</div> <!-- mainmenu -->";
	}
	else
	{
		echo "<div id='fbPhotoSubHeader' class='fbPhotoSubHeader'>";
		echo "  <div id='fbHeaderLeft' class='leftPart'>&nbsp;</div>";
		echo "  <div id='fbHeaderRight' class='rightPart'>&nbsp;&nbsp;&nbsp;</div>";
		echo "</div>";
	}
	
?>


<div class="container" id="page" style="margin:0px;padding-top:0px;text-aligh:center;height:400px">

	<?php echo $content; ?>

	<div id="footer"></div><!-- footer -->

</div><!-- page -->
</body>
</html>