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
//$img->created;
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
	case 1: $monthName = "ŘÍJ";break;
	case 1: $monthName = "LIS";break;
	case 1: $monthName = "PRO";break;
}

?>

<style>
td{border: solid 0px red}
input[type=text]{width:200px;border: solid 1px #dcdcdc}
#txtCaption{width:200px; border: solid 1px #dcdcdc}
#tDetail td{font-size:11px}
#imagebox{width:400px;height:300px;border:dotted 1px #dcdcdc}
.platform_dialog_header_container {
background-color: #6D84B4;
border: 1px solid #3B5998;
}
.platform_dialog_icon {
background: #6D84B4 url(https://s-static.ak.facebook.com/rsrc.php/v1/yd/r/Cou7n-nqK52.gif) no-repeat scroll center;
float: left;
height: 16px;
margin: 5px;
width: 16px;
}
.platform_dialog_header_container .platform_dialog_header {
color: white;
font-size: 14px;
font-weight: bold;
padding: 5px;
}
.platform_dialog .platform_dialog_bottom_bar {
background-color: #F2F2F2;
bottom: 0;
left: 0;
position: fixed;
right: 0;
width: 100%;
}
.pam {
padding: 10px;
}
</style>


<div style="height:700px;">
<?php if(Yii::app()->user->hasFlash('imageview')): ?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('imageview'); ?>
		</div>
<?php endif; ?>

<div id="fb-root"></div><script src="/fb/hannah/soutez-leto2011/css/all.js"></script>
<div id="img-header" style="width:100%;height:20px;margin-left:190px;"><span id="imgpos"></span><br/>

</div>
<div id="imagebelt" style="width:100%;height:<?php echo $img->height+50; ?>">
	<div style="height:64px;width:400px;margin-left:180px;padding:2px;text-align:center;border:solid 0px red">
			<a id="lnk_prev2" href=""><img id="img_prev2" src="images/arr-prev.gif" title="další" width="12" height="56" border="0" style="margin-right:-2px;margin-bottom:2px;border:solid 0px #fff" /></a>
			<a id="lnk_prev" href=""><img id="img_prev" title="předchozí" width="80" height="60" border="0" style="margin:1px;border:solid 1px #fff" /></a>
			<img id="img_actu" width="80" height="60" border="0" style="margin:1px;border:solid 1px #ff0000" />
			<a id="lnk_next" href=""><img id="img_next" title="další" width="80" height="60" border="0" style="margin:1px;border:solid 1px #fff" /></a>
			<a id="lnk_next2" href=""><img id="img_next2" src="images/arr-next.gif" title="další" width="12" height="56" border="0" style="margin-left:-2px;margin-bottom:2px;border:solid 0px #fff" /></a>
	</div>
	<div id="leftbox" style="float:left;margin-left:45px;margin-top:100px;width:120px;height:<?php echo $img->height+8; ?>;background-color:#d7d3bd;padding:10px 10px 10px 5px;border:solid 1px #fff;border-right:solid 1px #bebebe">
		<div  style="padding:4px;text-align:right">
			<?php 
			$ajaxCall = array( 
				'type' => 'POST',
				'data' => array( 'imageId' => $img->id ),
				'success' => "function( data ){ $('#counter').html( data ); $('#yt0').attr('class','uibutton special disable'); $('#yt0').attr('disabled','disabled'); $('#yt0').attr('value','Už jsi hlasoval/a');}",
			  );
					
			if( $isvoted==0 )
				echo CHtml::ajaxButton('Hlasovat', Yii::app()->createUrl('ajax/vote'), $ajaxCall, array('class'=>'uibutton special disable','style'=>'width:120px','disabled'=>'disabled') );
			else
				echo CHtml::ajaxButton('Už jsi hlasoval/a', Yii::app()->createUrl('ajax/vote'), $ajaxCall, array('class'=>'uibutton special disable','style'=>'width:120px','disabled'=>'disabled') );
			?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Pozvat přátele', array('class'=>'uibutton confirm','style'=>'width:120px', 'onclick'=>'newInvite()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Dát si fotku na zeď', array('class'=>'uibutton confirm','style'=>'width:120px', 'onclick'=>'publishStream()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Poslat fotku', array('class'=>'uibutton confirm','style'=>'width:120px', 'onclick'=>'sendPhoto()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<a href="<?php echo Yii::app()->createUrl('site/mail&imageId=' . $img->id); ?>" class="uibutton confirm" style="width:105px" id="send_mail_button">Poslat e-mail</a>
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
		<div id="image-views" title="počet zobrazení" style="width:100px;height:30px;margin-top:15px;background:#d7d3bd url(images/views.jpg) no-repeat;padding-top:10px;border:solid 1px #fff;border-left:0px;">
			<span style="font-size:16px;font-weight:bold;color:#666666;margin-left:45px"><?php echo $img->views; ?></span>
		</div>
		<div id="image-likes" title="počet hlasů" style="width:100px;height:30px;margin-top:15px;background:#d7d3bd url(images/likes.jpg) no-repeat;padding-top:10px;border:solid 1px #fff;border-left:0px;">
			<span id="counter" style="font-size:16px;font-weight:bold;color:#666666;margin-left:45px"><?php echo $img->votes; ?></span>
		</div>
		<div id="image-comments" title="počet komentářů" style="width:100px;height:30px;margin-top:15px;background:#d7d3bd url(images/comments.jpg) no-repeat;padding-top:10px;border:solid 1px #fff;border-left:0px;">
			<span style="font-size:16px;font-weight:bold;color:#666666;margin-left:45px"><fb:comments-count href="<?php echo $url; ?>" /></fb:comments-count></span>
		</div>
	</div>
	<div id="label" style="width:392px;height:72px;margin-top:8px;background-image:url(images/caption.png);padding:4px;margin-left: 184px;">
		<div id="datebox" style="width:70px;height:70px;border:0px;float:left;color:#ffffff;text-align:center">
			<span style="font-size:14px;font-weight:bold"><?php echo $monthName; ?></span><br/>
			<span style="font-size:22px;font-weight:bold;line-height: 1em;"><?php echo date("d", $created); ?></span><br/>
			<span style="font-size:12px;line-height: 0.8em;"><?php echo date("Y", $created); ?></span><br/>
			<span style="font-size:10px"><?php echo date("H:i:s", $created); ?></span>
		</div>
		<div id="profilebox" style="width:60px;height:70px;border:0px;float:left">
			<div style="height:52px;width:52;padding:1px;float:left;border:solid 1px #dddddd;margin:4px;margin-top:0px">
				<img src="https://graph.facebook.com/<?php echo $img->fbid; ?>/picture?type=square" width="50" height="50" border="0" />
			</div>
			
		</div>
		<div id="caption" style="width:253px;height:70px;border:0px;float:left;text-align:left">
			<span style="font-size:12px;font-weight:bold"><?php echo $name; ?></span>
			<?php if( $img->fbid==$_SESSION["fbid"] ) { ?>
				&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?r=image/update&id=<?php echo $img->id; ?>" style="font-size:11px;color:#3b5998;padding:2px;text-decoration:none">[upravit&nbsp;popis]</a>
			<?php } ?>
			<br/>
			<span style="font-size:16px;font-weight:normal;color:#000000"><?php echo $img->caption; ?></span><br/>
			<span style="font-size:12px;font-weight:normal;color:#888888"><?php echo $img->place . " " . $img->time; ?></span><br/>
		</div>
		
	</div>

	<div id="faces" style="margin-left:180px;width:400px;height:60px">

	</div>

	<div id="commentbox" style="background-color:#ffffff;margin-left:180px;width:400px;padding:4px;border:solid 1px #cccccc">
		<fb:comments href="<?php echo $url; ?>" num_posts="5" width="400"></fb:comments>
	</div>
	
</div>

<div id="space" >&nbsp;</div>
</div>
 
 <?php 
  $req_limit = Yii::app()->params['invitationsPerDayLimit'];
?>;
    <script type="text/javascript">
	
       FB.init({
         appId  : '<?= Yii::app()->params['appid'] ?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });
	   FB.Canvas.setSize({ height: 1600 });
	   
function newInvite(){
 			//filters:['app_non_users'],data:99
	var n = <?php echo $req_limit-$req_per_day; ?>;
	if( n>0 )
	{
		 var receiverUserIds = FB.ui({ 
				method : 'apprequests',
				message: 'Ahoj, účastním se soutěže Hannah o nejlepší outdoorovou fotku z letošního léta. Hlasuj pro mě... Dík',
				//filters:['app_non_users'],
				max_recipients:n
		 },

		 function(receiverUserIds) {
				  sentReq(receiverUserIds.request_ids);
				  //console.log("IDS : " + receiverUserIds.request_ids);
				}
		 );
	}
	else
	{
		alert("Denní limit <?php echo $req_limit; ?> pozvánek byl vyčerpán. Zase zítra ;-)");
	}
}

function sentReq(ids)
{
$.ajax({
	   type: 'POST',
	   url: '<?php echo Yii::app()->createUrl('ajax/requestsent'); ?>',
	   data: 'reqid='+ids,   
	   success: function(msg){ window.location.href='<?php echo Yii::app()->createUrl('image/view&id='.$img->id ); ?>'; },
	   });
}

function publishStream(){
	 streamPublish('<?php echo $img->caption; ?>', '<?php echo Yii::app()->params['appName']; ?>', '', '<?php echo $fburl; ?>', '');
}

function streamPublish(linkname, caption, description, hrefLink, userPrompt){        

	FB.ui({ method : 'feed', 
		message: userPrompt,
		link   :  hrefLink,
		caption:  caption,
		picture:  '<?php echo Yii::app()->params['appBaseUrl']; ?><?php echo $img->path; ?>',
		description: description,
		name : linkname
	  },
	  
	  function(response) {
			 if (response && response.post_id) {
			   alert('Fotka byla publikována na zdi.');
			 } else {
			   alert('Fotka nebyla publikována.');
			 }
		}
	);
}
	 
function sendPhoto()	 
{
	FB.ui({
          method: 'send',
          name: '<?php echo $img->caption; ?>',
          link: '<?php echo $fburl; ?>',
		  picture:  '<?php echo Yii::app()->params['appBaseUrl']; ?><?php echo $img->path; ?>',
		  description: '<?php echo Yii::app()->params['appName']; ?>',
          });
}

$(document).ready(function(){
	$("#send_mail_button").fancybox({'showCloseButton': false,'titlePosition':'inside','easingEnabled':true,'mouseEnabled':true,'transitionIn':'elastic','transitionOut':'elastic','speedIn':600,'speedOut':200,'overlayShow':false, 'onComplete': function() {$("#fancybox-wrap").css({'top':'20px', 'bottom':'auto'});} });


	$.ajax({
	   type: 'POST',
	   url: '<?php echo Yii::app()->createUrl('ajax/next'); ?>',
	   data: 'imageId=<?php echo $img->id; ?>',
	   success: function(msg){ 
			try{
				var pos = JSON.parse(msg); 
				if( pos.images[0].id=='0')
				{
					$("#lnk_prev").attr("href", "#");
					$("#lnk_prev2").attr("href", "#");
					$("#img_prev").attr("src", 'images/empty-th.jpg');
				}
				else
				{
					$("#lnk_prev").attr("href", "?r=image/view&id="+pos.images[0].id);
					$("#lnk_prev2").attr("href", "?r=image/view&id="+pos.images[0].id);
					$("#img_prev").attr("src", pos.images[0].src);
				}
				
				if( pos.images[2].id=='0')
				{
					$("#lnk_next").attr("href", "#");
					$("#lnk_next2").attr("href", "#");
					$("#img_next").attr("src", 'images/empty-th.jpg');
				}
				else
				{
					$("#lnk_next").attr("href", "?r=image/view&id="+pos.images[2].id);
					$("#lnk_next2").attr("href", "?r=image/view&id="+pos.images[2].id);
					$("#img_next").attr("src", pos.images[2].src);
				}

				$("#img_actu").attr("src", pos.images[1].src);
			}
			catch(ex){ alert(ex.message); }
			//fotogal/index.php?r=image/view&id=44
		}
	 });
});

function sendMail(uri)
{

	// $.ajax({
	   // type: 'POST',
	   // url: uri,
	   // data: 'imageId=<?php echo $img->id; ?>',
	   // success: function(msg){ },
	   // });
	   
}
</script>


<div style="display:none">
<div id="data">

</div>
</div>





