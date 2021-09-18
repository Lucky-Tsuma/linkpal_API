<?php

require_once __DIR__.'/quicksort.php';
require_once __DIR__.'/config.php';

// An associative array for the response
$response = array();

$user_id = $_POST["employer_id"];
$employer_longitude = $_POST["longitude"];
$employer_latitude = $_POST["latitude"];
$sort_method = $_POST["sort_method"];
/*case 1 = rating, 2 = distance, 3 = price*/

function getDistance($lat1, $lon1, $lat2, $lon2) {
	$lat1 = (float) $lat1;
	$lon1 = (float) $lon1; 
	$lat2 = (float) $lat2; 
	$lon2 = (float) $lon2;

  $earthRadius = 6371; // Radius of the earth in km
  $diffLatitude = deg2rad($lat2-$lat1);  // degrees to radians
  $diffLongitude = deg2rad($lon2-$lon1);
  $a =  sin($diffLatitude/2) * sin($diffLatitude/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($diffLongitude/2) * sin($diffLongitude/2); 
  $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
  $distance = $earthRadius * $c; // Distance in km
  return $distance;
}

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

		$worker_id = $row["user_id"];
		$worker_longitude = $row["longitude"];
		$worker_latitude = $row["latitude"];

		$sql_rating = "SELECT AVG(rating) FROM work_ratings WHERE worker_id = '$worker_id'";
		$result_rating = $conn -> prepare($sql_rating);
		$result_rating -> execute();
		while($row = $result_rating -> fetch()) {
			if($row['AVG(rating)'] == null) {
				$request["rating"] = 0;
			} else {
				$request["rating"] = $row['AVG(rating)'];
			}
		}

		$request["distance"] = getDistance($employer_latitude, $employer_longitude, $worker_latitude, $worker_longitude);

		array_push($response["requests"], $request);
		
	} 

	switch ($sort_method) {
		case 1:
		$response["requests"] = QuickSort::rsort('rating', $response["requests"]);
		break;

		case 2:
		$response["requests"] = QuickSort::sort('distance', $response["requests"]);
		break;
		
		case 3:
		$response["requests"] = QuickSort::sort('bidding_amount', $response["requests"]);
		break;
		
		default:
			$response["requests"] = QuickSort::rsort('rating', $response["requests"]);
		break;
	}

	echo json_encode($response);

	
} else {

	$response["message"] = "false";
	echo json_encode($response);

}

$conn = null;

?>