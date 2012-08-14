$(function(){
	//	load map
	techniart.mapAddress();
	
	//	power collapsible sections
	$('h2').click(function(e) {
        techniart.toggle($(this).next('.collapsible'));
    });
	
	//	auto collapse
	techniart.autoCollapse();
	
});

//	load the google map if a valid address is present
techniart.mapAddress = function(){
	var address = $('#address').html();
	if( address ){
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				//	store point so it can be used in map bug fix
				techniart.map_point = results[0].geometry.location;
				var mapOptions = {
					zoom: 15,
					center: techniart.map_point,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				techniart.map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
			
				techniart.map.setCenter(techniart.map_point);
			
				var marker = new google.maps.Marker({
					map: techniart.map,
					position: techniart.map_point
				});
			}
		});
	}	
};

//	toggle collapsible sections
techniart.toggle = function(obj){
	obj.slideToggle('slow', function(){
		$(this).prev().toggleClass('collapsed');
	});
	//	fix map bug where grey areas show if map is initialized in a hidden div
	google.maps.event.trigger(techniart.map, 'resize');
	techniart.map.setCenter(techniart.map_point);
};

//	automatically collapse
techniart.autoCollapse = function(){
	//	default section to keep expanded
	var doNotHide = '.planning';
	//	is the URL hashed?
	if(location.hash){
		var subSectionToShow = location.hash.substr(1);
		doNotHide = $('a[name="' + subSectionToShow + '"]').closest('.collapsible');
	}
	//	collapse elements
	$('.collapsible').not(doNotHide).each(function(index, element) {
        $(this).hide().prev().addClass('collapsed');
    });
	
};