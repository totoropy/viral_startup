<?php
$this->pageTitle=Yii::app()->name . ' - Fotka';
$this->pageName="imageview";
$this->breadcrumbs=array('Fotka',);
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
$img->created;
$created = strtotime($img->created);
$created = $created + (8 * 60 * 60); // +8 hours - server is in the usa


?>

<div style="height:700px;">
<?php if(Yii::app()->user->hasFlash('imagetest')): ?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('imagetest'); ?>
		</div>
<?php endif; ?>
<h2>Test <?php echo $code; ?></h2>


<div id="fb-root"></div><script src="https://connect.facebook.net/cs_CZ/all.js"></script>
<div id="img-header" style="width:100%;height:20px;margin-left:190px;"><span id="imgpos"></span><br/>

</div>
<div id="imagebelt" style="width:100%;height:<?php echo $img->height+50; ?>">

	<div id="leftbox" style="float:left;margin-left:40px;margin-top:120px;width:120px;height:<?php echo $img->height+8; ?>;background-color:#F3DCA6;padding:10px">
		<div  style="padding:4px;text-align:right">
			<?php 
			$ajaxCall = array( 
				'type' => 'POST',
				'data' => array( 'imageId' => $img->id ),
				'success' => "function( data ){ $('#counter').html( data ); }",
			  );
					
			if( $isvoted==0 )
				echo CHtml::ajaxButton('Hlasovat', Yii::app()->createUrl('ajax/vote'), $ajaxCall, array('class'=>'uibutton special','style'=>'width:120px') );
			else
				echo CHtml::ajaxButton('Už jsi hlasoval', Yii::app()->createUrl('ajax/vote'), $ajaxCall, array('class'=>'uibutton special disable','style'=>'width:120px','disabled'=>'disabled') );
			?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Pozvat pøátele', array('class'=>'uibutton confirm','style'=>'width:120px', 'onclick'=>'newInvite()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Dát si fotku na zeï', array('class'=>'uibutton confirm','style'=>'width:120px', 'onclick'=>'publishStream()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Poslat fotku', array('class'=>'uibutton confirm','style'=>'width:120px', 'onclick'=>'sendPhoto()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<a id="send_mail_button" href="data.php">mail</a>
		</div>
		<div style="padding:4px;text-align:right">
			<fb:like href="<?php echo $fburl; ?>" send="false" layout="button_count" width="400" show_faces="true" action="like" font=""></fb:like>
		</div>
	</div>

	<div id="imagebox" style="float:left;width:<?php echo $img->width; ?>px;height:<?php echo $img->height; ?>px;background-color:#ffffff;padding:4px;">
		<img src="<?php echo $img->path; ?>" width="<?php echo $img->width; ?>" height="<?php echo $img->height; ?>" border="0" style="padding:0px;margin:0px;border:0px" />
	</div>
	<div id="imageflags" style="margin-left:<?php echo $img->width+182; ?>px;width:100px;height:<?php echo $img->height+8; ?>px;border:solid 0px red;">
		<div id="image-space" style="width:100px;height:10px;"></div>
		<div id="image-views" title="poèet zobrazení" style="width:100px;height:30px;margin-top:15px;background:#F3DCA6 url(images/views.jpg) no-repeat;padding-top:10px;">
			<span style="font-size:16px;font-weight:bold;color:#666666;margin-left:45px"><?php echo $img->views; ?></span>
		</div>
		<div id="image-likes" title="poèet hlasù" style="width:100px;height:30px;margin-top:15px;background:#F3DCA6 url(images/likes.jpg) no-repeat;padding-top:10px;">
			<span id="counter" style="font-size:16px;font-weight:bold;color:#666666;margin-left:45px"><?php echo $img->votes; ?></span>
		</div>
		<div id="image-comments" title="poèet komentáøù" style="width:100px;height:30px;margin-top:15px;background:#F3DCA6 url(images/comments.jpg) no-repeat;padding-top:10px;">
			<span style="font-size:16px;font-weight:bold;color:#666666;margin-left:45px"><fb:comments-count href="<?php echo $url; ?>" /></fb:comments-count></span>
		</div>
	</div>
	<div id="label" style="width:392px;height:72px;margin-top:8px;background-image:url(images/caption.png);padding:4px;margin-left: 184px;">
		<div id="datebox" style="width:70px;height:70px;border:0px;float:left;color:#ffffff;text-align:center">
			<span style="font-size:14px;font-weight:bold"><?php echo date("M", $created); ?></span><br/>
			<span style="font-size:22px;font-weight:bold"><?php echo date("d", $created); ?></span><br/>
			<span style="font-size:12px;"><?php echo date("Y", $created); ?></span><br/>
			<span style="font-size:10px"><?php echo date("H:i:s", $created); ?></span>
		</div>
		<div id="profilebox" style="width:60px;height:70px;border:0px;float:left">
			<div style="height:52px;width:52;padding:1px;float:left;border:solid 1px #dddddd;margin:4px;margin-top:0px">
				<img src="https://graph.facebook.com/<?php echo $img->fbid; ?>/picture?type=square" width="50" height="50" border="0" />
			</div>
		</div>
		<div id="caption" style="width:253px;height:70px;border:0px;float:left;text-align:left">
			<span style="font-size:12px;font-weight:bold"><?php echo $fusr->name; ?></span><br/>
			<span style="font-size:16px;font-weight:normal;color:#000000"><?php echo $img->caption; ?></span><br/>
			<span style="font-size:12px;font-weight:normal;color:#888888"><?php echo $img->place . " " . $img->time; ?></span><br/>
		</div>
		
	</div>

	<div id="faces" style="margin-left:180px;width:400px;height:60px">

	</div>

	
</div>
