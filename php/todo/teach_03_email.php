<?php
include('../11/teach_session.php');
include('../11/database.php');

$year = $_SESSION['year'];
$session = $_SESSION['session'];
$teacher = $login_session;
$course = $_GET['course'];
$pdo = Database::connect();
$sql_stus = "SELECT COURSEENROLLMENT.student_id AS 'student_id',
					CONCAT(stu_first, ' ', stu_last) AS 'SName',
					stu_email1
			FROM COURSEENROLLMENT
			left join STUDENTINFO on COURSEENROLLMENT.student_id = STUDENTINFO.student_id
			left join studentcontact on studentinfo.student_id = studentcontact.student_id
			WHERE acad_year = {$year} 
			AND acad_session = {$session}
			AND course_id = '{$course}'
			ORDER BY stu_last"
			;
			
echo "Email list for {$course}";
			
echo '<br><br>
 <table class="table table-striped table-bordered">
				<col width="90">
				<col width="200">
				<col width="150>"
                  <thead>
                    <tr>
                      <th align="left">Student ID</th>
                      <th align="left">Student Name</th>
					  <th align="left">Email</th>
                    </tr>
                  </thead>
                  <tbody>';

$count = 0;
foreach ($pdo->query($sql_stus) as $row) {
		$stu_id = $row['student_id'];
		$stu_name = $row['SName'];
		$stu_email1 = $row['stu_email1'];
		echo "<tr>";
		echo "<td>{$stu_id}</td>";
		echo "<td>{$stu_name}</td>";
		echo "<td>{$stu_email1}</td>";
		echo '</tr>';
		$count++;
		
}
echo "</table>";
echo "<br><br>Student count: {$count}";
Database::disconnect();

?>