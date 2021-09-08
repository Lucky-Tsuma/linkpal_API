<?php
require_once __DIR__.'/config.php';
date_default_timezone_set("Africa/Nairobi");

$user_id = $_POST["user_id"];
$job_description = $_POST["job_description"];
$job_specialty = $_POST["job_specialty"];
$currentTimeinSeconds = time(); 

$sql = "INSERT INTO jobs (user_id, job_description, post_date, job_specialty) VALUES (?, ?, ?, ?)";
$result = $conn -> prepare($sql);

if ($result -> execute([$user_id, $job_description, $currentTimeinSeconds, $job_specialty])){
	echo json_encode(['error' => false, 'message' => "Job was successfully posted", 'post' => $_POST]);
} else {
	echo json_encode(['error' => true, 'message' => "Upload failed", 'post' => $_POST]);
}

$conn = null;

?>


