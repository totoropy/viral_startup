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

</style>



<script language="javascript">
function validate()
{
	if( $("MailForm_imageId").val()=='' ){
		$("MailForm_imageId").html('Chybí imageId'); return false; }
		
	if( $("MailForm_from").val()=='' ){
		$("MailForm_from").html('Chybí odesilatel. Nastavte si email v profilu.'); return false; }

	if( $("MailForm_to").val()=='' ){
		$("MailForm_to").html('Chybí odesilatel. Nastavte si email v profilu.'); return false; }
		
	if( $("MailForm_body").val()=='' ){
		$("MailForm_body").html('Chybí text zprávy.'); return false; }
	
	return false;
}
function send()
{

	//form1.submit();
}
</script>

<div class="msg_wrap">
	<div class="platform_dialog_header_container">
		<div class="platform_dialog_icon"></div><div class="platform_dialog_header">Pošli mail</div>
	</div>

	<form id="mail-form" action="<?php echo Yii::app()->createUrl('ajax/mail'); ?>" method="POST">
		<div id="mail-box-message">&nbsp;</div>
		<input type="hidden" id="MailForm_fbid" name="MailForm[fbid]" value="<?php echo $model->fbid; ?>" />
		<input type="hidden" id="MailForm_imageId" name="MailForm[imageId]" value="<?php echo $model->imageId; ?>" />
		<table style="border:0px;margin-bottom: 10px;">
			<tbody>
			<tr>
				<th class="label"><?php echo $form->labelEx($model,'from'); ?></th>
				<td class="data"><div id="mail-box-from" class=""><?php echo $form->textField($model,'from', array('class'=>'inputtext', 'readonly'=>'readonly', 'style'=>'width:320px;color:#888888',)) ?></div></td>
			</tr>
			
			<tr>
				<th class="label"><?php echo $form->labelEx($model,'to'); ?></th>
				<td class="data"><div id="mail-box-to" class=""><?php echo $form->textField($model,'to', array('class'=>'inputtext', 'style'=>'width:320px',)) ?></div></td>
			</tr>

			<tr>
				<th class="label"><?php echo $form->labelEx($model,'body'); ?></th>
				<td class="data"><div id="mail-box-body" class=""><textarea id="MailForm_body" class="uiTextareaAutogrow" style="width:320px" name="MailForm[body]">Přijď na Facebook...fotka=<?php echo $model->imageId; ?></textarea></div></td>
			</tr>
			</tbody>
		</table>
	
		<div id="mail-box-buttons">
			<div class="platform_dialog_buttons">
				<input class="uibutton confirm" id="mail-box-send" style="width:120px" type="button" onclick="if(validate()) send();" value="Poslat mail" />
				<input class="uibutton" style="width:120px" onclick="$.fancybox.close();" type="button" value="Zavřít" />
			</div>
		</div>
	</form>
</div>
