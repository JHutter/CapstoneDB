 
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
echo '<br><br>
 <table class="table table-striped table-bordered">
				<col width="250">
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
		echo '<td><a href="../99/admit_05_class.php?course=' . $course . '&teacher='.$teacher.'">' . $course_name . ' --- '. $teacher.'</a></b>';
		echo '</tr>';
}
Database::disconnect();

echo '<b id="logout"><a href="../11/teach_logout.php">Log Out</a></b>';
?>

 <br><br>
 <!--- PHP Statment: include("../11/teacher_02.php") ---> 
<br><br>

 

<!-- End Body --> 