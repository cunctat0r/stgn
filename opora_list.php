<?php

$host="127.0.0.1";
$user="frost";
$pass="frost";
$dbname="monitoringdata";
$dbtable = "post_parameters";
$db = mysqli_connect($host,$user,$pass);

if (!mysqli_select_db($dbname)) {
    //echo "Unable to select mydbname: " . mysql_error();
    exit;
}

$query = "SET NAMES 'utf8'";
$result=mysqli_query($query);
if (!$result) {
    //echo "Could not set names: " . mysql_error();
    exit;
}

$query = "SET character_set_client='utf8'";
$result=mysqli_query($query);
if (!$result) {
    //echo "Could not set characterset client: " . mysql_error();
    exit;
}

$query = "SET character_set_connection='utf8'";
$result=mysqli_query($query);
if (!$result) {
    //echo "Could not set characterset connection: " . mysql_error();
    exit;
}

$sql = "SELECT * FROM " . $dbtable . " ORDER BY numLine";

$result=mysqli_query($sql);
if (!$result) {
    //echo "Could not successfully run query from DB: " . mysql_error();
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
					"numLine" => $row['numLine'],
					"phaseAmaxWeight" => $row['phaseAmaxWeight'],
					"phaseBmaxWeight" => $row['phaseBmaxWeight'],
					"phaseCmaxWeight" => $row['phaseCmaxWeight'],
					"receiver1" => $row['receiver1'],
					"receiver2" => $row['receiver2']
					);
}

mysqli_free_result($result);
mysqli_close($db);


include_once("./templates/view-opora_list.php");
