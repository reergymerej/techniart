$(function(){
	//	activate sortable/filterable tables
	techniart.activateList('user/userList');
	
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