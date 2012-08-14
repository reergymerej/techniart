$(function(){
	//	activate sortable/filterable tables
	techniart.activateList('product/productList');
	
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
		case 'type':
			sortObject.field = 'type';
			break;
		case 'model':
			sortObject.field = 'model';
			break;
		case 'price':
			sortObject.field = 'price';
			break;
		default:
		case 'product name':
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