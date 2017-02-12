<!--- Begin body --->  
<?php 
include('../11/admit_session.php');
include('../11/database.php');

$year = $_GET['year'];
$session = $_GET['session'];
$stu_id = trim($_GET['student_id']);
$pdo = Database::connect();
$sql_name = "select CONCAT(stu_first, ' ', stu_last) AS 'SName'
			from STUDENTINFO
			where student_id = '{$stu_id}'";
foreach ($pdo->query($sql_name) as $row){ //should only return one
	$stu_name = $row['SName'];
}
$sql_sched = "select	COURSEENROLLMENT.course_id AS 'course_id',
				COURSE.skill AS 'skill',
				COURSE.sk_level AS 'level',
				COURSEASSIGNMENT.room AS 'room',
				CONCAT(teacher_first, ' ', teacher_last) AS 'teacher'
		from COURSEENROLLMENT
		left join COURSE on COURSEENROLLMENT.course_id = COURSE.course_id
		left join COURSEASSIGNMENT on COURSEENROLLMENT.course_id = COURSEASSIGNMENT.course_id
		left join COURSE_TYPE on COURSE.course_type = COURSE_TYPE.course_type
		left join TEACHER on COURSEASSIGNMENT.teacher_id = TEACHER.teacher_id
		WHERE 	COURSEENROLLMENT.acad_year = {$year}
		AND 	COURSEENROLLMENT.acad_session = {$session}
		AND 	COURSEENROLLMENT.student_id = '{$stu_id}'
		ORDER BY COURSE.course_type"
		;
$sql_get_email = "select stu_email1 from studentcontact where student_id = '{$stu_id}'";
foreach ($pdo->query($sql_get_email) as $row){ //should only return one
	$email = $row['stu_email1'];
}
class Course{
	public $skill;
	public $level = '---';
	public $room = '---';
	public $teacher = '---';
}
$course_array = [];
foreach ($pdo->query($sql_sched) as $row){
	$course = new Course();
	$course->skill = $row['skill'];
	$course->level = $row['level'];
	$course->room = $row['room'];
	$course->teacher = $row['teacher'];
	
	$course_array[] = $course;
}

//grab term dates here

 
echo '<img src="../img/page_top.gif" alt="CEMC logo"><br>';
echo '<font size="8"><center>Student Schedule</center></font><br>';

echo '<table><tr>
	<col width="250">
	<col width="100">
	<col width="200">
    <thead>
    <tr>
    <th align="left"></th>
    <th align="left"></th>
	<th align="left"></th>
    </tr></thead>
    <tbody>
	
	<tr>
		<td></td>
		<td>Session: </td>';
		
		echo "<td>{$session}, {$year}</td></tr>";
	
	echo "<tr>
		<td></td>
		<td>Session Dates: </td>
		<td>12/01/2015 - 1/22/2016</td></tr>
	
	<tr>
		<td></td>
		<td>Name: </td>
		<td>{$stu_name}</td></tr>
	
	<tr>
		<td></td>
		<td>Student ID: </td>
		<td>{$stu_id}</td></tr>
	
	<tr>
		<td></td>
		<td>Email: </td>
		<td>{$email}</td></tr>
	
	</table>
	
	<br>";
	
	echo '<table class="table table-striped table-bordered" bgcolor="#505050">
	<tr>
	<col width="100">
	<col width="130">
	<col width="130">
	<col width="130">
	<col width="130">
	<col width="130">
    <thead>
    <tr bgcolor="#ffffff">
    <th align="center"> </th>
    <th align="center">Monday</th>
	<th align="center">Tuesday</th>
	<th align="center">Wednesday</th>
	<th align="center">Thursday</th>
	<th align="center">Friday</th>
	
    </tr></thead>
    <tbody>
	

	<tr bgcolor="#ffffff">
	<td>10:00 - 10:55</td>';
	$index = 0;
	if ($course_array[$index]->skill == 'Speaking/Listening'){
		for ($col = 1; $col < 6; $col++){
			echo "<td>{$course_array[$index]->skill}<br><br>
			{$course_array[$index]->level}<br><br>
			Room: _________<br><br>
			Teacher: _____________</td>";
		}
		if (array_key_exists($index+1,$course_array)) {$index++;}
	}
	else{
		for ($col = 1; $col < 6; $col++){
			echo "<td>---No class---<br><br>
			Level ---<br><br>
			Room ---<br><br>
			<br></td>";
		}
	}
	echo '</tr><tr bgcolor="#ffffff"><td>11:00 - 11:55</td>';
	
	if ($course_array[$index]->skill == 'Reading/Writing'){
		for ($col = 1; $col < 6; $col++){
			echo "<td>{$course_array[$index]->skill}<br><br>
			{$course_array[$index]->level}<br><br>
			Room: _________<br><br>
			Teacher: _____________</td>";
		}
		if (array_key_exists($index+1,$course_array)) {$index++;}
	}
	else{
		for ($col = 1; $col < 6; $col++){
			echo "<td>---No class---<br><br>
			Level ---<br><br>
			Room ---<br><br>
			<br></td>";
		}
	}
	
	echo '</tr><tr bgcolor="#ffffff"><td>12:00 - 12:55</td>';
	for ($col = 1; $col < 6; $col++){
		echo "<td><br>LUNCH<br><br></td>";
	}
	
	echo '</tr><tr bgcolor="#ffffff"><td>1:00 pm</td>';
	if ($course_array[$index]->skill == 'Grammar'){
		$mwf_string = "<td>{$course_array[$index]->skill}<br><br>
		{$course_array[$index]->level}<br><br>
		Room: _________<br><br>
		Teacher: _____________<br><br>
		Finish 2:40 pm</td>";
		if (array_key_exists($index+1,$course_array)) {$index++;}
	}
	else{
		$mwf_string = "<td>---No class---<br><br>
			Level ---<br><br>
			Room ---<br><br>
			<br></td>";
		}
	
	if ($course_array[$index]->skill == 'Vocabulary'
			OR $course_array[$index]->skill == 'Life Skills'
			OR $course_array[$index]->skill == 'TOEFL'){
		$tth_string = "<td>{$course_array[$index]->skill}<br><br>
		{$course_array[$index]->level}<br><br>
		Room: _________<br><br>
		Teacher: _____________<br><br>
		Finish 2:30 pm</td>";
	}
	else {
		$tth_string = "<td>---No class---<br><br>
			Level ---<br><br>
			Room ---<br><br>
			<br></td>";
	}
	
	for ($col = 1; $col < 3; $col++){
		echo $mwf_string;
		echo $tth_string;
	}
	echo $mwf_string;

Database::disconnect();


?>


<!-- End Body-->
