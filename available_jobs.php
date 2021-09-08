<?php
// An associative array for the response
$response = array();

require_once __DIR__.'/config.php';

$sql0 = "SELECT jobs.job_id, users.firstname, users.lastname, jobs.job_description, jobs.post_date, specialty.specialty_name, users.phone_number, users.longitude, users.latitude 
FROM jobs 
inner join specialty on jobs.job_specialty = specialty.specialty_id 
inner join users on jobs.user_id = users.user_id";

$result0 = $conn->prepare($sql0);
$result0 -> execute();

if($result0 -> rowCount() > 0) {
	
	// Node indicates whether there are any jobs available
	$response["message"] = "true";

	// Node for each job available on the database
	$response["available_job"] = array();

	while($row = $result0->fetch()) {

		$job = array();

		$job["job_id"] = $row["job_id"];
		$job["firstname"] = $row["firstname"];
		$job["lastname"] = $row["lastname"];
		$job["job_description"] = $row["job_description"];
		$job["post_date"] = date('Y/m/d H:i', $row["post_date"]);
		$job["job_specialty"] = $row["specialty_name"];
		$job["employer_phone"] = $row["phone_number"];
		$job["longitude"] = $row["longitude"];
		$job["latitude"] = $row["latitude"];

		// appending a job array at the avaiable_job every time
		array_push($response["available_job"], $job);
	}
	echo json_encode($response);
}
else {
	$response["message"] = "false";
	echo json_encode($response);
}

$conn = null;

?>