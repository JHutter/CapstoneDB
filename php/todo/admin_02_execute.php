 <?php
 include('../11/database.php');
 $startyn = trim($_GET['startyn']);
 $acad_session = trim($_GET['session']);
 $acad_year = trim($_GET['year']);
$pdo = Database::connect();

$sql_stus_for_grade = "SELECT student_id, course_id 
						from COURSEENROLLMENT 
						where acad_year = {$acad_year} and acad_session = {$acad_session}
						order by student_id";
if ($startyn == 'yes'){
	foreach ($pdo->query($sql_stus_for_grade) as $row) {
		$course = $row['course_id'];
		$stu = $row['student_id'];
		echo "<br>";
		
		$sql_insert_coursegrade = "INSERT INTO SESSIONGRADE
										(course_id, acad_year, acad_session, student_id)
									VALUES
									('{$course}', {$acad_year}, {$acad_session}, '{$stu}')";
		$sql_insert_final_attn = "INSERT INTO ATTENDANCE_FINAL (acad_year, acad_session, course_id, student_id)
									VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$stu}')";
		$sql_insert_grade40a_total = "INSERT INTO GRADE40ATOTAL (acad_year, acad_session, course_id, student_id)
									VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$stu}')";
		$sql_insert_grade40b_total = "INSERT INTO GRADE40BTOTAL (acad_year, acad_session, course_id, student_id)
									VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$stu}')";
		$sql_insert_grade20_total = "INSERT INTO GRADE20TOTAL (acad_year, acad_session, course_id, student_id)
									VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$stu}')";
		
		//$pdo->exec($sql_insert_coursegrade);
		echo "<br>New student grade record created successfully for {$stu}, course {$course}<br>";
		
		//write to backup folder
		$student_backup_file = fopen("..\logs\startnewsession.sql", "a") or die("Unable to write to backup file t add student!");
		$txt = "\n".$sql_insert_coursegrade.";\n\n";
		fwrite($student_backup_file, $txt);
		$txt = "\n".$sql_insert_final_attn.";\n\n";
		fwrite($student_backup_file, $txt);
		$txt = "\n".$sql_insert_grade40a_total.";\n\n";
		fwrite($student_backup_file, $txt);
		$txt = "\n".$sql_insert_grade40b_total.";\n\n";
		fwrite($student_backup_file, $txt);
		$txt = "\n".$sql_insert_grade20_total.";\n\n";
		fwrite($student_backup_file, $txt);
		fclose($student_backup_file);
		
		
		
		//get course type
		$sql_course_type = "SELECT course_type from COURSE where course_id = '{$course}'";
		foreach ($pdo->query($sql_course_type) as $row) {
			$type = $row['course_type'];
			}
		echo "<br>{$type}<br>";

		//get dates for that course
		$sql_get_type_dates = "SELECT DATE_FORMAT(class_date, '%Y%m%d') AS 'class_date'  
								from SESSION_DATES
								where acad_year = {$acad_year}
								and acad_session = {$acad_session}
								and course_type = '{$type}'";
		
		//foreach date, add stu/course/date
		echo "Attendance date added for ";
		foreach ($pdo->query($sql_get_type_dates) as $row) {
			$class_date = $row['class_date'];
			$sql_insert_attendance = "INSERT INTO ATTENDANCE
										(course_id, acad_year, acad_session, student_id, class_date)
									VALUES
									('{$course}', {$acad_year}, {$acad_session}, '{$stu}', {$class_date})";
			//$pdo->exec($sql_insert_attendance);
			echo "{$class_date} ";
			
			//write to backup folder
			$student_backup_file = fopen("..\logs\startnewsession.sql", "a") or die("Unable to write to backup file t add student!");
			$txt = "\n".$sql_insert_attendance.";\n\n";
			fwrite($student_backup_file, $txt);
			fclose($student_backup_file);
			}
			echo "<br><br>";
			
			
			
		}
	
}
else {
	echo "Invalid option. Currently only new session starts are supported";
}

// if ($stu_id2 != $stu_id){ //ie, it's null or empty
	// //add
	// $sql_add_stu_to_contact = "INSERT INTO STUDENTCONTACT (student_id, stu_email1)
							// VALUES ('{$stu_id}', '{$new_email}')";
	// $pdo->exec($sql_add_stu_to_contact);
    // echo "<br>New student contact record created successfully for {$stu_id}, email {$new_email}<br>";
	
	// //write to backup folder
	// $student_backup_file = fopen("..\logs\studentcontact.sql", "a") or die("Unable to write to backup file t add student!");
	// $txt = "\n".$sql_add_stu_to_contact.";\n\n";
	// fwrite($student_backup_file, $txt);
	// fclose($student_backup_file);
// }
// else {
	// //update
	// $sql_alter_email = "update STUDENTCONTACT set stu_email1 = '{$stu_email}' where student_id = '{$stu_id}'";
	// $pdo->exec($sql_alter_email);
    // echo "<br>Email updated successfully for {$stu_id}, email {$new_email}<br>";
	
	// //write to backup folder
	// $student_backup_file = fopen("..\logs\studentcontact.sql", "a") or die("Unable to write to backup file t add student!");
	// $txt = "\n".$sql_alter_email.";\n\n";
	// fwrite($student_backup_file, $txt);
	// fclose($student_backup_file);
// }

Database::disconnect();
 ?>