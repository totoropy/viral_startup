
<style>


.fbPhotoSubHeader{
	width:100%;
	height:22px;
	font-family: Tahoma, Verdana, Arial, sans-serif;
	font-size: 11px;
	font-weight: normal;
	color:#000;
}
.fbPhotoBox{
	width:720;
	height:540px;
	background-color:#fff;
	padding:4px;
	border:solid 1px #ddd;
}
.fbFeatureBox{
	width:100%;
	height:300px;
}
.leftPart{
	float:left;
	text-align:left;
	width:60%;
}
.rightPart{
	float:left;
	text-align:right;
	width:40%;
}
</style>



<div class="fbPhotoSubHeader">
	<div class="leftPart">
		<a href="?r=image/list">Zpět do galerie</a>&nbsp;&nbsp;&nbsp;
		<a href="?r=site/index">Můj profil</a>&nbsp;&nbsp;&nbsp;
	</div>
	<div class="rightPart">
		<a id="lnk_prev" href="?r=image/detail">Předchozí</a>&nbsp;&nbsp;&nbsp;
		<a id="lnk_next" href="?r=image/detail">Další</a>&nbsp;&nbsp;&nbsp;
	</div>
</div>
<div class="fbPhotoBox"><img id="imageSource" src="<?php echo $img->path; ?>" width="<?php echo $img->width; ?>" height="<?php echo $img->height; ?>" border="0" style="padding:0px;margin:0px;border:0px" /></div>
<div class="fbFeatureBox">
	<div class="fbCommentBox"></div>
	<div class="fbButtonBox"></div>
</div>


 <?php 
  $req_limit = Yii::app()->params['invitationsPerDayLimit'];
?>

<div id="fb-root"></div><script src="<?php echo Yii::app()->params['appBaseUrl']; ?>css/all.js"></script>
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

function sentReq(ids){
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
	//$("#send_mail_button").fancybox({'showCloseButton': false,'titlePosition':'inside','easingEnabled':true,'mouseEnabled':true,'transitionIn':'elastic','transitionOut':'elastic','speedIn':600,'speedOut':200,'overlayShow':false, 'onComplete': function() {$("#fancybox-wrap").css({'top':'20px', 'bottom':'auto'});} });

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
					//$("#lnk_prev2").attr("href", "#");
					$("#img_prev").attr("src", 'images/empty-th.jpg');
				}
				else
				{
					$("#lnk_prev").attr("href", "?r=image/detail&id="+pos.images[0].id);
					//$("#lnk_prev2").attr("href", "?r=image/detail&id="+pos.images[0].id);
					$("#img_prev").attr("src", pos.images[0].src);
				}
				
				if( pos.images[2].id=='0')
				{
					$("#lnk_next").attr("href", "#");
					//$("#lnk_next2").attr("href", "#");
					$("#img_next").attr("src", 'images/empty-th.jpg');
				}
				else
				{
					$("#lnk_next").attr("href", "?r=image/detail&id="+pos.images[2].id);
					//$("#lnk_next2").attr("href", "?r=image/detail&id="+pos.images[2].id);
					$("#img_next").attr("src", pos.images[2].src);
				}

				//$("#img_actu").attr("src", pos.images[1].src);
			}
			catch(ex){ alert(ex.message); }
		}
	 });
});


</script>

