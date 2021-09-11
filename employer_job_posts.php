<?php

// An associative array for the response
$response = array();

require_once __DIR__.'/config.php';

$user_id = $_POST["user_id"];

$sql0 = "SELECT jobs.job_id, jobs.job_description, jobs.post_date, specialty.specialty_name
FROM jobs 
inner join specialty on jobs.job_specialty = specialty.specialty_id 
WHERE jobs.user_id = '$user_id' AND status = 'pending'";

$result0 = $conn->prepare($sql0);
$result0 -> execute();

if($result0 -> rowCount() > 0) {
	
	// Node indicates whether user posted anything yet
	$response["message"] = "true";

	// Node for each job posted by a user
	$response["posted_job"] = array();

	while($row = $result0->fetch()) {

		$job = array();

		$job["job_id"] = $row["job_id"];
		$job["job_description"] = $row["job_description"];
		$job["post_date"] = date('Y/m/d H:i', $row["post_date"]);
		$job["job_specialty"] = $row["specialty_name"];

		// appending a job array at the posted_job node every time
		array_push($response["posted_job"], $job);
	}
	echo json_encode($response);
}
else {
	$response["message"] = "false";
	echo json_encode($response);
}

$conn = null;

?>