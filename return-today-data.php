<?php 

$correct_date =  date("Y-m-d");

function correct_value($value) {
	return (($value == -1000) || ($value == -500))? 0 : $value;	
}

$host="127.0.0.1";
$user="frost";
$pass="frost";
$dbname="monitoringdata";
$db = mysqli_connect($host,$user,$pass);


if (!mysqli_select_db($db, $dbname)) {
//    echo "Unable to select mydbname: ";
    exit;
}


$sql = "SELECT * FROM `monitoringtable` WHERE dateOfMeasurement BETWEEN '" . $correct_date . " 00:00:00' AND '" . $correct_date . " 23:59:59' ORDER BY `monitoringtable`.`index` ASC";
//echo $sql;

$result=mysqli_query($db, $sql);
if (!$result) {
    //echo "Could not successfully run query from DB: " ;
    exit;
}

if (mysqli_num_rows($result) == 0) {
    //echo "No rows found, nothing to print so am exiting";
    exit;
}

$date = array();
$phoneNumber = array();
$F0 = array();
$F1 = array();
$F2 = array();
$F3 = array();
$F4 = array();
$T0 = array();
$T1 = array();
$T2 = array();
$T3 = array();
$T4 = array();
$humid = array();

while ($row = mysqli_fetch_assoc($result))
{
	$date[] = strtotime($row['dateOfMeasurement']) * 1000;
	$phoneNumber[] = $row['phoneNumber'];
	$F0[] = correct_value($row['F0']);
	$F1[] = correct_value($row['F1']);
	$F2[] = correct_value($row['F2']);
	$F3[] = correct_value($row['F3']);
	$F4[] = correct_value($row['F4']);
	$T0[] = correct_value($row['T0']);
	$T1[] = correct_value($row['T1']);
	$T2[] = correct_value($row['T2']);
	$T3[] = correct_value($row['T3']);
	$T4[] = correct_value($row['T4']);
	$humid[] = correct_value($row['vlagn']);
}
$returned = array();

$returned["date"] = $date;
$returned["phoneNumber"] = $phoneNumber;
$returned["F0"] = $F0;
$returned["F1"] = $F1;
$returned["F2"] = $F2;
$returned["F3"] = $F3;
$returned["F4"] = $F4;
$returned["T0"] = $T0;
$returned["T1"] = $T1;
$returned["T2"] = $T2;
$returned["T3"] = $T3;
$returned["T4"] = $T4;
$returned["humid"] = $humid;
//$returned["par"] = $sql;

	
echo json_encode($returned);

mysqli_free_result($result);
mysqli_close($db);
?>
