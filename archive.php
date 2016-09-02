<?php

$host="127.0.0.1";
$user="frost";
$pass="frost";
$dbname="monitoringdata";
$dbtable = "post_parameters";
$db = mysqli_connect($host,$user,$pass);


if (!mysqli_select_db($db, $dbname)) {
    //echo "Unable to select mydbname: ";
    exit;
}

$query = "SET NAMES 'utf8'";
$result=mysqli_query($db, $query);
if (!$result) {
    //echo "Could not set names: ";
    exit;
}

$query = "SET character_set_client='utf8'";
$result=mysqli_query($db, $query);
if (!$result) {
    //echo "Could not set characterset client: ";
    exit;
}

$query = "SET character_set_connection='utf8'";
$result=mysqli_query($db, $query);
if (!$result) {
    //echo "Could not set characterset connection: ";
    exit;
}

$sql = "SELECT * FROM " . $dbtable . " ORDER BY numLine ASC";

$result=mysqli_query($db, $sql);
if (!$result) {
    //echo "Could not successfully run query from DB: ";
    exit;
}

if (mysqli_num_rows($result) == 0) {
    //echo "No rows found, nothing to print so am exiting";
    exit;
}



while ($row = mysqli_fetch_assoc($result))
{
	$returned[] = array(
					"phoneNumber" => $row['phoneNumber'],
					"numOpora" => $row['numTower'],
					"numLine" => $row['numLine']
					);
}

mysqli_free_result($result);
mysqli_close($db);


include_once "/templates/view-archive.php";

