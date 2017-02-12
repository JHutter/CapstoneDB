<?php
session_start(); // Starting Session
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
$connection = mysql_connect("127.0.0.1", "root", "CapstoneEMC");
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
// Selecting Database
$db = mysql_select_db("cemc", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select * from login where pass='$password' AND user_id='$username' and access_level = 'admin'", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
$_SESSION['login_user']=$username; // Initializing Session
header("location: ../admin/admin_profile.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";
}
mysql_close($connection); // Closing Connection
}
}
?>