$(function(){
	//	activate sortable/filterable tables
	techniart.activateList( 'event/eventList' );
	
	$("#search").submit(function() {
		techniart.refreshList( techniart.getSortObject() );
        return false;
    });
});

//	returns an object with field and direction, used for refreshList()
techniart.getSortObject = function( element ){
	var sortObject = new Object();
		
	//	if triggered by form, figure out the element and keep the sorting order
	if(!element){
		element = $('th.asc, th.desc').first();
		sortObject.direction = $(element).hasClass('desc') ? 'desc' : 'asc';
	}
	
	//	determine column
	var column = $(element).html();
	switch(column.toLowerCase()){
		case 'start':
			sortObject.field = 'date_start';
			break;
		case 'end':
			sortObject.field = 'date_end';
			break;
		case 'finished':
			sortObject.field = 'finished';
			break;
		default:
		case 'event name':
			sortObject.field = 'name';
			break;
	}
	
	//	determine sort direction
	if(!sortObject.direction){
		if($(element).hasClass('desc') || !$(element).hasClass('asc')){
			sortObject.direction = 'asc';
		} else {
			sortObject.direction = 'desc';
		}
	}
	
	return sortObject;
};