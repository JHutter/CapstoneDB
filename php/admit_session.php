<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "CapstoneEMC");

// Selecting Database
$db = mysql_select_db("cemc", $connection);
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysql_query("select user_id from login where user_id='$user_check' and access_level = 'admit'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login_session =$row['user_id'];





$date = date('Ymd');
// get the current session
$sql_get_session = "select acad_session from calendar 
					where session_start <= {$date}
					order by acad_session asc"; 
$result = mysql_query($sql_get_session);
while ($row2 = mysql_fetch_array($result)) {
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
mysql_close($connection); // Closing Connection
header('Location: ../index.php'); // Redirecting To Home Page
}
$_SESSION['year'] = date("Y");
$_SESSION['session'] = $acad_session;
$_SESSION['user_id'] = $login_session;
$_SESSION['term'] = $acad_term;
$_SESSION['user_name'] = "Admissions";

?>