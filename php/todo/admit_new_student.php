<head><title>Class Schedule, Roster Select- Capstone Classroom Management System</title>
<?PHP  include("../00/header1.php")?>

<table width="1000" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#FFFFFF"><td align=center valign=top rowspan="2" BGCOLOR="#DFDFFF">

<?PHP  include("../00/admit_m2.php") ?>  
  

</td><td valign=top Width=80%><H3><font color="#008080">Capstone English Center Classroom Management System Prototype 1</font></H3>
</td></tr><tr bgcolor="#FFFFFF"><td width=15% valign=top>

<!-- Page Headline: -->
<ul><H2>Add New Student</h2><br>
<?php
include '..\11\database.php';
include '..\11\admit_session.php';

$new_stu_id = trim($_GET['student_id']);
$new_stu_first = trim($_GET['stu_first']);
$new_stu_last = trim($_GET['stu_last']);
$year = $_GET['year'];
$acad_session = $_GET['session'];
$course1 = $_GET['10am'];
$course2 = $_GET['11am'];
$course3 = $_GET['mwfpm'];
$course4 = $_GET['tthpm'];
$new_stu_courses = [];
if ($course1 != "" AND $course1 != "none"){
	$new_stu_courses[] = $course1;
}
if ($course2 != "" AND $course2 != "none"){
	$new_stu_courses[] = $course2;
}
if ($course3 != "" AND $course3 != "none"){
	$new_stu_courses[] = $course3;
}
if ($course4 != "" AND $course4 != "none"){
	$new_stu_courses[] = $course4;
}

$check_stu_id_new = "SELECT student_id from STUDENTINFO";
$insert_new_stu = "INSERT INTO STUDENTINFO (student_id, stu_first, stu_last)
	VALUES ('{$new_stu_id}', '{$new_stu_first}', '{$new_stu_last}')";
$pdo = Database::connect();
$stu_id_array = [];

// add existing stu IDs to array
foreach ($pdo->query($check_stu_id_new) as $row) {
		$stu_id = $row['student_id'];
		$stu_id_array[] = $stu_id;
}

// check to make sure that new stu ID is not already in use
if (in_array($new_stu_id, $stu_id_array)){ // entered student id is already in use
	echo "<p>Invalid id. The student ID you entered already exists in the database</p>";
	echo "<p>Please go back and try again with a different student ID.</p>";
	echo "<p>You can check the student ID by navigating to the student list.</p>";
} 
else {
	// execute sql to add new student
	//echo "Valid id";
	$pdo->exec($insert_new_stu);
    echo "<br>New record created successfully for {$new_stu_first} {$new_stu_last}, ID {$new_stu_id}<br>";
	
	//write to backup folder
	$student_backup_file = fopen("..\logs\students.sql", "a") or die("Unable to write to backup file t add student!");
	$txt = "\n".$insert_new_stu.";\n\n";
	fwrite($student_backup_file, $txt);
	fclose($student_backup_file);
	
	// execute sql to add stu to courses
	foreach ($new_stu_courses as $course){
		$insert_new_stu_course = "INSERT INTO COURSEENROLLMENT (course_id, acad_year, acad_session, student_id)
			VALUES ('{$course}', {$year}, {$acad_session}, '{$new_stu_id}')";
		$pdo->exec($insert_new_stu_course);
		echo "<br>ID {$new_stu_id} added to {$course}";
		
		// write to backup folder
		$student_backup_file = fopen("..\logs\courseenrollment.sql", "a") or die("Unable to write to backup file to add courses!");
		$txt = "\n".$insert_new_stu_course.";\n\n";
		fwrite($student_backup_file, $txt);
		fclose($student_backup_file);
		echo "<br>{$course} added to student schedule<br>";
	}
	
	//grab list of class dates
	
	//grab course list db, to find coursetype
	
	//compare the two, add dates to array
	
	//add the array to attendance, with no value (default of 'none' in db)
}

?>