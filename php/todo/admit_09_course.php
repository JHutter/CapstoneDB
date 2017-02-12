<!--- Begin body --->  
<?php 
include('../11/admit_session.php'); 
include('../11/database.php');

$year = $_SESSION['year'];
$session = $_SESSION['session'];

$SName = $_GET['student_name'];
$stu_id = $_GET['student_id'];
$start = $_GET['date_start'];
$end = $_GET['date_end'];
$attn = $_GET['attn'];
$course_type = $_GET['course_type'];
$pdo = Database::connect();
$sql_course = "SELECT courseenrollment.course_id as 'course_id' from courseenrollment left join course on courseenrollment.course_id = course.course_id
				where student_id = '{$stu_id}' and acad_year = {$year}
				and acad_session = {$session} and course_type = '{$course_type}'";
foreach ($pdo->query($sql_course) as $row) {
	$course = $row['course_id'];
}
?>

Excused/LOA by course.<br>

<br>Name: <?php echo $SName; ?>
<br>ID: <?php echo $stu_id;?>
<br>Course: <?php echo $course ?>
<br>Start Date: <?php echo $start; ?>
<br>End Date: <?php echo $end; ?>
<br>Attendance: <?php echo $attn; ?>

<?php
$sql_change_attn = "UPDATE attendance
					set attendance_yn = '{$attn}'
					where student_id = '{$stu_id}'
					and acad_year = {$year}
					and acad_session = {$session}
					and class_date between '{$start}' and '{$end}'
					and course_id = '{$course}'";

$pdo->exec($sql_change_attn);
			
//write to backup folder
$student_backup_file = fopen("..\logs\admit_attendance.sql", "a") or die("Unable to write to backup file t add student!");
$txt = "\n".$sql_change_attn.";\n\n";
fwrite($student_backup_file, $txt);
fclose($student_backup_file);

?>




<br>
<br>


<br>