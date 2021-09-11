<?php

require_once __DIR__.'/config.php';

$employer_id = $_POST["employer_id"];
$job_id = $_POST["job_id"];
$worker_id = $_POST["worker_id"];
$currentTimeinSeconds = time(); 

$sql0 = "INSERT INTO work_ratings (job_id, worker_id, employer_id, job_start_date) VALUES (?, ?, ?, ?)";
$result0 = $conn -> prepare($sql0);

$sql1 = "DELETE FROM job_requests WHERE job_id = '$job_id'";
$result1 = $conn -> prepare($sql1);

$sql2 = "UPDATE jobs SET status = 'taken' WHERE job_id = '$job_id'";
$result2 = $conn -> prepare($sql2);

if($result0 -> execute([$job_id, $worker_id, $employer_id, $currentTimeinSeconds])) {

	$result1 -> execute();
	$result2 -> execute();
	echo json_encode(['error' => false, 'message' => "Recruit was successfull.\nYou can rate the job once it is done"]);

} else {

	echo json_encode(['error' => true, 'message' => "An error occured. Please try again later"]);
}

$conn = null;

?>