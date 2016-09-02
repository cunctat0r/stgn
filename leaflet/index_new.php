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
var tower = new Object();

var map = new L.Map('map');

var map_layer = new L.TileLayer('http://192.168.1.175/osm/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
    maxZoom: 18
});

var middleMap = new L.LatLng(48.8377, 43); // geographical point (longitude and latitude)
map.setView(middleMap, 7).addLayer(map_layer);


function get_towers () {
	$.ajax({
		url: 'get_towers.php',
		dataType: 'json',
		success: function (hash_table) {
			process_towers(hash_table);
		},
		cache: false,
		ifModified: true
	});
}

function process_towers (in_data) {
	var my_towers = eval(in_data);
	if (my_towers != undefined) {
		
		for (var the_tower in my_towers) {
			tower.latitude = the_tower.latitude;
			tower.longitude = the_tower.longitude;
		}
		
		var num_of_towers = my_towers.index.length;
		for (i = 0; i < num_of_towers; i++) {
			tower[i] = {'latitude': my_towers.latitude[i],
				'longitude': my_towers.longitude[i],
				'numLine' : my_towers.numLine[i],
				'numTower': my_towers.numTower[i],
				'index': my_towers.index[i]
			};
//			get_tower_data(tower[i]);
			add_tower(tower[i]);
		}
	} else {
		return -1;
	}
//	setTimeout(get_towers, 60000);
//setInterval('get_towers()', 60000);
}


$(document).ready(function () {
	get_towers();
//	for (i = 0; i < tower.length; i++) {
//		get_tower_data(tower[i]);
//	}
});

function get_tower_data(tower_index) {
	var text = "";

	$.ajax({
		url: 'get_tower_data.php',
		data: {my_index : tower_index},
		dataType: 'json',
		success: function (hash_table) {
				var text = ""
				var tower_current = process_tower_data(hash_table);
				text = "Линия " + tower_current.numLine + "</br>" + "Опора " + tower_current.numTower;
	
 				marker[tower_index].bindPopup(text);
    				
			
			},
		cache: false,
		ifModified: true
	});

}


function process_tower_data (in_data) {
	var new_tower_data = eval(in_data);
	var processed_data;
	if (new_tower_data != undefined) {
		//console.log(new_tower_data);
		processed_data = {
			'numLine': new_tower_data.numLine,
			'numTower': new_tower_data.numTower
			
		};
		return processed_data;
	}
}

function add_tower (tower1) {

	var markerLocation = new L.LatLng(tower1.latitude, tower1.longitude);
	marker[tower1.index] = new L.Marker(markerLocation);
	map.addLayer(marker[tower1.index]);
//	marker[tower1.index].bindPopup(get_tower_data(tower1.index));
//	marker[tower1.index].bindPopup("Это опора");
//		get_tower_data(6);
	//marker[tower1.index].click = get_tower_data(tower1.index);
	marker[tower1.index].on("click", marker[tower1.index].bindPopup(get_tower_data(tower1.index)));

}




</script>


 
</body>
</html>
