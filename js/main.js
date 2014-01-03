
function limitInput(selector, limit, mode)
{
	$(selector).each(function() {
		var field = $(this);
		var info  = '<p class="help-block control-label col-sm-12">'
		          + (mode == 'min' ? 'mind.' : 'max.') +  ' ' + limit + ' Zeichen<br />' + (mode == 'min' ? 'aktuell' : 'noch übrig') +  ': '
		          + '<input disabled="disabled" name="' + field.attr('name') + '_charcount" class="charcount" />'
		          + '</p>';
		var label = field.closest('.form-group').find('label');
		
		label.wrap('<div class="col-sm-3"><div class="row"></div></div>')
		     .removeClass('col-sm-3')
		     .addClass('col-sm-12')
		     .after(info);
		
		var numberField = $('input[name="' + field.attr('name') + '_charcount"]');
		
		field.keyup(function() {
			var value = field.val();
			
			if (mode == 'max') {
				if (value.length > limit) {
					field.val(value.substring(0, limit));
				}
				numberField.val(limit - field.val().length);
			} else {
				numberField.val(field.val().length);
			}
		}).keyup();
	});
}
