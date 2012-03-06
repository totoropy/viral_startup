/* jQuery jnplace 1.0
 * http://www.webspider.ro/jquery-plugins/jquery.jnplace/
 *
 * Copyright (c) 2010 Ovidiu Pop
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function(jQuery){
 	jQuery.fn.extend({jnplace: function(options) {
		var defaults = {
			doajax:false,
			mod_debug:false,
			ajax_page : 'functions.php',
			ajax_function:'function=myfunction&arg1=xxxxxx&arg2=yyyyyy',
			iscombo:false,
			doubleclick:true,
			 
			 editabil:function(obj){
				var actual = obj.html();
				var actualID = obj.attr("id");
				var newBox = '<input type="text" class="editor_rename" value="'+actual+'" />';
				obj.hide().after(newBox);
				jQuery('.editor_rename').focus();

				jQuery('.editor_rename').blur(function(){
					jQuery(this).remove();
					obj.show();
				})

				jQuery('.editor_rename').bind("keyup", function(event){
					if(event.keyCode == 27){
						jQuery(this).remove();
						obj.show();
					}

					if(event.keyCode == 13){
						var new_val = jQuery(this).val();
						jQuery(this).remove();
						obj.html(new_val).show();
						if(options.doajax){
							var myfunction = options.ajax_function.replace("xxxxxx", actualID).replace("yyyyyy", new_val);
							$.post(options.ajax_page, myfunction, function(rez){
								if(rez === 'reload'){window.location.href=window.location.href;}
								if(options.mod_debug){
									alert(rez);
								}
							})
						}else{
							if(options.mod_debug){
								alert("id="+actualID+"\nvalue="+new_val);
							}
						}
					}
				})
			 },
			 
			 combo:function(obj){
				var actualID = obj.attr("id");
				var new_val = obj.val();
				var myfunction = options.ajax_function.replace("xxxxxx", actualID).replace("yyyyyy", new_val);
				$.post(options.ajax_page, myfunction, function(rez){
					if(rez === 'reload'){window.location.href=window.location.href;}
					if(options.mod_debug){
						alert(rez);
					}
				})
			}
		}

		var options =  $.extend(defaults, options);
    		return this.each(function() {
			if(options.iscombo == true){
				jQuery(this).bind('keyup, change', function (){
					var obj = jQuery(this);
					options.combo(obj);
				});
			}else{
				var event = options.doubleclick? "dblclick": "click" 
				jQuery(this).bind(event, function(){
					var obj = jQuery(this);
					options.editabil(obj);
				});
			}
				
		});
	}
});
})(jQuery);