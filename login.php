<?php 
require_once __DIR__.'/config.php';

$phone = $_POST["phone_number"];
$password = $_POST["password"];

$sql0 = "SELECT user_id, firstname, lastname, phone_number, longitude, latitude, passwrd, join_date FROM users WHERE phone_number = '$phone'";
$result0 = $conn -> prepare($sql0);
$result0 -> execute();

if($result0 -> rowCount() == 0){
		//case 1: no such phone_number
	echo json_encode(['error' => true, 'message' => "Invalid password or phone_number. Please try again!"]);
} else {
	while($row = $result0 -> fetch()){
		$returned_password = $row["passwrd"];
		$firstname = $row["firstname"];
		$lastname = $row["lastname"];
		$user_id = $row["user_id"];
		$phone_number = $row["phone_number"];
	}
		//phone_number exixts, but the password is wrong
	if(password_verify($password, $returned_password) == false) {
		echo json_encode(['error' => true, 'message' => "Invalid password or phone_number. Please try again!"]);
		die();
	} else {
			//phone_number and password correct, checking the type of user
		$sql1 = "SELECT phone_number from users_extras WHERE phone_number = '$phone'";
		$result1 = $conn -> prepare($sql1);
		$result1 -> execute();
	}

	if($result1 -> rowCount() == 0) {
		echo json_encode(['error' => false, 'message' => "Login successfull!", 'userType' => "employer", 'firstname' => $firstname,  'lastname' => $lastname, 'user_id' => $user_id, 'phone_number' => $phone_number]);
	} else {
		$sql_pic = "SELECT profile_pic from users_extras WHERE phone_number = '$phone'";
		$result_pic = $conn -> prepare($sql_pic);
		$result_pic -> execute();
		while($row = $result_pic -> fetch()) {
			$profile_pic = $row["profile_pic"];
		}

		$sql_rating = "SELECT AVG(rating) FROM work_ratings WHERE worker_id = '$user_id'";
		$result_rating = $conn -> prepare($sql_rating);
		$result_rating -> execute();
		while($row = $result_rating -> fetch()) {
			if($row['AVG(rating)'] == null) {
				$rating = 0.0;
			} else {
				$rating = $row['AVG(rating)'];
			}
		}
		echo json_encode(['error' => false, 'message' => "Login successfull!", 'userType' => "worker", 'firstname' => $firstname, 'lastname' => $lastname, 'profile_pic' => $profile_pic, 'user_id' => $user_id, 'phone_number' => $phone_number, 'rating' => $rating]);
	}
}

$conn = null;

?>