<?php 
require_once __DIR__.'/config.php';

$longitude = $_POST["longitude"];
$latitude = $_POST["latitude"];
$user_id = $_POST["user_id"];

$sql_update = "UPDATE users SET longitude = '$longitude', latitude = '$latitude' WHERE user_id = '$user_id'";
$result_update = $conn -> prepare($sql_update);
$result_update -> execute();


$sql_retrieve = "SELECT longitude, latitude FROM users WHERE user_id = '$user_id'";
$result_retrieve = $conn -> prepare($sql_retrieve);
$result_retrieve -> execute();

if($result_retrieve -> rowCount() > 0) {
	while($row = $result_retrieve -> fetch()) {
		$longitude = $row["longitude"];
		$latitude = $row["latitude"];
		echo json_encode(['error' => false, 'message' => "Location updated successfully", 'longitude' => $longitude, 'latitude' => $latitude]);
	}
}  else {
	echo json_encode(['error' => false, 'message' => "Location update failed", 'longitude' => $longitude, 'latitude' => $latitude]);
}

?>