<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_Project_Group_8";

//creating connection
$conn = new mysqli($servername, $username, $password, $dbname);
//check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
//else{ 
	//mysqli_select_db($conn, $dbname);
	//echo "Connection successful";}
?>