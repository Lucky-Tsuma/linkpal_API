<?php
$DATABASE = "linkpal2.0";
$SERVER = "localhost";
define('USERNAME', "root");
define('PASSWORD', "version23");

try {
	$conn = new PDO("mysql:host=$SERVER;dbname=$DATABASE" ,USERNAME ,PASSWORD);
    // set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE/*error name*/, PDO::ERRMODE_EXCEPTION/*error value*/);
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

?>