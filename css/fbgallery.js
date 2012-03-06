function startGallery(){
	$('.hide').removeClass('hide');
	$('.toggleDown').click(function(){
		$(this).toggleClass('toggleUp').next('div').toggle();
	});
	
	$('.imageItem').hover( function(){
		var delIcon = $(this).closest('li').children('div').find('.deleteIcon');
		delIcon.css('display', 'block'); 
		delIcon.hover( function(){ 
			$(this).css('display', 'block'); 
		}, 
		function(){ 
			$(this).css('display', 'none'); } ); 
	}, 
	function(){ 
		$(this).closest('li').children('div').find('.deleteIcon').css('display', 'none'); 
	});
}

function editorForTitle(url){
	$('.text_field').jnplace({
		'doajax':true,
		'mod_debug':false,
		'ajax_page' : url,
		'doubleclick':true,
		'ajax_function':'function=renameItem&arg1=xxxxxx&arg2=yyyyyy'
	});
}

function gDialogs(dialogDeleteImageMessage, dialogDeleteImage, okButton, cancelButton, url, max)
{
	$('.deleteIcon').click(function(){
		var thisName = $(this).attr('title');
		var mysortable = $(this).closest('ul');
		var objLi = $(this).closest('li');
		$('.msg').html(dialogDeleteImageMessage.replace('xxxxx', thisName));

		var buttons = {};
		buttons[okButton] = function() {
			objLi.remove(); 
			mysortable.sortable( 'refresh' ); 
			$(this).dialog('close'); 
			$.ajax({ type: 'POST', 
				url: url, 
				data: 'deleteImg=true&gImg='+thisName, 
				success: function(){
					var max = parseInt($('#limitFiles').html())+1;
					$('#limitFiles').html(max);
					$('#uploader').MultiFile('reset').attr('maxlength', max);
				}
			});
		};

		buttons[cancelButton] = function() {
			$(this).dialog('close');
		};

		$('#myDialog').dialog({
			buttons: buttons,
			width:400,
			title: dialogDeleteImage + thisName + '?'
		}).dialog('open');
		return false;
	});
}
