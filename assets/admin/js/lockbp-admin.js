jQuery(document).ready(function(){
	jQuery(document).on('change', '#lockbp-display-items', function(){
		var display_val = jQuery(this).val();
		if( display_val == '' ) {
			jQuery('.lockbp-display-panel').hide()
		} else {
			jQuery('.lockbp-display-panel').show()
		}
	});
});

