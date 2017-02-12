<?php
include('../11/admit_session.php');
include('../11/database.php');

$year = $_SESSION['year'];
$session = $_SESSION['session'];
$teacher = $_GET['teacher'];
$course = $_GET['course'];
$pdo = Database::connect();
$sql_stus = "SELECT COURSEENROLLMENT.student_id AS 'student_id',
					CONCAT(stu_first, ' ', stu_last) AS 'SName',
					stu_nick
			FROM COURSEENROLLMENT
			left join STUDENTINFO on COURSEENROLLMENT.student_id = STUDENTINFO.student_id
			WHERE acad_year = {$year} 
			AND acad_session = {$session}
			AND course_id = '{$course}'
			ORDER BY stu_last"
			;
			
echo $teacher . '\'s class list for ' . $course;
			
echo '<br><br>
 <table class="table table-striped table-bordered">
				<col width="80">
				<col width="150">
				<col width="150>"
                  <thead>
                    <tr>
                      <th align="left">Student ID</th>
                      <th align="left">Student Name</th>
					  <th align="left">Student Nickname</th>
                    </tr>
                  </thead>
                  <tbody>';
$count = 0;
foreach ($pdo->query($sql_stus) as $row) {
		$stu_id = ''.$row['student_id'];
		$stu_name = ''.$row['SName'];
		$stu_nick = ''.$row['stu_nick'];
		echo '<tr>';
		echo '<td>'. $stu_id . '</td>';
		echo '<td>'. $stu_name . '</td>';
		echo '<td>'. $stu_nick . '</td>';
		echo '</tr>';
		$count++;
}
echo "</table><br><br>";
echo "Student count: {$count}";
Database::disconnect();

?>