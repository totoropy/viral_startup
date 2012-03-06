<?php
$url =  'https://'.Yii::app()->request->getServerName() . Yii::app()->request->url; //
$perms = Yii::app()->params['permissions'];
$loginUrl = "https://www.facebook.com/dialog/oauth?scope=".$perms."&client_id=". Yii::app()->params['appid'] ."&redirect_uri=".urlencode(Yii::app()->params['appCanvasUrl']);
?>
<script> 
function enter(){top.location.href='<?php echo $loginUrl; ?>';} 
function leave(){top.location.href='https://www.facebook.com';}
</script>

<div style="width:523px;height:99%;padding:4px;margin:25px 25%;background-color:#fff;border:0px">
<div id="score" style="width:500px;height:99%;padding:10px;margin:0px;background-color:#fff;border:solid 1px #dddddd">


<br/><br/>
Uživatel <img src="https://graph.facebook.com/<?php echo $id; ?>/picture?type=square" alt="" width="50" height="50" border="0" /> <?php echo $name; ?> si Vás dovolil pozvat na ... <br/>
<input type='button' value='Přijmout pozvání' onclick='enter()' /><br/>
<input type='button' value='Odmítnout' onclick='leave()' /><br/>

<div>
<div>

