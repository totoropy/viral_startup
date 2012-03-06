<style>
.inputtext {margin: 0;border: 1px solid #BDC7D8;font-family:tahoma,verdana,arial;font-size:11px;padding:3px;padding-bottom:4px;-webkit-appearance: none;-webkit-border-radius: 0;}
.label {color: #666;font-size: 11px;font-weight: bold;padding-right: 10px;text-align: right;vertical-align:top;width: 60px;}
.avatar-box{height:50px;width:50px;padding:1px;float:left;border:solid 1px #dddddd;margin:4px;margin-top:0px}
#info-box{	position:absolute;top:100px;left:50px;width:660px;height:550px;background-color:#fff;z-index:100;text-align:center;}
#info-box-shadow{	position:absolute;top:0px;left:0px;width:760px;height:800px;background-color:#000;z-index:10;text-align:center;opacity: 0.5; filter: alpha(opacity=50);cursor:wait;}
.kupon-box-closed{
	position:absolute;
	top:220px;
	left:359px;
	float:left;
	width:54px;
	height:104px;
	padding:0px;
	border:0px;
	cursor:pointer;
}
.kupon-box-revealed{
	position:absolute;
	top:220px;
	left:359px;
	float:left;
	width:54px;
	height:104px;
	padding:0px;
	border:0px;
	cursor:pointer;
}
</style>

<?php 
$req_limit = Yii::app()->params['invitationsPerDayLimit'];
$imagesLimit = Yii::app()->params['imagesLimit'];
$fitem = new FItem;
$showUpload = false;
if( $imgCount<$imagesLimit)
	$showUpload = true;
		

$kuponBoxClass = "kupon-box-closed";
$kuponBoxTitle = "Nejprve je třeba získat " . Yii::app()->params['kuponLimit'] . " hlasů pro svoji fotku a zde si pak stáhnete slevový kupón.";
$link = "<a href='" . Yii::app()->params['appBaseUrl'] . "index.php?r=site/page&view=prize'><img src='images/kupon-cb.jpg' atl='kupon' width='54' height='104' border='0' /></a>";
if( $highScore>=Yii::app()->params['kuponLimit'] )
{
	$kuponBoxClass = "kupon-box-revealed";
	$kuponBoxTitle = "Gratulujeme, vyhráváte slevový kupón.";
	$link = "<a href='" . Yii::app()->params['appBaseUrl'] . "images/poukaz_na_soutez_fb.jpg' target='_blank'><img src='images/kupon-b.jpg' atl='kupon' width='54' height='104' border='0' /></a>";
}
 ?>


<div id="wraper" style="margin-top:67px;height:220px;border:solid 0px red">
	<div id="kupon-box" style="display:none" class="<?php echo $kuponBoxClass; ?>" title="<?php echo $kuponBoxTitle; ?>"><?php echo $link; ?></div>
	<div id="profile-box" style="margin-left:426px;width:220px;height:200px;padding:10px;background-color:transparent;border:solid 0px red">

			<?php $form = $this->beginWidget('CActiveForm', array(
				'id'=>'user-form',
				'enableAjaxValidation'=>true,
				'action'=>Yii::app()->createUrl('site/index'),
					)); ?>
				
				<?php echo $form->hiddenField($model,'facebookID'); ?>
				<table style="border:0px;margin-bottom: 10px;">
					<tbody>
						<tr>
							<td class="label"><div style="float:left;padding:4px;font-size:11px;font-weight:normal">&nbsp;</div></td>
							<td class="data"><div id="mail-box-message" style="">&nbsp;</div></td>
						</tr>
						<tr>
							<td class="label"></td>
							<td class="data">
								<div class="avatar-box">
									<img src="https://graph.facebook.com/<?php echo $_SESSION["fbid"]; ?>/picture?type=square" width="50" height="50" border="0" />
								</div>
							</td>
						</tr>
						<tr>
							<td class="label"><?php echo $form->labelEx($model,'nick'); ?></td>
							<td class="data"><?php echo $form->textField($model,'nick', array('class'=>'inputtext', 'style'=>'width:140px',)) ?></td>
						</tr>
						<tr>
							<td class="label"><?php echo $form->labelEx($model,'email'); ?></td>
							<td class="data"><?php echo $form->textField($model,'email', array('class'=>'inputtext', 'style'=>'width:140px',)) ?></td>
						</tr>
						<tr>
							<td class="label"><?php echo $form->labelEx($model,'created_at'); ?></td>
							<td class="data"><?php echo CHtml::textField('FUser[created_at]', date('d.m. Y H:i', strtotime($model->created_at)+3600*7), array('class'=>'inputtext', 'style'=>'width:140px;color:#888','disabled'=>'disabled',)) ?></td>
						</tr>
						<tr>
							<td class="label"></td>
							<td class="data"><input class="uibutton" id="user-save" style="width:70px" type="button" onclick="validate();" value="Uložit" /></td>
						</tr>
					</tbody>
				</table>
				
			<?php $this->endWidget(); ?>
	</div>
</div>

		<div style="margin-top:32px;margin-right:100px;padding:4px;text-align:right">
			<?php echo CHtml::button('Pozvat přátele', array('class'=>'uibutton confirm','style'=>'width:160px', 'onclick'=>'newInvite()')); ?>
		</div>

</div>
</div>
<div id="info-box-shadow" style="display:none"></div>
<div id="info-box" style="display:none"><br/><h2>Vyčkejte... <span class="blink">Obrázek se nahrává.</span></h2></div>
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
	   