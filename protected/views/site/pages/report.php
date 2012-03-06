<?php
$url =  'https://'.Yii::app()->request->getServerName() . Yii::app()->request->url; //
?>
<div style="width:523px;height:99%;padding:4px;margin:25px 25%;background-color:#fff;border:0px">
<div style="width:500px;height:99%;padding:10px;margin:0px;background-color:#fff;border:solid 1px #dddddd">
<p>Máte nějaký problém s aplikací? Našli jste chybu? <b>Napište to sem</b>.</p>

<p>
Pokusíme se Váš problém vyřešit.
</p>

<p>
<a href="https://www.facebook.com/apps/application.php?id=<?php echo Yii::app()->params['appid']; ?>" target="_blank">Diskuzní fórum o aplikace <?php echo Yii::app()->params['appName']; ?></a>
</p>

	<div id="commentbox" style="background-color:#ffffff;margin:40px auto;width:400px;padding:4px;border:solid 1px #cccccc">
		<fb:comments href="<?php echo $url; ?>" num_posts="5" width="400"></fb:comments>
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
