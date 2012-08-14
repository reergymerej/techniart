$(function(){
	techniart.activateImages();
});

techniart.activateImages = function(){
	techniart.solidImages();

	$('#delete a').click(function(e){
		$('#delete img').not( $('img', this) ).css({ opacity : .3 });
		techniart.promptForDelete(this);
		return false;
	});
};

techniart.promptForDelete = function(a){
	if( confirm("Delete this image?") ){
		$('#delete').html('refreshing...');
		var action = $('#delete_form').attr('action');
		var imageId = $(a).attr('name');
		
		$.post(action, {id:imageId}, function(resp){
			var reloadAction = action.replace('delete', 'getThumbs');
			//	refresh thumbs
			$.post(reloadAction, function(resp){
				$('#delete').html(resp);
				techniart.activateImages();
			});
		});
		
	} else {
		techniart.solidImages();	
	}
};

techniart.solidImages = function(){
	$('#delete img').css({ opacity : 1 });
};