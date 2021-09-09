<?php

require_once __DIR__.'/config.php';

$user_id = $_POST["user_id"];

$response = array();

$sql = "SELECT job_requests.job_id, job_requests.bidding_amount, specialty.specialty_name, users.longitude, users.latitude, users.firstname, users.lastname, job_requests.request_date 
FROM job_requests
inner join jobs on jobs.job_id = job_requests.job_id
inner join users on jobs.user_id = users.user_id
inner join specialty on jobs.job_specialty = specialty.specialty_id 
where job_requests.user_id = '$user_id'";

$result = $conn -> prepare($sql);
$result -> execute();

if($result -> rowCount() > 0) {

	$response["message"] = "true";

	$response["requests"] = array();

	while($row = $result->fetch()) {

		$request = array();

		$request["job_id"] = $row["job_id"];
		$request["bidding_amount"] = $row["bidding_amount"];
		$request["job_title"] = $row["specialty_name"];
		$request["longitude"] = $row["longitude"];
		$request["latitude"] = $row["latitude"];
		$request["firstname"] = $row["firstname"];
		$request["lastname"] = $row["lastname"];
		$request["request_date"] = date('Y/m/d H:i', $row["request_date"]);  

		array_push($response["requests"], $request);
	}
	echo json_encode($response);
} else {

	$response["message"] = "false";
	echo json_encode($response);

}

$conn = null;

?>