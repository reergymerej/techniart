$(function(){
	//	activate sortable/filterable tables
	var loc = String( window.location );
	var eventID = loc.substr( loc.lastIndexOf('/') );
	techniart.activateList('event_users/listUsers' + eventID);
});

//	returns an object with field and direction, used for refreshList()
techniart.getSortObject = function( element ){
	alert('Sorting here does not work yet.');
	var sortObject = new Object();
		
	//	if triggered by form, figure out the element and keep the sorting order
	if(!element){
		element = $('th.asc, th.desc').first();
		sortObject.direction = $(element).hasClass('desc') ? 'desc' : 'asc';
	}
	
	//	determine column
	var column = $(element).html();
	switch(column.toLowerCase()){
		case 'admin':
			sortObject.field = 'admin';
			break;
		case 'phone':
			sortObject.field = 'phone';
			break;
		case 'last':
			sortObject.field = 'last_name';
			break;
		case 'first':
			sortObject.field = 'first_name';
			break;
		default:
		case 'email':
			sortObject.field = 'email';
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