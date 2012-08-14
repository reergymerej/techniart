$(function(){
	techniart.hideMessages();
});

var techniart = {
	MESSAGE_TIMEOUT : 3000,
	hideMessages : function(){
		$('.message').each(function(index, element) {
			if($(this).html()){
				setTimeout(function(){
					$(element).slideUp('slow');
				}, techniart.MESSAGE_TIMEOUT);
			} else {
				$(this).hide();
			}
        });
	},
	sortListPath : null
}

/****************************/
/*	Sortable/filterable tables
/*	techniart.getSortObject must be implemented for the specific page's criteria
/*	and techniart.sortListPath must be defined in the .ready() section on the first .activateList()
/****************************/
//	Add sort functionality to .sortable tables.
//	watch for click events to trigger sorting
techniart.activateList = function( sortListPath ){
	techniart.sortListPath = sortListPath
	$('.sortable th').not('.not-sortable').each(function(index, element) {
	   $(this).click( element, function(){
		  techniart.refreshList( techniart.getSortObject(element) ); 
	   });
	});
};

//	Replace the current #list with the fetched results.
//	post criteria and reload list
techniart.refreshList = function( sortObject ){
	$.post(techniart.sortListPath, {
			sort_by: sortObject.field,
			direction: sortObject.direction,
			filter_criteria: $.trim($('[name="filter_criteria"]').val()),
			filter_field: $.trim($('#filter_field').val())
			}, function(resp){
		$('#list').html(resp);
		techniart.activateList( techniart.sortListPath );			
	});
};