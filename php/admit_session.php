<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysqli_connect("aa257otozcc6lg.c9f1gdrefypl.us-west-2.rds.amazonaws.com:3306", "root", "jbCC8wUb");

// Selecting Database
$db = mysqli_select_db($connection, "cemc" );
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysqli_query($connection, "select user_id from login where user_id='$user_check' and access_level = 'admit'");
$row = mysqli_fetch_assoc($ses_sql);
$login_session =$row['user_id'];





//$date = date('Ymd');
$date = '20160318';
// get the current session
$sql_get_session = "select acad_session from calendar 
					where session_start <= {$date}
					order by acad_session asc"; 
$result = mysqli_query($connection, $sql_get_session);
while ($row2 = mysqli_fetch_array($result)) {
	$acad_session = $row2['acad_session'];
}
switch ($acad_session) {
	case 1:
	case 2:
		$acad_term = 1;
		break;
	case 3:
	case 4:
		$acad_term = 2;
		break;
	case 5:
	case 6:
		$acad_term = 3;
		break;
	case 7:
	case 8:
		$acad_term = 4;
		break;
	default:
		$acad_term= 0;
		//based on database design, control should never get here
}

if(!isset($login_session)){
mysqli_close($connection); // Closing Connection
header('Location: ../index.php'); // Redirecting To Home Page
}
$_SESSION['year'] = '2016';
$_SESSION['date'] = $date;
$_SESSION['session'] = $acad_session;
$_SESSION['user_id'] = $login_session;
$_SESSION['term'] = $acad_term;
$_SESSION['user_name'] = "Admissions";

?>