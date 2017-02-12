<?php
	include("../php/nav_widget.php");
	echo get_login_widget_div($_SESSION['user_name']);
?>

<table class=plain >
<tr class=plain><td ><strong>Enrollment:</strong></td>
	<td style=min-width:180px;><a href="student_report.php" class="button" title="Main Menu" style=min-width:95%>     Student Report     </a> </td>
	<td style=min-width:180px;><a href="new_student.php" class="button" title="Main Menu" style=min-width:95%>     New Student     </a> </td>
	<td style=min-width:180px;><a href="email.php" class="button" title="Main Menu" style=min-width:95%>     Student Email     </a></td>
	
	</tr>

<tr class=plain><td><strong>Grades: </strong></td>
	<td><a href="report_card.php" class="button" title="Main Menu" style=min-width:95%>     Report Cards     </a> </td>
	<td><a href="grade_report.php" class="button" title="Main Menu" style=min-width:95%>     Grade Reports     </a> </td>
	<td><a href="assignments.php" class="button" title="Main Menu" style=min-width:95%>     Assignments     </a></td>
	
	</tr>
	
<tr class=plain><td><strong>Attendance: </strong></td>
	<td><a href="attendance_report.php" class="button" title="Main Menu" style=min-width:95%>     Attendance Reports     </a></td>
	<td><a href="attendance_see.php" class="button" title="Main Menu" style=min-width:95%>     See Attendance     </a></td>
	<td><a href="attendance_change.php" class="button" title="Main Menu" style=min-width:95%>     Change Attendance     </a></td>
	<td><a href="blank_attendance.php" class="button" title="Main Menu" style=min-width:95%>     Missing Attendance     </a></td>
	
	
	</tr>
	
<tr class=plain><td><strong>Course Assignment:</strong></td>
	<td><a href="classlists.php" class="button" title="Main Menu" style=min-width:95%>     Class Lists     </a> </td>
	<td><a href="teacher_schedule.php" class="button" title="Main Menu" style=min-width:95%>     Teacher Schedules     </a> </td>
	<td><a href="student_schedule.php" class="button" title="Main Menu" style=min-width:95%>     Student Schedules     </a></td>

	
	</tr>
	
</table>
