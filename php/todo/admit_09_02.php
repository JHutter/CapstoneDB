<!--- Begin body --->  
<?php 
include('../11/admit_session.php'); 
include('../11/database.php');
 $stu_id = trim($_GET['stu_id']);
 $sql_stu_name = "SELECT CONCAT(stu_first, ' ', stu_last) AS 'SName' FROM STUDENTINFO WHERE student_id = '{$stu_id}'";
$pdo = Database::connect();
foreach ($pdo->query($sql_stu_name) as $row) {
	$stu_name = $row['SName'];
}

$year = $_SESSION['year'];
$session = $_SESSION['session'];
$sql_session_dates = "select CONVERT(CHAR(12), session_start, 112) AS 'session_start', 
						session_end AS 'session_end'
					from calendar
					where acad_year = {$year} and acad_session = {$session}";
foreach ($pdo->query($sql_session_dates) as $row) {
	$sStart = $row['session_start'];
	$sEnd = $row['session_end'];
}
?>

Enter dates as 6 digit numbers (eg. 20151201). Only enter dates in the current session.
<Form action="../99/admit_09_date.php" method = "get">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Attendance, by date</td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student ID</td><td><input required type="Text" value="<?php echo $stu_id; ?>" name="student_id" size=70></td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student Name</td><td><input required type="Text" value="<?php echo $stu_name; ?>" name="student_name" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right width=25%>Start Date</td><td><input required type="number" min="20151201" max="20160122" name="date_start" size=70></td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>End Date</td><td><input required type="number" min="20151201" max="20160122" name="date_end" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right>Attendance</td><td><p><select required name="attn">
	<option value="">Select...</option>
	<option value="excused">excused</option>
	<option value="loa">loa</option>
	<option value="med leave">medical leave</option>
	<option value="term">terminated</option>
	<option value="present">present</option>
	<option value="late">late</option>
	<option value="absent">absent</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

</table> 
<input value="Submit Form" type="submit"> 
</form><br>

<Form action="../99/admit_09_course.php" method = "get">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Attendance, by course and date</td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student ID</td><td><input required type="Text" value="<?php echo $stu_id; ?>" name="student_id" size=70></td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student Name</td><td><input required type="Text" value="<?php echo $stu_name; ?>" name="student_name" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right width=25%>Start Date</td><td><input required type="number" min="20151201" max="20160122" name="date_start" size=70></td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>End Date</td><td><input required type="number" min="20151201" max="20160122" name="date_end" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right>Course</td><td><p><select required name="course_type">
	<option value="">Select...</option>
	<option value="10am">Speaking / Listening</option>
	<option value="11am">Reading / Writing</option>
	<option value="mwfpm">Grammar</option>
	<option value="tthpm">Skills Course (Vocab, TOEFL, Life Skills)</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Attendance</td><td><p><select required name="attn">
	<option value="">Select...</option>
	<option value="excused">excused</option>
	<option value="loa">loa</option>
	<option value="med leave">medical leave</option>
	<option value="term">terminated</option>
	<option value="present">present</option>
	<option value="late">late</option>
	<option value="absent">absent</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

</table> 
<input value="Submit Form" type="submit"> 
</form><br>
<br>


<br>