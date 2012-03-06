How to
Install FBGallery:

    * Extract archive in your protected/extensions folder.
    * Create one folder for your galleries and give write permissions.
      Default folder is set to "galleries", at same level as protected folder, but you can put anywhere, then set it in Gallery CPanel.
    * Add to your database two tables (see sql/fbgallery.sql)

Use FBGallery:

    * In static pages, or pages without id in its url,(ex: site/index ) use next statement:

      $this->widget('application.extensions.FBGallery.FBGallery', array('pid'=>'1'));

      where pid must be an unique value for each gallery

    * In pages which use id in url,(ex: /page/2 - where 2 is id) use next statement

      $this->widget('application.extensions.FBGallery.FBGallery');


Updated version

Starting with Yii.1.1.5 changed:
assets/fbgallery.js
	from: $(this).closest('li').find('.deleteIcon');
	to: $(this).closest('li').children('div').find('.deleteIcon');

view/viewEditor:
	handle option from zii.widgets.jui.CJuiSortable not permit to start drag;
	'handle'=>'.gImgName' have been removed

04.03.2011 Updated
Added - thOnLine propriety - set the number of thumbnails to be listed in a row. Default value is 4.
	Value of thOnLine can't be bigger than row width / sum of thumbnails widths.
Corected - Id name of thumbnails to be valide html, using a prefix.
Corected - Every picture will be constrained to stay in it's row.

26.05.2011 minor update (necessar for yii 1.1.7)
Replaced in FBGallery.php in line 441 
	$rel = $this->pageTitle.'-'.$this->pid; 
		with 
	$rel = preg_replace('!\-+!', '-', preg_replace("/[^a-z0-9-_.]/", "-", strtolower($this->pageTitle)));



