<?php
$db = mysqli_connect("localhost", "frost", "frost");
// This establishes a link to MySQL
if (!mysqli_select_db($db, "power_lines")) {
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

$sql = "SELECT * FROM `monitoringdata`.`power_lines`";
$result = mysqli_query($db, $sql);
if (!$result) {
	echo "Error in query ";
	exit;
}

if (mysqli_num_rows($result) == 0) {
	echo "No rows found";
	exit;
}

while ($row = mysqli_fetch_assoc($result)) {
	$rows[] = $row
}

echo json_encode($rows);

mysqli_free_result($result);
mysqli_close($db);

?>
