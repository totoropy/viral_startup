<style>
.inputtext {margin: 0;border: 1px solid #BDC7D8;font-family:tahoma,verdana,arial;font-size:11px;padding:3px;padding-bottom:4px;-webkit-appearance: none;-webkit-border-radius: 0;}
.label {color: #666;font-size: 11px;font-weight: bold;padding-right: 10px;text-align: right;vertical-align:top;width: 60px;}
.avatar-box{height:50px;width:50px;padding:1px;float:left;border:solid 1px #dddddd;margin:4px;margin-top:0px}
</style>

<?php 
$req_limit = Yii::app()->params['invitationsPerDayLimit'];
 ?>


	<div id="wraper" style="margin-left:7px;margin-top:67px;height:600px;padding:5px;border:solid 0px red;background-color:#fff">
	<div>Odesláno pozvánek celkem: <?php echo $sent; ?> &nbsp;Přijato: <?php echo $accepted; ?></div>
	<?php 
		if( isset($profiles) )
		{
			foreach($profiles as $profile)
			{
				echo $profile;
			}
		}
	 ?>
 
	</div>

	<div style="margin-top:32px;margin-right:100px;padding:4px;text-align:right">
		<?php echo CHtml::button('Pozvat přátele', array('class'=>'uibutton confirm','style'=>'width:160px', 'onclick'=>'newInvite()')); ?>
	</div>

</div>
</div>
<div style="margin-top:400px;text-align:center;font-size:13px;font-weight:bold"><a href="<?php echo Yii::app()->params['webSite']; ?>" target="_blank" style="color:#fff">www.joebo.info</div>



<div id="fb-root"></div><script src="<?php echo Yii::app()->params['appBaseUrl']; ?>css/all.js"></script>
<script type="text/javascript">

FB.init({
 appId  : '<?= Yii::app()->params['appid'] ?>',
 status : true, // check login status
 cookie : true, // enable cookies to allow the server to access the session
 xfbml  : true  // parse XFBML
});
FB.Canvas.setSize({ height: 900 });
   
$(document).ready(function() {
  
});

  
function newInvite(){
 			//filters:['app_non_users'],data:99
	var n = <?php echo $req_limit-$req_per_day; ?>;
	if( n>0 )
	{
		 var receiverUserIds = FB.ui({ 
				method : 'apprequests',
				message: 'Ahoj, účastním se soutěže...',
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

function sentReq(rids,uids)
{
	$.ajax({
		   type: 'POST',
		   url: '<?php echo Yii::app()->createUrl('ajax/requestsent'); ?>',
		   data: 'reqid='+rids + '&userids='+uids,   
		   success: function(msg){ window.location.href='<?php echo Yii::app()->createUrl('site/index' ); ?>'; },
		   });
}


function validate()
{
	$("#mail-box-message").attr('style','color:red;display:block');
	if( $("#FUser_nick").val()=='' ){
		$("#mail-box-message").html('Chybí jméno.&nbsp;'); clearMsg(); return false; }
		
	if( $("#FUser_email").val()=='' ){
		$("#mail-box-message").html('Chybí email.&nbsp;'); clearMsg(); return false; }


	var str = $("#user-form").serialize();
	$.ajax({
	   type: 'POST',
	   url: '<?php echo Yii::app()->createUrl('site/index'); ?>',
	   data: str + '&ajax=user-form',   
	   success: function(msg){ if(msg=='1') {$("#mail-box-message").html('Uloženo.');$("#mail-box-message").attr('style','color:green');clearMsg(); return true;} else{ $("#mail-box-message").html('Zadejte platný email.&nbsp;'); clearMsg(); return false;} },
	   });
	
	return false;
}
function clearMsg()
{
	$("#mail-box-message").fadeOut(4000);
}


(function($)
{
	$.fn.blink = function(options)
	{
		var defaults = { delay:500 };
		var options = $.extend(defaults, options);
		
		return this.each(function()
		{
			var obj = $(this);
			setInterval(function()
			{
				if($(obj).css("visibility") == "visible")
				{
					$(obj).css('visibility','hidden');
				}
				else
				{
					$(obj).css('visibility','visible');
				}
			}, options.delay);
		});
	}
}(jQuery))
</script>
	   