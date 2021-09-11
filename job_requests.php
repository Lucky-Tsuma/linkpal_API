<?php

// An associative array for the response
$response = array();

require_once __DIR__.'/config.php';

$user_id = $_POST["employer_id"];

$sql = "SELECT job_requests.job_id, job_requests.user_id,  job_requests.bidding_amount, job_requests.proposal, users.firstname, users.lastname,  users.longitude, users.latitude, specialty.specialty_name, job_requests.request_date, users.phone_number
FROM job_requests
inner join users on job_requests.user_id = users.user_id
inner join jobs on job_requests.job_id = jobs.job_id 
inner join specialty on jobs.job_specialty = specialty.specialty_id 
where jobs.user_id = '$user_id'";

$result = $conn -> prepare($sql);
$result -> execute();

if($result -> rowCount() > 0) {

	$response["message"] = "true";

	$response["requests"] = array();

	while($row = $result->fetch()) {

		$request = array();

		$request["job_id"] = $row["job_id"];
		$request["user_id"] = $row["user_id"];
		$request["bidding_amount"] = $row["bidding_amount"];
		$request["proposal"] = $row["proposal"]; 
		$request["firstname"] = $row["firstname"];
		$request["lastname"] = $row["lastname"];
		$request["longitude"] = $row["longitude"];
		$request["latitude"] = $row["latitude"];
		$request["job_title"] = $row["specialty_name"];
		$request["request_date"] = date('Y/m/d H:i', $row["request_date"]);
		$request["phone_number"] = $row["phone_number"];

		array_push($response["requests"], $request);
	}
	echo json_encode($response);
} else {

	$response["message"] = "false";
	echo json_encode($response);

}

$conn = null;

?>