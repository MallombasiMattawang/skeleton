
var map;
var geocoder;
var marker;
var markersArray = [];

function initialize()
{
	geocoder = new google.maps.Geocoder();

	var myLatlng = new google.maps.LatLng(-6.176655999999999, 106.83058389999997);
	var mapOptions = {
		center: myLatlng,
		zoom: 14
	};

	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: 'Jakarta'
	});

	markersArray.push(marker);
	google.maps.event.addListener(marker, "click", function () {
	});
}

function clearOverlays()
{
	for (var i = 0; i < markersArray.length; i++)
	{
		markersArray[i].setMap(null);
	}

	markersArray.length = 0;

	document.getElementById("map-position").innerHTML = '';

}

/**
 * show location on map
 *
 * @param {string} address format: district, subdistrict
 * @param {string} title
 * @returns {undefined}
 */
function showCoordinate(address, title)
{
	geocoder.geocode({'address': address}, function (results, status)
	{
		if (status === google.maps.GeocoderStatus.OK)
		{
			clearOverlays();

			var position = results[0].geometry.location;

			map.setCenter(results[0].geometry.location);
			marker = new google.maps.Marker({
				'map': map,
				'position': results[0].geometry.location,
				'title': title
			});
			markersArray.push(marker);
			google.maps.event.addListener(marker, "click", function () {
			});


			document.getElementById("map-position").innerHTML = 'Lat: ' + position.lat() + '; Long: ' + position.lng();
		}
		else
		{
			document.getElementById("map-position").innerHTML = 'Geocode was not successful for the following reason: ' + status;
		}
	});
}

google.maps.event.addDomListener(window, 'load', initialize);