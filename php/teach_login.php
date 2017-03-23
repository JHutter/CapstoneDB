<?php
session_start(); // Starting Session
error_reporting(E_ALL); 
ini_set('display_errors', 1);
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysqli_connect("aa257otozcc6lg.c9f1gdrefypl.us-west-2.rds.amazonaws.com:3306", "root", "jbCC8wUb");
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($connection, $username);
$password = mysqli_real_escape_string($connection, $password);
// Selecting Database
$db = mysqli_select_db($connection, "cemc");
// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($connection, "select * from login where pass='$password' AND user_id='$username' and access_level = 'teacher'");
$rows = mysqli_num_rows($query);
if ($rows == 1) {
$_SESSION['login_user']=$username; // Initializing Session
header("location: ../teach/teach_profile.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";
}
mysqli_close($connection); // Closing Connection
}
}
?>