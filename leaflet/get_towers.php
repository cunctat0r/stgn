<?php
$db = mysqli_connect("localhost", "frost", "frost");
// This establishes a link to MySQL
if (!mysqli_select_db($db, "monitoringdata")) {
	echo "Unable to select db: ";
	exit;
}

$query = "SET NAMES 'utf8'";
$result=mysqli_query($db, $query);
if (!$result) {
    echo "Could not set names: ";
    exit;
}

$query = "SET character_set_client='utf8'";
$result=mysqli_query($db, $query);
if (!$result) {
    echo "Could not set characterset client: ";
    exit;
}

$query = "SET character_set_connection='utf8'";
$result=mysqli_query($db, $query);
if (!$result) {
    echo "Could not set characterset connection: ";
    exit;
}

$sql = "SELECT * FROM `monitoringdata`.`post_parameters` WHERE baseNet='Ростов-на-Дону'";
$result = mysqli_query($db, $sql);
if (!$result) {
	echo "Error in query ";
	exit;
}

if (mysqli_num_rows($result) == 0) {
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


while ($row = mysqli_fetch_assoc($result)) {
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
mysqli_free_result($result);
mysqli_close($db);

?>
