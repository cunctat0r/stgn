<?php
$db = mysql_connect("localhost", "root", "LazyDog");
// This establishes a link to MySQL
if (!mysql_select_db("monitoringdata")) {
	echo "Unable to select db: " . mysql_error();
	exit;
}

$query = "SET NAMES 'utf8'";
$result=mysql_query($query);
if (!$result) {
    echo "Could not set names: " . mysql_error();
    exit;
}

$query = "SET character_set_client='utf8'";
$result=mysql_query($query);
if (!$result) {
    echo "Could not set characterset client: " . mysql_error();
    exit;
}

$query = "SET character_set_connection='utf8'";
$result=mysql_query($query);
if (!$result) {
    echo "Could not set characterset connection: " . mysql_error();
    exit;
}

$sql = "SELECT * FROM `monitoringdata`.`post_parameters` WHERE baseNet='Ростов-на-Дону'";
$result = mysql_query($sql);
if (!$result) {
	echo "Error in query " . mysql_error();
	exit;
}

if (mysql_num_rows($result) == 0) {
	echo "No rows found";
	exit;
}

$phoneNumber = array();
$numTower = array();
$numLine = array();
$latitude = array();
$longitude = array();
$index = array();
$phaseAmax = array();
$phaseBmax = array();
$phaseCmax = array();
$tros1max = array();
$tros2max = array();


while ($row = mysql_fetch_assoc($result)) {
	$phoneNumber[] = $row['phoneNumber'];
	$numTower[] = $row['numTower'];
	$numLine[] = $row['numLine'];
	$latitude[] = $row['latitude'];
	$longitude[] = $row['longitude'];
	$phaseAmax[] = $row['phaseAmax'];
	$phaseBmax[] = $row['phaseBmax'];
	$phaseCmax[] = $row['phaseCmax'];
	$tros1max[] = $row['tros1max'];
	$tros2max[] = $row['tros2max'];
	
	$index[] = $row['index'];
}


$returned = array();

$returned['phoneNumber'] = $phoneNumber;
$returned['numTower'] = $numTower;
$returned['numLine'] = $numLine;
$returned['latitude'] = $latitude;
$returned['longitude'] = $longitude;

$returned['phaseAmax'] = $phaseAmax;
$returned['phaseBmax'] = $phaseBmax;
$returned['phaseCmax'] = $phaseCmax;
$returned['tros1max'] = $tros1max;
$returned['tros2max'] = $tros2max;

$returned['index'] = $index;

echo json_encode($returned);
mysql_free_result($result);
mysql_close($db);

?>
