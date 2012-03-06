<?php
$h = "hlasů";
if( $this->item['votes']<5 && $this->item['votes']>0 )
	$h = "hlasy";
if( $this->item['votes']==1 )
	$h = "hlas";

?>
<div class="gTh">
	<div class="imageItem">	
		<a rel="1" title="<?php echo $this->item['title'];?>" style="z-index:1" href="index.php?r=image/view&id=<?php echo $this->item['id'];?>">
			<div class="gImg" title="<?php echo $this->item['title'];?>" caption="<?php echo substr($this->item['title'],0,70); ?>.." votes="<?php echo $this->item['votes'];?>" no="<?php echo $this->item['id'];?>" over="<?php echo $this->item['overSrc'];?>" style="background: #ddd url('<?php echo $this->item['imgSrc'];?>') no-repeat <?php echo $this->item['margin'];?>px 0px;">&nbsp;</div>
			<div style="display:none;background: #ddd url('<?php echo $this->item['overSrc'];?>') no-repeat"><img src="<?php echo $this->item['overSrc'];?>" alt="preload" /></div>
		</a>
	</div>
</div>

