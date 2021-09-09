<?php

require_once __DIR__.'/config.php';

$user_id = $_POST["user_id"];
$job_id = $_POST["job_id"];
$price = $_POST["price"]; 
$proposal = $_POST["proposal"];
$currentTimeinSeconds = time(); 

$previous_job_requests = array();

$sql0 = "SELECT job_id FROM job_requests WHERE user_id = '$user_id'";
$result0 = $conn -> prepare($sql0);

$sql1 = "INSERT INTO job_requests(user_id, job_id, bidding_amount, proposal, request_date) VALUES (?, ?, ?, ?, ?)";
$result1 = $conn -> prepare($sql1);

while($row = $result0 -> fetch()) {

	$request = $row["job_id"];
	array_push($previous_job_requests, $request);

}

if(in_array($job_id, $previous_job_requests)) {

	echo json_encode(['error' => true, 'message' => "You already applied for this job"]);
	exit();

} else {

	if($result1 -> execute([$user_id, $job_id, $price, $proposal, $currentTimeinSeconds])) {

		echo json_encode(['error' => false, 'message' => "Job application was successfull"]);

	} else {

		echo json_encode(['error' => true, 'message' => "Job application failed. Please try again later."]);

	}
}

$conn = null;

?>