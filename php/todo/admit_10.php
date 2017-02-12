
 <!-- Begin Body -->  
<Form action="../99/admit_10_01.php" action="GET">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>See Attendance by Student</td></tr><tr bgcolor="#ffffff">
<td align=right width=15%>Student ID</td><td><input type="Text" name="stu_id" size=70></td></tr><tr bgcolor="#ffffff">
<td></td></tr></table> 
<input value="Look Up Student ID" type="submit"></input>
</form><br><br>

<b>See Attendance by Class</b>
 
<!-- Begin Body -->  
 <?php
include('../11/admit_session.php');
include('../11/database.php');

$year = $_SESSION['year'];
$session = $_SESSION['session'];
$pdo = Database::connect();
$sql_courses = "SELECT 	COURSEASSIGNMENT.course_id AS 'course_id',
						CONCAT(COURSE.skill, ' ', COURSE.sk_level) AS 'Course Name',
						CONCAT(teacher_first, ' ', teacher_last) AS 'Teacher'
				FROM COURSEASSIGNMENT 
				left join COURSE on COURSEASSIGNMENT.course_id = COURSE.course_id
				left join TEACHER on COURSEASSIGNMENT.teacher_id = TEACHER.teacher_id
				WHERE acad_year = {$year} 
				AND acad_session = {$session}"
		;
echo '
 <table class="table table-striped table-bordered">
				<col width="80">
				<col width="350">
                  <thead>
                    <tr>
                      <th align="left">Course</th>
                    </tr>
                  </thead>
                  <tbody>';
foreach ($pdo->query($sql_courses) as $row) {
		$course = ''. $row['course_id'];
		$course_name = ''. $row['Course Name'];
		$teacher = "{$row['Teacher']}";
		echo '<tr>';
		echo '<td>'. $course . '</td>';
		echo '<td><a href="../99/admit_10_02.php?course=' . $course . '&teacher='.$teacher.'">' . $course_name . ' --- '. $teacher.'</a></b>';
		echo '</tr>';
}
Database::disconnect();

?>

<br><br>

 

<!-- End Body --> 