<div class="gTh" style="width:181px;background-color:transparent;padding:0px;border:0">
<?php
$actionName = "view";
if( $this->item['fbid']==$_SESSION['fbid'] )
	$actionName = "update";

?>
		<div class="imageItem" style="width:169px;background-color:white;padding:0px;border:solid 1px #dddddd">
			<div style="height:1px;width:80px;padding:0px;position:relative;left:90px;top:90px;">
				<a href="?r=image/<?php echo $actionName; ?>&id=<?php echo $this->item['id'];?>" class="uibutton">Líbí&nbsp;<img src="images/libi.gif" width="16" height="17" border="0" style="width:16px;height:17px;" />&nbsp;&nbsp;<span class="votecount"><?php echo $this->item['votes'];?></span></a>
			</div>
			
			<a class="gImg" rel="<?php echo $this->item['rel'];?>" title="<?php echo $this->item['title'];?>" style="z-index:1" href="<?php echo $this->item['urlImg'];?>">
				<div title="<?php echo $this->item['title'];?>" style="width:161px;height:120px;margin:4px;margin-top:3px;background:url('<?php echo $this->item['imgSrc'];?>') no-repeat;"></div>
			</a>
			<div style="height:40px;text-align:left;font-size:11px;background-color:#fff;z-index:100;border:0">
				<div style="height:34px;width:34;padding:1px;float:left;border:solid 1px #dddddd;margin:4px;margin-top:0px">
					<img src="https://graph.facebook.com/<?php echo $this->item['fbid'];?>/picture?type=square" width="32" height="32" border="0" style="width:32px;height:32px;" />
				</div>
				<div style="margin-left:42px;background-color:#ffffff;float:left;height:38px;width:120px;text-align:left;font-size:11px;clip:rect(0px, 120px, 38px, 0px);position: absolute">
				<?php echo $this->item['title'];?>
				</div>
			</div>
		</div>
</div>

