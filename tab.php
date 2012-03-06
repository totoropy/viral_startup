<?php

require_once('protected/vendors/fbsdk311/FacebookWrapper.php');

$fbconfig['appid']  = "304809456224027";
$fbconfig['secret']  = "0ed2b1d12112d42ab35b9834354a4a20";
$uri_app = 'https://apps.facebook.com/joebo_viral/';
$uri_page = 'https://www.facebook.com/pages/JoeBo/160698883995181?sk=app_245239455509046';

	   
// Create our Application instance.
$facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));

# Getting the signed request object
# When user interacts this object is not present
$signed_request = $facebook->getSignedRequest();
$page_id = $signed_request['page']['id'];
?>
	<html>
	<head>
	<title>JoeBo - foto soutěž</title>
	<style>
body{margin: 0;	padding: 0;	font: normal 10pt Arial,Helvetica,sans-serif;}
	</style>
	</head>
<?php 



if(isset($signed_request['page']['liked']) and $signed_request['page']['liked'] == 1)
{
	#Liked?
?>
	<body style="background:url('images/tab.jpg') no-repeat; width:516px;margin:0px;padding:0px;overflow:hidden;">
	<div style="position:absolute;left:10px;top:560px;width:500px;padding:8px;color:#D1010F">
			<b>Toto je ukázková aplikace FOTO SOUTĚŽ.</b><br/>
			<span style="color:#666">Princip soutěže je jednoduchý. Soutěžící má možnost poslat svoji fotku do soutěže. Dále má možnost hlasovat pro kteroukoliv soutěžící fotku a má možnost svoji fotku propagovat mezi svými přáteli. Vítězí fotka s největším počtem hlasů.</style>
			<div style="margin-left:300px;margin-top:30px"><a href="https://apps.facebook.com/joebo_fotogal/" target="_top"><img src="images/start.jpg" alt="" title="VSTOUPIT DO SOUTĚŽE" /></a></div>
	</div>

	</body>
<?php 
}
else
{	# Not liked
?>
<body style="background:url('images/tab.jpg') no-repeat; width:500px;margin:0px;padding:0px;overflow:hidden;">
	<div style="position:absolute;left:10px;top:560px;width:500px;padding:8px;color:#D1010F">
			<b>Toto je ukázková aplikace FOTO SOUTĚŽ.</b><br/>
			<span style="color:#666">Princip soutěže je jednoduchý. Soutěžící má možnost poslat svoji fotku do soutěže. Dále má možnost hlasovat pro kteroukoliv soutěžící fotku a má možnost svoji fotku propagovat mezi svými přáteli. Vítězí fotka s největším počtem hlasů.</style>
			<div style="margin-left:300px;margin-top:30px"><a href="https://apps.facebook.com/joebo_fotogal/" target="_top"><img src="images/start.jpg" alt="" title="VSTOUPIT DO SOUTĚŽE" /></a></div>
	</div>
	<div id="fb-root"></div>
	<script language="javascript">
	function FbRedirect(url){ setTimeout(function(){ top.location.href = url; }, 100); }
	window.fbAsyncInit = function(){
		FB.init({appId:'<?php echo $fbconfig['appid']; ?>',session:null,status:true,cookie:true,xfbml:true});
		//FB.Canvas.setAutoResize();
		FB.Canvas.setSize({ width: 500, height: 800 });
		FB.Event.subscribe('edge.create', function(){ FbRedirect('<?php echo $uri_app; ?>'); });
		FB.Event.subscribe('edge.remove', function(){ FbRedirect('<?php echo $uri_app; ?>'); });
	};

	(function(d){
		  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
		  js = d.createElement('script'); js.id = id; js.async = true;
		  js.src = "//connect.facebook.net/cs_CZ/all.js#xfbml=1";
		  d.getElementsByTagName('head')[0].appendChild(js);
		}(document));
	</script>
	<div style="position:absolute;left:35px;top:680px;color:#333;font:12px verdana,sans-serif;">
		<fb:like href="<?php echo $uri_page; ?>" send="false" layout="button_count" width="255" show_faces="false"></fb:like><br/><br/>
		<span style="font-size:12px;">Klikni na "To se mi líbí".</span><br/><br/>
		<span style="font-size:10px;">Soutěž je určena pouze fanouškům</span>
	</div>
</body>
<?php } ?>

