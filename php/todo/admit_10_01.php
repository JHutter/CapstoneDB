 
<!-- Begin Body -->  
 <?php
include('../11/admit_session.php');
include('../11/database.php');

$acad_year = $_SESSION['year'];
$acad_session = $_SESSION['session'];
$course = $_GET['course'];
$stu_id = $_GET['stu_id'];
$pdo = Database::connect();
$sql_get_name = "SELECT CONCAT(stu_first, ' ', stu_last) AS 'SName',
				stu_email1
				from STUDENTINFO left join studentcontact on studentinfo.student_id = studentcontact.student_id
				WHERE studentinfo.student_id = '{$stu_id}'";
foreach ($pdo->query($sql_get_name) as $row) {
	$SName = $row['SName'];
	$stu_email1 = $row['stu_email1'];
}

echo "Name: {$SName}, ID {$stu_id}<br>";
echo "Email: {$stu_email1}";
echo "<br><br>";

$sql_sched = "select	COURSEENROLLMENT.course_id AS 'course_id',
				COURSEASSIGNMENT.room AS 'room',
				CONCAT(teacher_first, ' ', teacher_last) AS 'teacher'
		from COURSEENROLLMENT
		left join COURSE on COURSEENROLLMENT.course_id = COURSE.course_id
		left join COURSEASSIGNMENT on COURSEENROLLMENT.course_id = COURSEASSIGNMENT.course_id
		left join COURSE_TYPE on COURSE.course_type = COURSE_TYPE.course_type
		left join TEACHER on COURSEASSIGNMENT.teacher_id = TEACHER.teacher_id
		WHERE 	COURSEENROLLMENT.acad_year = {$acad_year}
		AND 	COURSEENROLLMENT.acad_session = {$acad_session}
		AND 	COURSEENROLLMENT.student_id = '{$stu_id}'
		ORDER BY COURSE.course_type"
		;

class Course{
	public $course_id;
	public $room = '---';
	public $teacher = '---';
}
$course_array = [];
foreach ($pdo->query($sql_sched) as $row){
	$course = new Course();
	$course->course_id = $row['course_id'];
	$course->room = $row['room'];
	$course->teacher = $row['teacher'];
	
	$course_array[] = $course;
}

echo '<table><tr>
	<col width="75">
	<col width="130">
	<col width="200">
    <thead>
    <tr>
    <th align="left">Course</th>
    <th align="left">Teacher</th>
	<th align="left">Room</th>
    </tr></thead>
    <tbody>';

foreach ($course_array as $course) {
	echo "<tr>";
	echo "<td>{$course->course_id}</td>";
	echo "<td>{$course->teacher}</td>";
	echo "<td>{$course->room}</td>";
	echo "</tr>";
}
echo "</table>";

$absent = 0;
$late = 0;
echo "<br><br><br>";
echo "<table bgcolor=\"#aaaaff\" cellspacing=1 cellpadding=\"2\">";
echo '<col width="150">';
echo "<tr bgcolor=\"#FFFFFF\">
	<td>Course</td>";
	
	//get dates from course+course_type+class_dates
	//echo td for each date
	$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
							class_date AS 'date',
							DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
								from ATTENDANCE
								where acad_year = {$acad_year}
								and acad_session = {$acad_session}
								and course_id like 'SL%'
								order by date";
	
	foreach ($pdo->query($sql_get_dates) as $row){
		$date = $row['class_date'];
		echo "<td>{$date}</td>";
		}
	echo "</tr>";

$sql_get_courses = "select	courseenrollment.course_id
					from COURSEENROLLMENT left join course on courseenrollment.course_id = course.course_id
					WHERE 	COURSEENROLLMENT.acad_year = {$acad_year}
					AND 	COURSEENROLLMENT.acad_session = {$acad_session}
					AND 	COURSEENROLLMENT.student_id = '{$stu_id}'
					order by course.course_type";

foreach ($pdo->query($sql_get_courses) as $row) {
	$course = $row['course_id'];
	echo "<tr bgcolor='#ffffff'>";
	echo "<td>{$course}</td>";
	
	$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
							class_date AS 'date',
							DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
								from ATTENDANCE
								where acad_year = {$acad_year}
								and acad_session = {$acad_session}
								and course_id like 'SL%'
								order by date";
								
	foreach ($pdo->query($sql_get_dates) as $row) {
		$date = $row['tableDate'];
		$sql_get_attn = "select attendance_yn
					from attendance
					where student_id = '{$stu_id}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					and class_date = {$date}";
		$results = $pdo->query($sql_get_attn);
		
		if ($results->rowCount() == 0) {
			$attn = '---';
		}
		else {
			foreach ($pdo->query($sql_get_attn) as $row2) {
				$attn = $row2['attendance_yn'];
			}
		}
		if ($attn == 'absent') {
			$absent++; 
			echo "<td><b><font color =\"red\">{$attn}</font></b></td>";
		}
		else if ($attn == 'late') {
			$late++; 
			echo "<td><b>{$attn}</b></td>";
		}
		else {
			if ($attn == 'none') {$attn = '---';}
			echo "<td>{$attn}</b></td>";
		}
		
		
		
	}
	echo "</tr>";
}
echo "</table>";
echo "<br><br>";
echo "Times absent: {$absent}<br>";
echo "Times late:   {$late}<br>";

Database::disconnect();	
?>
<br><br><br>
<Form action="../99/admit_10_01.php" action="GET">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Look up attendance for another student</td></tr><tr bgcolor="#ffffff">
<td align=right width=15%>Student ID</td><td><input type="Text" name="stu_id" size=70></td></tr><tr bgcolor="#ffffff">
<td></td></tr></table> 
<input value="Look Up Student ID" type="submit"></input>
</form><br><br>