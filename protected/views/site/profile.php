<div class="profileframe" style="width:80px;height:80px;border:solid 1px #ddd;padding:2px;overflow:hidden;float:left;margin:2px">
	<a href="http://www.facebook.com/profile.php?id=<?php echo $req['friendId'];?>" target="_blank" >
		<div style="width:52px;height:52px;float:left"><img src="https://graph.facebook.com/<?php echo $req['friendId'];?>/picture?type=square" width="50" height="50" border="0" style="display:block;float:left;width:50px;height:50px;margin:2px;vertical-align:top;" /></div>
		<div style="width:52px;height:25px;"><?php echo $req['id'];?></div>
	</a>
	
	<?php echo $req['accepted'];?>
</div>
