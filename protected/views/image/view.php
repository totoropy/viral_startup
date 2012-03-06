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

<div class="fbPhotoBox" style="text-align:center"><img id="imageSource" src="<?php echo $img->path; ?>" width="<?php echo $img->width; ?>" height="<?php echo $img->height; ?>" border="0" style="padding:0px;margin:0px;border:0px" /></div>
<div class="fbFeatureBox">
	<div class="fbCommentBox">
		<table cellpadding="0" cellmargin="0" style="width:480px;height-min:70px;margin:0px;padding:0px;border:0px;background-color:#eee"><tr>
			<td style="width:72px;margin:0px;padding:0px;color:#ffffff;background:url(images/caption.png) no-repeat 2px 2px;text-align:center;vertical-align:top;">
				<span style="font-size:14px;font-weight:bold"><?php echo $monthName; ?></span><br/>
				<span style="font-size:22px;font-weight:bold;line-height: 1em;"><?php echo date("d", $created); ?></span><br/>
				<span style="font-size:12px;line-height: 0.8em;"><?php echo date("Y", $created); ?></span><br/>
				<span style="font-size:10px"><?php echo date("H:i:s", $created); ?></span>
			</td>
			<td style="width:52px;margin:0px;padding:2px;vertical-align:top;text-align:left;">
				<div style="height:52px;width:52;padding:1px;border:solid 1px #dddddd;background-color:#fff;margin:0px;">
					<img src="https://graph.facebook.com/<?php echo $img->fbid; ?>/picture?type=square" width="50" height="50" border="0" />
				</div>
			</td>
			<td style="border:0px;margin:0px;padding:2px;text-align:left;vertical-align:top;border:solid 1px #ddd;background-color:#fff">
				<span style="font-size:12px;font-weight:bold"><?php echo $name; ?></span>
				<?php if( $img->fbid==$_SESSION["fbid"] ) { ?>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?r=image/update&id=<?php echo $img->id; ?>" style="font-size:11px;color:#3b5998;padding:2px;text-decoration:none">[upravit&nbsp;popis]</a>
				<?php } ?>
				<br/>
				<span id="popis" style="font-size:16px;font-weight:normal;color:#000000"><?php echo $img->caption; ?></span>
			</td>
			</tr>
		</table>
		<br/>
		<fb:comments href="<?php echo $url; ?>" num_posts="5" width="480"></fb:comments>
	</div>
	<div class="fbButtonBox">
		<div  style="padding:4px;text-align:right"><span id="counter" style="font-size:16px;font-weight:bold;color:#000;margin-left:5px"><?php echo $img->votes; ?></span>&nbsp;hlasů
			<?php 
			$ajaxCall = array( 
				'type' => 'POST',
				'data' => array( 'imageId' => $img->id ),
				'success' => "function( data ){ $('#counter').html( data ); $('#yt0').attr('class','uibutton special disable'); $('#yt0').attr('disabled','disabled'); $('#yt0').attr('value','Už jsi hlasoval/a');}",
			  );
			
			
			if( $isvoted==0 )	
				echo CHtml::button('Hlasovat', array('id'=>'yt0','class'=>'uibutton special','style'=>'width:120px', 'onclick'=>'vote('.$img->id.')'));
			else
				echo CHtml::button('Už jsi hlasoval/a', array('id'=>'yt0','class'=>'uibutton special disable','style'=>'width:120px', 'disabled'=>'disabled'));
			
			
			//if( $isvoted==0 )
			//	echo CHtml::ajaxButton('Hlasovat', Yii::app()->createUrl('ajax/vote'), $ajaxCall, array('id'=>'yt0','class'=>'uibutton special','style'=>'width:120px') );
			//else
			//	echo CHtml::ajaxButton('Už jsi hlasoval/a', Yii::app()->createUrl('ajax/vote'), $ajaxCall, array('id'=>'yt0','class'=>'uibutton special disable','style'=>'width:120px','disabled'=>'disabled') );
			?>
		</div>
		
		<div style="padding:4px;text-align:right">
			<span style="margin-top:20px;margin-left:55px;padding:4px;font-family:tahoma,verdana,arial,sans-serif;font-size:11px;font-weight:normal;color:gray;margin-left:5px"><?php echo $img->views; ?>&nbsp;zobrazení</span>
		</div>
		<div style="margin-top:20px;margin-left:55px;padding:4px;font-family:tahoma,verdana,arial,sans-serif;font-size:11px;font-weight:bold;color:#333;">Propagovat fotku</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Pozvat přátele', array('class'=>'uibutton confirm','style'=>'width:160px', 'onclick'=>'newInvite()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Dát si fotku na zeď', array('class'=>'uibutton confirm','style'=>'width:160px', 'onclick'=>'publishStream()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<?php echo CHtml::button('Poslat fotku', array('class'=>'uibutton confirm','style'=>'width:160px', 'onclick'=>'sendPhoto()')); ?>
		</div>
		<div style="padding:4px;text-align:right">
			<a href="<?php echo Yii::app()->createUrl('site/mail&imageId=' . $img->id); ?>" class="uibutton confirm" style="width:145px" id="send_mail_button">Poslat e-mail</a>
		</div>
	</div>
</div>

<a id="lnk_prev2" href="#"><div id="fbLeftSpace" class="arrowBoxL" title="Předchozí"><img src="css/fb_left.png" style="margin:10px" /></div></a>
<a id="lnk_next2" href="#"><div id="fbRightSpace" class="arrowBoxR" title="Další"><img src="css/fb_right.png" style="margin:10px" /></div></a>


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
	   
// load menu
jQuery('#fbHeaderLeft').html("<a href='?r=image/list'>Zpět do galerie</a>&nbsp;&nbsp;&nbsp;<a href='?r=site/index'>Můj profil</a>&nbsp;&nbsp;&nbsp;");
jQuery('#fbHeaderRight').html("<a id='lnk_prev' href='?r=image/view'>Předchozí</a>&nbsp;&nbsp;&nbsp;<a id='lnk_next' href='?r=image/view'>Další</a>&nbsp;&nbsp;&nbsp;");

  
function newInvite(){
 			//filters:['app_non_users'],data:99
	var n = <?php echo $req_limit-$req_per_day; ?>;
	if( n>0 )
	{
		 var receiverUserIds = FB.ui({ 
				method : 'apprequests',
				message: 'Ahoj, účastním se foto soutěže. Hlasuj pro mě... Dík',
				//filters:['app_non_users'],
				max_recipients:n
		 },

		 function(response) {
				  sentReq(response.request, response.to);
				}
		 );
	}
	else
	{
		alert("Denní limit <?php echo $req_limit; ?> pozvánek byl vyčerpán. Zase zítra ;-)");
	}
}

function vote(id)
{
	if(id>0)
	{
		$.ajax({
			   type: 'POST',
			   url: '<?php echo Yii::app()->createUrl('ajax/vote'); ?>',
			   data: 'imageId='+id,   
			   success: function(x){ $('#counter').html(x); $('#yt0').attr('class','uibutton special disable'); $('#yt0').attr('disabled','disabled'); $('#yt0').attr('value','Už jsi hlasoval/a'); },
			   });
	}
}
			

function sentReq(rids,uids)
{
	$.ajax({
		   type: 'POST',
		   url: '<?php echo Yii::app()->createUrl('ajax/requestsent'); ?>',
		   data: 'reqid='+rids + '&userids='+uids,   
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

var isLast = false;
var isFirst = false;
$(document).ready(function(){
	
	$("#fbLeftSpace").fadeOut(100);
	$("#fbRightSpace").fadeOut(100);
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
					isFirst = true;
					//$("#img_prev").attr("src", 'images/empty-th.jpg');
				}
				else
				{
					$("#lnk_prev").attr("href", "?r=image/view&id="+pos.images[0].id);
					$("#lnk_prev2").attr("href", "?r=image/view&id="+pos.images[0].id);
					//$("#img_prev").attr("src", pos.images[0].src);
				}
				
				if( pos.images[2].id=='0')
				{
					$("#lnk_next").attr("href", "#");
					$("#lnk_next2").attr("href", "#");
					isLast = true;
					//$("#img_next").attr("src", 'images/empty-th.jpg');
				}
				else
				{
					$("#lnk_next").attr("href", "?r=image/view&id="+pos.images[2].id);
					$("#lnk_next2").attr("href", "?r=image/view&id="+pos.images[2].id);
					//$("#img_next").attr("src", pos.images[2].src);
				}

				//$("#img_actu").attr("src", pos.images[1].src);
			}
			catch(ex){ alert(ex.message); }
		}
	 });
	 
	 //showing navig.arrows
	 var visib = false;
	 $("body").bind("mousemove",
			function(e){			
				if( e.pageY>70 && e.pageY<615 )
				{ 
					//show
					if(!visib)
					{
						if( !isFirst )
						{
							$("#fbLeftSpace").fadeIn(500); //fbLeftSpace fbRightSpace  lnk_prev2 lnk_next2
							visib = true;
						}
						if( !isLast )
						{
							$("#fbRightSpace").fadeIn(500); 
							visib = true;
						}
					}
				}
				else
				{ 
					//hide
					if(visib)
					{
						$("#fbLeftSpace").hide(100);
						$("#fbRightSpace").hide(100); 
						visib = false;
					}
				}
			});
			
			jQuery("#send_mail_button").fancybox({
							'showCloseButton': false,
							'titlePosition':'inside',
							'easingEnabled':true,
							'mouseEnabled':true,
							'transitionIn':'elastic',
							'transitionOut':'elastic',
							'speedIn':600,
							'speedOut':200,
							'overlayShow':false, 
							'onComplete': function() {$("#fancybox-wrap").css({'top':'20px', 'bottom':'auto'});}, 
			});
	
});


</script>

