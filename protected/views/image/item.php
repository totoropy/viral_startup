<?php
$h = "hlasů";
if( $this->item['votes']<5 && $this->item['votes']>0 )
	$h = "hlasy";
if( $this->item['votes']==1 )
	$h = "hlas";

?>
<div class="gTh">
		<div class="imageItem">
			<div class="itemButton">
				<a rel="fancybox" href="?r=image/view&id=<?php echo $this->item['id'];?>" class="uibuttontrans" ><img src="images/libi.gif" class="itemIcon" width="16" height="17" border="0" />&nbsp;&nbsp;<span class="votecount"><?php echo $this->item['votes'] . "&nbsp;" . $h ;?></span></a>
			</div>
			
			<a rel="<?php echo $this->item['rel'];?>" title="<?php echo $this->item['title'];?>" style="z-index:1" href="index.php?r=image/view&id=<?php echo $this->item['id'];?>">
				<div class="gImg" title="<?php echo $this->item['title'];?>" style="background: #ddd url('<?php echo $this->item['imgSrc'];?>') no-repeat <?php echo $this->item['margin'];?>px 0px;"></div>
			</a>
			<div class="titleFrame">
				<div class="titleProfile">
					<img src="https://graph.facebook.com/<?php echo $this->item['fbid'];?>/picture?type=square" width="32" height="32" border="0" style="width:32px;height:32px;" />
				</div>
				<div class="titleDesc">
					<b><?php echo $this->item['title'];?></b><br/>
					<i><?php echo $this->item['subtitle'];?></i>
				</div>
			</div>
		</div>
</div>

