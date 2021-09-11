<?php  

require_once __DIR__.'/config.php';

$rating = $_POST["rating"];
$job_id = $_POST["job_id"];

$job_rating = (int)$rating;

$sql0 = "UPDATE work_ratings SET rating = '$job_rating' WHERE job_id = '$job_id'";
$result0 = $conn -> prepare($sql0);

$sql1 = "UPDATE jobs SET status = 'rated' WHERE job_id = '$job_id'";
$result1 = $conn -> prepare($sql1);

if($result0 -> execute([$job_rating])) {

	$result1 -> execute();
	echo json_encode(['error' => false, 'message' => "Thank you. You are welcome to hire from this platform anytime."]);

} else {

	echo json_encode(['error' => true, 'message' => "An error occured. Please try again later"]);
}

$conn = null;

?>