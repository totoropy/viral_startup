<?php
$url = 'http://'.Yii::app()->request->getServerName() . Yii::app()->request->url; 
$pos = strrpos($url,"/index.php?");
$fburl = Yii::app()->params['appCanvasUrl'];
if( $pos>0 )
{
	$s = substr($url, $pos+1 );
	$fburl = Yii::app()->params['appCanvasUrl'] . $s;
}
//url   = "https://www.akceplzen.cz/fb/joebo/fotogal/index.php?r=image%2Fview&id=44";
//fburl = "http://apps.facebook.com/joebo_fotogal/index.php?r=image%2Fview&id=44";
$created = strtotime($img->created);
$created = $created + (8 * 60 * 60); // +8 hours - server is in the usa

$monthName = "---";
$month = date("m", $created);
switch($month)
{
	case 1: $monthName = "LED";break;
	case 2: $monthName = "ÚNO";break;
	case 3: $monthName = "BŘE";break;
	case 4: $monthName = "DUB";break;
	case 5: $monthName = "KVĚ";break;
	case 6: $monthName = "ČRN";break;
	case 7: $monthName = "ČRC";break;
	case 8: $monthName = "SRP";break;
	case 9: $monthName = "ZÁŘ";break;
	case 10: $monthName = "ŘÍJ";break;
	case 11: $monthName = "LIS";break;
	case 12: $monthName = "PRO";break;
}
?>

<div class="fbPhotoBox"><img id="imageSource" src="<?php echo $img->path; ?>" width="<?php echo $img->width; ?>" height="<?php echo $img->height; ?>" border="0" style="padding:0px;margin:0px;border:0px" /></div>
<div class="fbFeatureBox">
	<?php $form=$this->beginWidget('CActiveForm', array('action'=>Yii::app()->createUrl('image/update', array('id' => $img->id)), 'id'=>'fimage-form','enableAjaxValidation'=>false,)); ?>
	<div class="fbCommentBox">
		<div id="label" style="width:99%;height:72px;margin-top:2px;background:url(images/caption.png) no-repeat 4px 4px;padding:4px;">
			<div id="datebox" style="width:70px;height:70px;border:0px;float:left;color:#ffffff;text-align:center;">
				<span style="font-size:14px;font-weight:bold"><?php echo $monthName; ?></span><br/>
				<span style="font-size:22px;font-weight:bold;line-height: 1em;"><?php echo date("d", $created); ?></span><br/>
				<span style="font-size:12px;line-height: 0.8em;"><?php echo date("Y", $created); ?></span><br/>
				<span style="font-size:10px"><?php echo date("H:i:s", $created); ?></span>
			</div>
			<div id="profilebox" style="width:60px;height:70px;border:0px;float:left;">
				<div style="height:52px;width:52;padding:1px;float:left;border:solid 1px #dddddd;margin:4px;margin-top:0px">
					<img src="https://graph.facebook.com/<?php echo $img->fbid; ?>/picture?type=square" width="50" height="50" border="0" />
				</div>
			</div>
			<div id="caption" style="width:340px;height:70px;border:0px;float:left;text-align:left;">
				<span style="font-size:12px;font-weight:bold"><?php echo $name; ?></span>
				<?php echo $form->textArea($img,'caption', array('size'=>60,'maxlength'=>255,'style'=>'width:99%;height:60px;border:solid 1px #b6cadc;scroll:auto;font-size:16px;font-weight:normal;color:#000000')); ?>
				<br/><?php echo $form->error($img,'caption'); ?>
			</div>
			<div id="error">
				<?php echo $form->errorSummary($img); ?>
				<?php echo CHtml::hiddenField('id', $img->id);  ?>
			</div>
		</div>
	</div>
	<div class="fbButtonBox" style="text-align:right">
		<div style="margin:4px;text-align:right">
			<?php echo CHtml::link('Odstranit', array('image/delete', 'id'=>$img->id), array('class'=>'uibutton', 'style'=>'width:100px', 'confirm'=>'Opravdu smazat?', )); ?>
		</div>
		<div style="margin:4px;margin-top:40px;text-align:right">
			<?php echo CHtml::submitButton('Uložit', array('class'=>'uibutton confirm', 'style'=>'width:115px;')); ?>
		</div>
		<div style="margin:4px;text-align:right">
			<?php if( !empty($img->caption) )	{echo CHtml::link('Zpět', array('image/view', 'id'=>$img->id), array('class'=>'uibutton', 'style'=>'width:100px'));}  ?>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>

<div id="fb-root"></div><script src="/fb/hannah/soutez-leto2011/css/all.js"></script>
<script type="text/javascript">

   FB.init({
	 appId  : '<?= Yii::app()->params['appid'] ?>',
	 status : true, // check login status
	 cookie : true, // enable cookies to allow the server to access the session
	 xfbml  : true  // parse XFBML
   });
   FB.Canvas.setSize({ height: 1200 });
</script>














