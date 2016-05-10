/*
 * 	Themex Autosave 1.0 - jQuery plugin
 *	written by Ihor Ahnianikov	
 *  http://themextemplates.com
 *
 *	Copyright (c) 2015 Ihor Ahnianikov
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
(function($) {
	$.fn.themexAutosave = function (options) {
		var form=$(this);
		var	fields=form.find('input,select,textarea');
		
		//init fields
		function init() {
			var data=localStorage.getItem(form.attr('id'));
			
			if(data==null && form.data('default')) {
				var defaults=localStorage.getItem(form.data('default'));
				
				if(defaults) {
					data=defaults;
					
					localStorage.setItem(form.attr('id'), defaults);
					localStorage.removeItem(form.data('default'));
				}
			}
			
			if(data) {
				data=JSON.parse(data);
				
				$.each(data, function(index, object) {
					if(object.name.indexOf('[]')<0) {
						var field=form.find('[name="'+object.name+'"]');
						
						if(field.length && field.attr('type')!='hidden') {
							field.val(object.value);
						}
					}
				});
			}
		}
		
		//save fields
		function save() {
			var data=fields.not('.exclude').serializeArray();
			
			if(data) {				
				data=JSON.stringify(data);
				localStorage.setItem(form.attr('id'), data);
			}
		}
		
		//clear fields
		function clear() {
			localStorage.setItem(form.attr('id'), '');
		}
		
		//update tinyMCE
		if(typeof(tinyMCE)!='undefined') {
			$(document).click(function(){
				tinyMCE.triggerSave();
				save();
			});
		}
		
		//autosave on change
		fields.change(function() {
			save();
		});
		
		//clear on submit
		form.submit(function() {
			clear();
		});
		
		//init on loading
		init();
	}
})(jQuery);