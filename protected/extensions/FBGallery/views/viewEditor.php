<div class="gcontainer clearfix" style="border:0">
	<?php
		if(count($this->arrItems)):
			$this->widget('zii.widgets.jui.CJuiSortable', array(
				'items'=>$this->arrItems,
				'id'=>'container_sortabil',
				'options'=>array(
					'delay'=>'300',
// 					'handle'=>'.gImgName',
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


<!-- <div id="fbguploader" class="fbguploader">uploader zone</div> -->
