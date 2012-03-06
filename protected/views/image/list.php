

<?php if(Yii::app()->user->hasFlash('list')): ?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('list'); ?>
		</div>
<?php endif; ?>

<div id="gal" class="gcontainer-notitle clearfix" style="border:0px;margin-left:10px">
	<?php
		if(count($this->arrItems)):
			$this->widget('zii.widgets.jui.CJuiSortable', array(
				'items'=>$this->arrItems,
				'id'=>'container_sortabil',
				'options'=>array(
					'delay'=>'300',
					'stop' => "js: function(){
							var ids = new Array;
							var urls = $(this).find('a.gImg');

							$(urls).each(function(){
								var a = $(this).attr('href').split('/');
								var l = a.length;
								ids.push(a[l-1]);
							}); 
							$.post('$this->rUri', 'newImgOrder='+ids);
						}"
				),
			));

			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>'myDialog',
				'options'=>array(
					'dialogClass'=>'hidden',
					'autoOpen'=>false,
				),
			));
			echo '<div class="msg hide"></div>';
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		else:
			echo Yii::t('app', 'Galerie je prázdná.');
		endif;
	?>
</div>


<div style="border:0px;margin-left:13px;width:700px;padding:4px;background-color:#fff">
<?php $this->widget('CLinkPager', array( 'pages' => $pages,)) ?>
</div>

<br/>

<div class="tooltip"></div>

<div id="fb-root"></div><script src="<?php echo Yii::app()->params['appBaseUrl']; ?>css/all.js"></script>

<script type="text/javascript">

FB.init({
	 appId  : '<?= Yii::app()->params['appid'] ?>',
	 status : true, // check login status
	 cookie : true, // enable cookies to allow the server to access the session
	 xfbml  : true  // parse XFBML
    });
FB.Canvas.setSize({ height: 1200 });
   
// load menu   this.getTrigger().attr("votes")
jQuery('#fbHeaderLeft').html("<a href='?r=site/index'>&lt;- Zpět na můj profil</a>&nbsp;&nbsp;&nbsp;");
jQuery('#gal .gImg').tooltip({ position:"bottom center", offset:[-140, 0], onShow:function() { var offset=this.getTip().offset(); if(offset.left<0) this.getTip().offset({top:offset.top,left:0});if(offset.left>430) this.getTip().offset({top:offset.top,left:430});  var v=this.getTrigger().attr("votes");var t=this.getTrigger().attr("caption");var u=this.getTrigger().attr("over");var i=this.getTrigger().attr("no");this.getTip().html("<a style='z-index:1' href='index.php?r=image/view&id=" + i + "'><div style='width:322px;height:242px;z-index:2;background:#ddd url(" + u + ") no-repeat' ></div><table class='downtext' cellmargin='0' cellpadding='0'><tr><td style='height:30px;padding:1px;font-weight:bold;'>" + t + "</td><td style='width:10%'><div class='overvotesTitle'>" + v + "&nbsp;hlasů&nbsp;</div></td></tr></table></a>"); } });

</script>

<style>

/* tooltip styling */
.tooltip {
	display:none;
	background:#fff;
	height:242px;
	padding:4px;
	border:solid 1px #ccc;
	width:322px;
	font-size:11px;
	color:#fff;
}
.downtext
{
	width:100%;
	height:28px;
	margin-top:-46px;
	background-color:#555;
	border-top:solid 1px #999;
	border-bottom:solid 1px #999;
	z-index:5;
	color:#fff;
	font-size:11px;
	font-weight:bold;
	padding:4px;
	background:rgba(0,0,0,0.6) no-repeat 0px 0px;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
}

/* a .label element inside tooltip */
.tooltip .label {
	color:yellow;
	width:35px;
}

.tooltip a {
	color:#ad4;
	font-size:11px;
	font-weight:bold;
}


</style>