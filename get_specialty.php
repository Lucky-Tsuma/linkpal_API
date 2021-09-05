<?php
	//array for JSON response
$response = array();

require_once __DIR__.'/config.php';

	//getting specialty from specialty table
$result = $conn->query("SELECT * FROM specialty");
$result -> execute();

if($result -> rowCount() > 0) {
		//$response is an associative array. will have a specialty node as below
	$response["specialty"] = array();

	while($row = $result->fetch()) {
			//a temp array we will use to populate the response's specialty node
		$user_specialty = array();

		$user_specialty["specialty_id"] = $row["specialty_id"];
		$user_specialty["specialty_name"] = $row["specialty_name"];

			//pushing single specialty into response array
		array_push($response["specialty"], $user_specialty);
	}
		//success
	/*$response["success"] = 1;*/
	echo json_encode($response);
}
else {
		//no specialty found
	/*$response["success"] = 0;*/
	$response["message"] = "No results found";
	echo json_encode($response);
}

	//closing database connection
$conn = null;
?>