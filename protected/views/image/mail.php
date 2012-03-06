<!DOCTYPE html>
<html>
<style>

body {
  font-family: tahoma,verdana,arial,sans-serif;
  font-size: 11px;
  background: #fff;
  color: #333;
  padding-bottom: 25px;
}

#fancybox-wrap {
  position: absolute;
  top: 100px !important;
}


#tbody{
  height: 100px;
  width: 200px;
  border: 1px solid #BDC7D8;
  padding: 3px;
  -webkit-appearance: none;
-webkit-border-radius: 0;
}

.inputtext, .inputpassword {
border: 1px solid #BDC7D8;
font-size: 11px;
padding: 3px;
-webkit-appearance: none;
-webkit-border-radius: 0;
}
.platform_dialog_header_container {
background-color: #6D84B4;
border: 1px solid #3B5998;
}
.platform_dialog_header {
color: white;
font-size: 14px;
font-weight: bold;
padding: 5px;
}
.platform_dialog_icon {
background: #6D84B4 url(https://s-static.ak.facebook.com/rsrc.php/v1/yd/r/Cou7n-nqK52.gif) no-repeat scroll center;
float: left;
height: 16px;
margin: 5px;
width: 16px;
}
.uiInlineTokenizer {
cursor: text;
padding-left: 2px;
border: 1px solid #BDC7D8;
margin-left:60px;
}
.uiTextareaAutogrow {
overflow: hidden;
}

textarea, .inputtext, .inputpassword {
border: 1px solid #BDC7D8;
font-family: tahoma,verdana,arial,sans-serif;
font-size: 11px;
padding: 3px;
-webkit-appearance: none;
-webkit-border-radius: 0;
}
.platform_dialog_buttons {
float: right;
padding: 10px;
text-align: right;
}
.inputtext, .inputpassword {
margin: 0;
padding-bottom: 4px;

}
.label {
color: #666;
font-size: 11px;
font-weight: bold;
padding-right: 10px;
text-align: right;
vertical-align:top;
width: 80px;
}

#fancybox-content {
width: 0;
height: 0;
padding: 0;
outline: none;
position: relative;
overflow: hidden;
z-index: 1102;
border: 0px solid #666;
}

#mail-box-message
{
	text-align:center;
	padding:4px;color:red;
}
</style>



<script language="javascript">
function validate()
{
	if( $("#MailForm_imageId").val()=='' ){
		$("#mail-box-message").html('Chybí imageId.&nbsp;<span style="width:30px;cursor:pointer" onclick="clearMsg()">[x]</span>'); return false; }
		
	if( $("#MailForm_from").val()=='' ){
		$("#mail-box-message").html('Chybí odesilatel <b>Od</b>. Nastavte si email v <a href="<?php echo Yii::app()->createUrl('site/index'); ?>">profilu</a>.&nbsp;<span style="width:30px;cursor:pointer" onclick="clearMsg()">[x]</span>'); return false; }

	if( $("#MailForm_to").val()=='' ){
		$("#mail-box-message").html('Chybí příjemce <b>Komu</b>.&nbsp;<span style="width:30px;cursor:pointer" onclick="clearMsg()">[x]</span>'); return false; }
		
	if( $("#MailForm_body").val()=='' ){
		$("#mail-box-message").html('Chybí text zprávy.&nbsp;<span style="width:30px;cursor:pointer" onclick="clearMsg()">[x]</span>'); return false; }
	

	var str = $("#mail-form").serialize();
	$.ajax({
	   type: 'POST',
	   url: '<?php echo Yii::app()->createUrl('ajax/validatemail'); ?>',
	   data: str + '&ajax=mail-form',   
	   success: function(msg){ if(msg=='1') {send();} else{$("#mail-box-message").html('Zadejte platný email Komu.&nbsp;<span style="width:30px;cursor:pointer" onclick="clearMsg()">[x]</span>'); return false;} },
	   });
	
	return false;
}
function clearMsg()
{
	$("#mail-box-message").html('');
}
function send()
{
	
	var str = $("#mail-form").serialize();
	$.ajax({
	   type: 'POST',
	   url: '<?php echo Yii::app()->createUrl('ajax/sendmail'); ?>',
	   dataType: 'json',
	   data: str + '&ajax=mail-form',   
	   success: function(msg){ if(msg=='1') {
								$("#mail-box-message").html("Mail byl odeslán na adresu " + $("#MailForm_to").val() ); 
								$("#mail-box-message").attr("style","color:green");
								$("#mail-box-send").hide();
								}
								else{$("#mail-box-message").html("Mail nebyl odeslán."); }
							},
	   });
}
</script>

<div class="msg_wrap">
	<div class="platform_dialog_header_container">
		<div class="platform_dialog_icon"></div><div class="platform_dialog_header">Pošli mail</div>
	</div>

	<?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'mail-form',
    'action'=>Yii::app()->createUrl('ajax/mail'),
		)); ?>
	
		<div id="mail-box-message" style="">&nbsp;</div>
		<input type="hidden" id="MailForm_fbid" name="MailForm[fbid]" value="<?php echo $model->fbid; ?>" />
		<input type="hidden" id="MailForm_imageId" name="MailForm[imageId]" value="<?php echo $model->imageId; ?>" />
		<table style="border:0px;margin-bottom: 10px;">
			<tbody>
			<tr>
				<th class="label"><?php echo $form->labelEx($model,'from'); ?></th>
				<td class="data"><div id="mail-box-from" class="">
				<?php 	if( empty($model->from) ) 
						{
							echo CHtml::link('Nastavit email',array('site/index'), array('title'=>'Nastavit mailovou adresu',) );
							echo $form->textField($model,'from', array('class'=>'inputtext', 'readonly'=>'readonly', 'style'=>'width:20px;display:none',));
						}
						else
							echo $form->textField($model,'from', array('class'=>'inputtext', 'readonly'=>'readonly', 'style'=>'width:320px;color:#888888',));
					  
				?>
				
				</div></td>
			</tr>
			
			<tr>
				<th class="label"><?php echo $form->labelEx($model,'to'); ?></th>
				<td class="data"><div id="mail-box-to" class=""><?php echo $form->textField($model,'to', array('class'=>'inputtext', 'style'=>'width:320px',)) ?></div></td>
			</tr>

			<tr>
				<th class="label"><?php echo $form->labelEx($model,'body'); ?></th>
				<td class="data"><div id="mail-box-body" class=""><textarea id="MailForm_body" class="uiTextareaAutogrow" rows="5" cols="35" style="width:320px;height:80px" name="MailForm[body]">Ahoj, účastním se soutěže <?php echo Yii::app()->params['appName']; ?>. Hlasuj pro mě...

<?php echo Yii::app()->params['appCanvasUrl'] . "index.php?r=image/view&id=" . $model->imageId; ?></textarea></div></td>
			</tr>
			</tbody>
		</table>
	
		<div id="mail-box-buttons">
			<div class="platform_dialog_buttons">
				<input class="uibutton confirm" id="mail-box-send" style="width:120px" type="button" onclick="validate();" value="Poslat mail" />
				<input class="uibutton" style="width:120px" onclick="$.fancybox.close();" type="button" value="Zavřít" />
			</div>
		</div>
	<?php $this->endWidget(); ?>
</div>
</html>