<html>
<head>
<meta http-equiv="Content-Type" content="text/html; Charset=UTF-8">

<link rel="stylesheet" href="./dist/leaflet.css" />
<!--[if lte IE 8]>
    <link rel="stylesheet" href="./dist/leaflet.ie.css" />
<![endif]-->

<script src="./dist/leaflet.js"></script>

<script src="./jquery/jquery-1.4.2.min.js"></script>
</head>
<body>


<div id="map" style="height: 100%"></div>
<script>
var tower = [];
var tower_data = [];
var marker = [];
var popup_text = [];
var map = new L.Map('map');

var map_layer = new L.TileLayer('../osm_tiles/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
    maxZoom: 18
});

var middleMap = new L.LatLng(53.00, 51.3); // geographical point (longitude and latitude)
//map.setView(middleMap, 9).addLayer(map_layer);
map.setView(middleMap, 9);
map_layer.addTo(map);

//Define an array of Latlng objects (points along the line)
var polylinePoints = [
    new L.LatLng(53.23358, 50.67567),
    new L.LatLng(53.23754, 50.72426),
    new L.LatLng(53.20300, 50.82734),
    new L.LatLng(53.23348, 50.83463),
    new L.LatLng(53.20218, 50.82957),
    new L.LatLng(53.15810, 50.95729),
    new L.LatLng(53.15254, 50.03805),
    new L.LatLng(53.07547, 51.29117),
    new L.LatLng(53.03595, 51.32816),
    new L.LatLng(52.92800, 51.95224),
    new L.LatLng(52.89156, 51.92279),
    new L.LatLng(52.86703, 52.00339),
    new L.LatLng(52.86051, 52.00948),
    new L.LatLng(52.83568, 52.08467),
    new L.LatLng(52.82095, 52.08004),
    new L.LatLng(52.78378, 52.11935),
    new L.LatLng(52.78707, 52.16063),
    new L.LatLng(52.74360, 52.20226),
    new L.LatLng(52.74386, 52.20578),
    new L.LatLng(52.76370, 52.23659),
    new L.LatLng(52.76692, 52.23736),
    new L.LatLng(52.77139, 52.25393),
];

var polylineOptions = {
   color: 'blue',
   weight: 3,
   opacity: 0.9
 };

var polyline = new L.Polyline(polylinePoints, polylineOptions);

map.addLayer(polyline);   

function get_towers () {
	$.ajax({
		url: 'get_towers.php',
		dataType: 'json',
		success: function (hash_table) {
			process_data(hash_table);
		},
		cache: false,
		ifModified: true
	});
}

function process_data (in_data) {
	var my_towers = eval(in_data);
	if (my_towers != undefined) {
		var num_of_towers = my_towers.index.length;
		for (i = 0; i < num_of_towers; i++) {
			tower[i] = {'latitude': my_towers.latitude[i],
				'longitude': my_towers.longitude[i],
				'numLine' : my_towers.numLine[i],
				'numTower': my_towers.numTower[i],
				'index': my_towers.index[i]
			};
			add_tower(tower[i]);
		}
	} else {
		return -1;
	}
//	setTimeout(get_towers, 60000);
}


$(document).ready(function () {
	get_towers();      
});

function isFrost(tower, phase) {
	var phaseWeight, phaseMax, phaseLow;


	switch (phase) {
		case "phaseA": {phaseWeight = 1.0 * tower.phaseA; phaseMax = 1.0 * tower.phaseAmax; break;}
		case "phaseB": {phaseWeight = 1.0 * tower.phaseB; phaseMax = 1.0 * tower.phaseBmax; break;}
		case "phaseC": {phaseWeight = 1.0 * tower.phaseC; phaseMax = 1.0 * tower.phaseCmax; break;}
		case "tros1": {phaseWeight = 1.0 * tower.tros1; phaseMax = 1.0 * tower.tros1max; break;}
		case "tros2": {phaseWeight = 1.0 * tower.tros2; phaseMax = 1.0 * tower.tros2max; break;}
	}

	phaseLow = 0.75 * phaseMax;
	if (phaseWeight > phaseMax) {
		return "<b>Опасный гололед</b>";
	} else if (phaseWeight > phaseLow) {
		return "<b>Гололед</b>";
	} else {
		return "";
	}
}

function get_tower_data(tower_index) {
	var text = "";

	$.ajax({
		url: 'get_tower_data.php',
		data: {my_index : tower_index},
		dataType: 'json',
		success: function (hash_table) {
				var text = ""
				var tower_current = process_tower_data(hash_table);
				text = "<b>Линия</b> " + tower_current.numLine + "</br>" + "Опора " + tower_current.numTower + "</br>";
				text = text + "<b>Фаза А:</b> " + tower_current.phaseA + " кгс " + isFrost(tower_current, "phaseA") + "</br>";
				text = text + "<b>Фаза B:</b> " + tower_current.phaseB + " кгс " + isFrost(tower_current, "phaseB") + "</br>";
				text = text + "<b>Фаза C:</b> " + tower_current.phaseC + " кгс " + isFrost(tower_current, "phaseC") + "</br>";
				text = text + "<b>Трос 1:</b> " + tower_current.tros1 + " кгс " + isFrost(tower_current, "tros1") + "</br>";
				text = text + "<b>Трос 2:</b> " + tower_current.tros2 + " кгс " + isFrost(tower_current, "tros2") + "</br>";
 				marker[tower_index].bindPopup(text);
				marker[tower_index].openPopup();


			},
		cache: false,
		ifModified: true
	});

}


function process_tower_data (in_data) {
	var new_tower_data = eval(in_data);
	var processed_data;
	if (new_tower_data != undefined) {
		processed_data = {
			'numLine': new_tower_data.numLine,
			'numTower': new_tower_data.numTower,
			'phaseA' : new_tower_data.phaseA,
			'phaseB' : new_tower_data.phaseB,
			'phaseC' : new_tower_data.phaseC,
			'tros1' : new_tower_data.tros1,
			'tros2' : new_tower_data.tros2,
			'phaseAmax' : new_tower_data.phaseAmax,
			'phaseBmax' : new_tower_data.phaseBmax,
			'phaseCmax' : new_tower_data.phaseCmax,
			'tros1max' : new_tower_data.tros1max,
			'tros2max' : new_tower_data.tros2max
		};
		return processed_data;
	}
}

function add_tower (tower1) {

	var markerLocation = new L.LatLng(tower1.latitude, tower1.longitude);
	marker[tower1.index] = new L.Marker(markerLocation);
	map.addLayer(marker[tower1.index]);
	marker[tower1.index]._myId = tower1.index;

	marker[tower1.index].on('click', function(e) {
			get_tower_data(tower1.index);
		}
	);


}




</script>


 
</body>
</html>
