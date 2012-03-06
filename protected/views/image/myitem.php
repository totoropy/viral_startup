<?php
$h = "hlasů";
if( $this->item['votes']<5 && $this->item['votes']>0 )
	$h = "hlasy";
if( $this->item['votes']==1 )
	$h = "hlas";

?>
<div class="gTh">
	<div class="imageItem">	
		<a rel="<?php echo $this->item['rel'];?>" title="<?php echo $this->item['title'];?>" style="z-index:1" href="index.php?r=image/view&id=<?php echo $this->item['id'];?>">
			<div class="gImg" title="<?php echo $this->item['title'];?>" style="background: #ddd url('<?php echo $this->item['imgSrc'];?>') no-repeat <?php echo $this->item['margin'];?>px 0px;">&nbsp;<div class="votesTitle"><?php echo $this->item['votes'] . "&nbsp;" . $h; ?></div></div>
		</a>
	</div>
</div>

