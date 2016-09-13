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
    echo "Could not set characterset connection: " ;
    exit;
}

$sql = "SELECT `index`, `phoneNumber`, `numTower`, `numLine`, `phaseAmax`, `phaseBmax`, `phaseCmax`, `tros1max`, `tros2max` FROM `monitoringdata`.`post_parameters` WHERE `post_parameters`.`index`=" . $_GET["my_index"];
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
$index = array();
$dateOfMeasurement = array();
$F0[] = array();
$F1[] = array();
$F2[] = array();
$F3[] = array();
$F4[] = array();

$phaseAmax[] = array();
$phaseBmax[] = array();
$phaseCmax[] = array();
$tros1max[] = array();
$tros2max[] = array();

while ($row = mysqli_fetch_assoc($result)) {
	$phoneNumber[] = $row['phoneNumber'];
	$numTower[] = $row['numTower'];
	$numLine[] = $row['numLine'];
	$index[] = $row['index'];

	$phaseAmax[] = $row['phaseAmax'];
	$phaseBmax[] = $row['phaseBmax'];
	$phaseCmax[] = $row['phaseCmax'];
	$tros1max[] = $row['tros1max'];
	$tros2max[] = $row['tros2max'];
}


$returned = array();

$returned['phoneNumber'] = $phoneNumber[0];
$returned['numTower'] = $numTower[0];
$returned['numLine'] = $numLine[0];
$returned['phaseAmax'] = $phaseAmax[1];
$returned['phaseBmax'] = $phaseBmax[1];
$returned['phaseCmax'] = $phaseCmax[1];
$returned['tros1max'] = $tros1max[1];
$returned['tros2max'] = $tros2max[1];
$returned['index'] = $index[0];

$sql = "SELECT * FROM `monitoringdata`.`monitoringtable` where phoneNumber=" . $returned['phoneNumber'] . " ORDER BY `monitoringtable`.`dateOfMeasurement` DESC LIMIT 1";
//echo $sql . "\n";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_assoc($result)) {
	$dateOfMeasurement[] = $row['dateOfMeasurement'];
	$F0[] = $row['F0'];
	$F1[] = $row['F1'];
	$F2[] = $row['F2'];
	$F3[] = $row['F3'];
	$F4[] = $row['F4'];
}


$returned['dateOfMeasurement'] = $dateOfMeasurement[0];
$returned['phaseA'] = $F0[1];
$returned['phaseB'] = $F1[1];
$returned['phaseC'] = $F2[1];
$returned['tros1'] = $F3[1];
$returned['tros2'] = $F4[1];

echo json_encode($returned);
mysqli_free_result($result);
mysqli_close($db);

?>
