<?php

require_once __DIR__.'/config.php';

$user_id = $_POST["employer_id"];

$response = array();

$sql = "SELECT work_ratings.job_id, specialty.specialty_name, jobs.job_description, users.firstname, users.lastname
FROM work_ratings
inner join users on users.user_id = work_ratings.worker_id
inner join jobs on jobs.job_id = work_ratings.job_id
inner join specialty on jobs.job_specialty = specialty.specialty_id 
WHERE work_ratings.employer_id = '$user_id' AND jobs.status = 'taken'"; 

$result = $conn -> prepare($sql);
$result -> execute();

if($result -> rowCount() > 0) {

	$response["message"] = "true";

	$response["ratings"] = array();

	while($row = $result->fetch()) {

		$request = array();

		$request["job_id"] = $row["job_id"];
		$request["job_title"] = $row["specialty_name"];
		$request["job_description"] = $row["job_description"];
		$request["firstname"] = $row["firstname"];
		$request["lastname"] = $row["lastname"];

		array_push($response["ratings"], $request);
	}
	echo json_encode($response);
} else {

	$response["message"] = "false";
	echo json_encode($response);

}

$conn = null;

 ?>