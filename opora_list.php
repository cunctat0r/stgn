<?php

$host="127.0.0.1";
$user="root";
$pass="LazyDog";
$dbname="monitoringdata";
$dbtable = "post_parameters";
$db = mysql_connect($host,$user,$pass);

if (!mysql_select_db($dbname)) {
    //echo "Unable to select mydbname: " . mysql_error();
    exit;
}

$query = "SET NAMES 'utf8'";
$result=mysql_query($query);
if (!$result) {
    //echo "Could not set names: " . mysql_error();
    exit;
}

$query = "SET character_set_client='utf8'";
$result=mysql_query($query);
if (!$result) {
    //echo "Could not set characterset client: " . mysql_error();
    exit;
}

$query = "SET character_set_connection='utf8'";
$result=mysql_query($query);
if (!$result) {
    //echo "Could not set characterset connection: " . mysql_error();
    exit;
}

$sql = "SELECT * FROM " . $dbtable . " WHERE baseNet='Ростов-на-Дону' ORDER BY numLine";

$result=mysql_query($sql);
if (!$result) {
    //echo "Could not successfully run query from DB: " . mysql_error();
    exit;
}

if (mysql_num_rows($result) == 0) {
    //echo "No rows found, nothing to print so am exiting";
    exit;
}



while ($row = mysql_fetch_assoc($result))
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

mysql_free_result($result);
mysql_close($db);


include_once("./templates/view-opora_list.php");
