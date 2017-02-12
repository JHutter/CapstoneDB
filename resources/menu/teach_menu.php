<?php
	include("../php/nav_widget.php");
	echo get_login_widget_div($_SESSION['user_name']);
?>

<a href="schedule.php" class="button" title="Main Menu">     Schedule     </a> 

<a href="class_list.php" class="button" title="Main Menu">     Class Lists     </a>

<a href="attendance.php" class="button" title="Main Menu">     Attendance     </a>

<a href="assignments.php" class="button" title="Main Menu">     Grades     </a> 

<a href="../php/logout.php" class="button" title="Main Menu">     Logout     </a> 

