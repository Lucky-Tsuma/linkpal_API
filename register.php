<?php 
require_once __DIR__.'/config.php'; 
date_default_timezone_set("Africa/Nairobi");

$firstName = $_POST["firstName"];
$lastname = $_POST["lastName"];
$phone = $_POST["phone"];
$pwd = $_POST["password"];
$password = password_hash($pwd, PASSWORD_BCRYPT);
$gender = $_POST["gender"];
$currentTimeinSeconds = time(); 

/*phone_number validation first*/
$sql0 = "SELECT * FROM users WHERE phone_number = '$phone'";
$result0 = $conn->prepare($sql0);
$result0 -> execute();

if ($result0 -> rowCount() > 0) {
	echo json_encode(['error' => true, 'message' => "Sorry, user already exists"]);
	exit();
}

/*Insert normal user details if phone_number validation passed*/

if(isset($_POST["longitude"]) && isset($_POST["latitude"])){
	$longitude = $_POST["longitude"];
	$latitude = $_POST["latitude"];
	$sql = "INSERT INTO users (firstname, lastname, phone_number, longitude, latitude, passwrd, gender, join_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	$result1 = $conn -> prepare($sql);

	if ($result1 -> execute([$firstName, $lastname, $phone, $longitude, $latitude, $password, $gender, $currentTimeinSeconds])){
		echo json_encode(['error' => false, 'message' => "Registration successful", 'post' => $_POST]);
	} else {
		echo json_encode(['error' => true, 'message' => "Registration failed", 'post' => $_POST]);
	}
} else {
	$sql = "INSERT INTO users (firstname, lastname, phone_number, passwrd, gender, join_date) VALUES (?, ?, ?, ?, ?, ?)";
	$result1 = $conn -> prepare($sql);

	if ($result1 -> execute([$firstName, $lastname, $phone, $password, $gender, $currentTimeinSeconds])){
		echo json_encode(['error' => false, 'message' => "Registration successful", 'post' => $_POST]);
	} else {
		echo json_encode(['error' => true, 'message' => "Registration failed", 'post' => $_POST]);
	}
}


/*Additional details for users creating workers' accounts*/
if (isset($_POST["jobField"])) {

	$jobField = $_POST["jobField"];
	$profileSummary =  $_POST["profileSummary"];
	$image = $_FILES['imageFile'];

	$ext_type = array('gif','jpg','jpe','jpeg','png');
	$tmp_name = $image["tmp_name"];/*Image temporary location on the server*/

	$name = basename($image["name"]);
	$uploads_dir = __DIR__.'/Uploads/Profile_pics';

	$pos = strrpos($_POST['imageName'], "."); /*Getting the last occurence of . in the string, probably the one before the MIME type extension*/

	if ($pos !== false) {
		$ext = substr($_POST['imageName'], $pos+1);
		/*$pos+1 is where the image extension type will be, if any*/

		if ($ext !== false) {
			if (in_array($ext, $ext_type)) {
				if (move_uploaded_file($tmp_name, "$uploads_dir/$name.$ext")) {
					$profilePicture = "/Uploads/Profile_pics/"."$name".".$ext";

					$sql2 = "INSERT INTO users_extras (phone_number, user_specialty, summary, profile_pic) VALUES (?, ?, ?, ?)";
					$result2 = $conn -> prepare($sql2);
					$result2 -> execute([$phone, $jobField, $profileSummary, $profilePicture]);
					echo json_encode(['error'=>false, 'message'=>"Registration successful", 'post'=>$_POST, 'file'=>$image]);
				} else {
					echo json_encode(['error'=>true, 'message'=>"Registration failed", 'post'=>$_POST, 'file'=>$image]);
				}
			} else {
				echo json_encode(['error'=>true, 'message'=>"Image Upload Failed. Invalid file extension", 'post'=>$_POST, 'file'=>$image]);
			}
		} else {
			echo json_encode(['error'=>true, 'message'=>"Image Upload Failed. No file extension type found", 'post'=>$_POST, 'file'=>$image]);
		}
	} else {
 			// note: three equal signs. not found...
		echo json_encode(['error'=>true, 'message'=>"Image Upload Failed. No file extension found", 'post'=>$_POST, 'file'=>$image]);
	}
}

$conn = null;

?>