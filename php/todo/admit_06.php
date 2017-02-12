<!--- Begin body --->  
<?php
include('../11/admit_session.php');
include('../11/database.php');

$year = $_SESSION['year'];
$session = $_SESSION['session'];
$teacher = $login_session;
$pdo = Database::connect();
$sql = "SELECT 	CONCAT(COURSEASSIGNMENT.course_id, ':  ',  COURSE.skill, ' ', COURSE.sk_level) AS 'Course',
		COURSE_TYPE.course_time AS 'Course Time',
		CONCAT(TEACHER.teacher_first, ' ', TEACHER.teacher_last) AS 'Teacher'
		FROM COURSEASSIGNMENT 
		left join COURSE on COURSEASSIGNMENT.course_id = COURSE.course_id
		left join COURSE_TYPE on COURSE.course_type = COURSE_TYPE.course_type
		left join TEACHER on COURSEASSIGNMENT.teacher_id = TEACHER.teacher_id
		WHERE acad_year = {$year} 
		AND acad_session = {$session}
		ORDER BY COURSE_TYPE.course_order, sk_level"
		;

echo '<br><br>
 <table class="table table-striped table-bordered">
				<col width="275">
				<col width="200">
				<col width="200">
                  <thead>
                    <tr>
                    <th align="left">Course</th>
                    <th align="left">Course Time</th>
					<th align="left">Teacher</th>
                    </tr>
                  </thead>
                  <tbody>';
foreach ($pdo->query($sql) as $row) {
		echo '<tr>';
		echo '<td>'. $row['Course'] . '</td>';
		echo '<td>'. $row['Course Time'] . '</td>';
		echo '<td>'. $row['Teacher'] . '</td>';
		echo '</tr>';
}

Database::disconnect();


?>


<!-- End Body-->
