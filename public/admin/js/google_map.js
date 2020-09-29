$(document).ready(function(e) {
	$('form').find('#pac-input').keypress(function(e){
		if ( e.which == 13 ) // Enter key = keycode 13
		{
			$(this).next().focus();  //Use whatever selector necessary to focus the 'next' input
			return false;
		}
	});

	var coor = '27.700855895121396, 85.28978346679685';
	if(document.getElementById('coordinates').value != '' && document.getElementById('coordinates').value != 0){
		coor = document.getElementById('coordinates').value
	}
	var latlngStr = coor.split(",",2);
	var lat = parseFloat(latlngStr[0]);
	var lng = parseFloat(latlngStr[1]);
	var latlng = new google.maps.LatLng(lat, lng);

	var infowindow = new google.maps.InfoWindow({
		maxWidth: 200
	});

	var map = new google.maps.Map(document.getElementById('map'), {
		center: latlng,
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
	});
	var geocoder = new google.maps.Geocoder();
	var elevator = new google.maps.ElevationService();
	var marker;

	placeMarker(latlng);
	executeFunctions(latlng);

	google.maps.event.addListener(marker, 'dragend', function(a) {
		executeFunctions(a.latLng);
		document.getElementById('coordinates').value = a.latLng;
		// a.latLng contains the co-ordinates where the marker was dropped
	});

	google.maps.event.addListener(map, 'click', function(event){
		executeFunctions(event.latLng);
		document.getElementById('coordinates').value = event.latLng;
	});

	function executeFunctions(loc){
		setMarker(loc);
		codeAddress(loc);
		getElevation(loc);
	}

	function placeMarker(location) {
		marker = new google.maps.Marker({
			position: location,
			map: map,
			title: 'Set lat/lon values for this property',
			draggable: true,
			animation: google.maps.Animation.DROP

		});
		//map.setCenter(location);
	}

	function setMarker(loc){
		marker.setPosition(loc);
		map.setCenter(loc);
	}

	function codeAddress(lat) {
		geocoder.geocode({'latLng': lat}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if(results[1]) {
					infowindow.setContent(results[1].formatted_address+'<br /><b>Latitude: </b>'+results[1].geometry.location.lat()+'<br /><b>Longitude: </b>'+results[1].geometry.location.lng());
					infowindow.open(map, marker);
				}
			}
		});
	}

	function getElevation(latlag) {

		var locations = [];

		// Retrieve the clicked location and push it on the array
		//var clickedLocation = event.latLng;
		locations.push(latlag);

		// Create a LocationElevationRequest object using the array's one value
		var positionalRequest = {
			'locations': locations
		}

		// Initiate the location request
		elevator.getElevationForLocations(positionalRequest, function(results, status) {
			if (status == google.maps.ElevationStatus.OK) {

				// Retrieve the first result
				if (results[0]) {
					//document.getElementById('pmAlti').value = results[0].elevation+' meters';
				} else {
					alert('No results found');
				}
			} else {
				alert('Elevation service failed due to: ' + status);
			}
		});
	}


	var markers = [];
	// Create the search box and link it to the UI element.
	var input = /** @type {HTMLInputElement} */(
			document.getElementById('pac-input'));
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	var searchBox = new google.maps.places.SearchBox(
			/** @type {HTMLInputElement} */(input));

	// [START region_getplaces]
	// Listen for the event fired when the user selects an item from the
	// pick list. Retrieve the matching places for that item.
	google.maps.event.addListener(searchBox, 'places_changed', function() {
		var places = searchBox.getPlaces();

		if (places.length == 0) {
			return;
		}
		for (var i = 0, marker; marker = markers[i]; i++) {
			marker.setMap(null);
		}

		// For each place, get the icon, place name, and location.
		markers = [];
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0, place; place = places[i]; i++) {
			var image = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25)
			};

			// Create a marker for each place.
			marker = new google.maps.Marker({
				map: map,
				icon: image,
				title: place.name,
				position: place.geometry.location
			});

			markers.push(marker);

			bounds.extend(place.geometry.location);
		}

		map.fitBounds(bounds);
	});
	// [END region_getplaces]

	// Bias the SearchBox results towards places that are within the bounds of the
	// current map's viewport.
	google.maps.event.addListener(map, 'bounds_changed', function() {
		var bounds = map.getBounds();
		searchBox.setBounds(bounds);
	});
});