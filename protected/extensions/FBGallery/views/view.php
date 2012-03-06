<div class="gcontainer clearfix" style="border:0">
	<?php
		echo $this->pageName . "*<br/>";
		$existentsItems = count($this->arrItems);
		if($existentsItems)
		{
			$ind = 0;
			$listed = 0;
			foreach($this->arrItems as $item)
			{
				if($ind === 0) 
					echo '<div class="fbgrow clearfix">';

				echo $item;
				$listed++;
				$ind++;

				if($ind === $this->thOnLine || $listed === $existentsItems)
				{
					echo '</div>';
					$ind = 0;
				}
			}
		}
		else
			echo Yii::t('app', 'This gallery is empty');
	?>
</div>

