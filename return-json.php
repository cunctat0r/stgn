<?php 


//$date_to_select = $_GET[date];
$date_begin = $_GET[date_begin];
$date_end = $_GET[date_end];
$phone = $_GET[phone_selected];


//$correct_date =  date("Y-m-d", strtotime($date_to_select));
$correct_date_begin =  date("Y-m-d", strtotime($date_begin));
$correct_date_end =  date("Y-m-d", strtotime($date_end));

function correct_value($value) {
	return (($value == -1000) || ($value == -500))? 0 : $value;
}

$host="127.0.0.1";
$user="root";
$pass="LazyDog";
$dbname="monitoringdata";
$db = mysql_connect($host,$user,$pass);

if (!mysql_select_db($dbname)) {
//    echo "Unable to select mydbname: " . mysql_error();
    exit;
}

$sql = "SELECT `dateOfMeasurement`, `F0`, `F1`, `F2`, `T0`, `T1`, `T2`, `vlagn` FROM `monitoringtable` WHERE (dateOfMeasurement BETWEEN '" . $correct_date_begin . " 00:00:00' AND '" . $correct_date_end . " 23:59:59') AND (phoneNumber='" . $phone . "') ORDER BY `monitoringtable`.`index` DESC";
//echo $sql;
//exit;

$result=mysql_query($sql);
if (!$result) {
//    echo "Could not successfully run query from DB: " . mysql_error();
    exit;
}

if (mysql_num_rows($result) == 0) {
//    echo "No rows found, nothing to print so am exiting";
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

while ($row = mysql_fetch_assoc($result))
{
	$date[] = strtotime($row['dateOfMeasurement']) * 1000;
	//$phoneNumber[] = $row['phoneNumber'];
	//$phoneNumber[] = $phone;
	$F0[] = correct_value($row['F0']);
	$F1[] = correct_value($row['F1']);
	$F2[] = correct_value($row['F2']);
	//$F3[] = correct_value($row['F3']);
	//$F4[] = correct_value($row['F4']);
	$T0[] = correct_value($row['T0']);
	$T1[] = correct_value($row['T1']);
	$T2[] = correct_value($row['T2']);
	//$T3[] = correct_value($row['T3']);
	//$T4[] = correct_value($row['T4']);
	$humid[] = correct_value($row['vlagn']);
}
$returned = array();
	
$returned["date"] = $date;
//$returned["phoneNumber"] = $phoneNumber;
$returned["F0"] = $F0;
$returned["F1"] = $F1;
$returned["F2"] = $F2;
//$returned["F3"] = $F3;
//$returned["F4"] = $F4;
$returned["T0"] = $T0;
$returned["T1"] = $T1;
$returned["T2"] = $T2;
//$returned["T3"] = $T3;
//$returned["T4"] = $T4;
$returned["humid"] = $humid;
//$returned["par"] = $sql;

	
echo json_encode($returned);

mysql_free_result($result);
mysql_close($db);
?>
