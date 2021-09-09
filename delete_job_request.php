<?php

require_once __DIR__.'/config.php';

$job_id = $_POST["job_id"];
$user_id = $_POST["user_id"];

$sql1 = "DELETE FROM job_requests WHERE job_id = '$job_id' AND user_id = '$user_id'";
$result1 = $conn -> prepare($sql1);

if($result1 -> execute()) {

	echo json_encode(['error' => false, 'message' => "Request was successfully deleted"]);

} else {

	echo json_encode(['error' => true, 'message' => "Error deleting request"]);

}

$conn = null;

?>