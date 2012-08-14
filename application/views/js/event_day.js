$(function(){
	$('#save').submit(function(e) {
		//	disable form
		$('input[type="submit"]', this).attr('disabled', 'disabled');
		
		//	TODO:: validate form
		
		
		$.post($(this).attr('action'), $(this).serialize(), function(resp){
			alert(resp);

			//	re-enable form
			$('input[type="submit"]', e.target).removeAttr('disabled');
		});
		
		return false;
    });
});