 <?php
 include_once('database.php');
 include_once('course_info.php');
 include_once('get_name.php');
 include_once('classlist.php');
 include_once('write_wrapper.php');
 
function populate_session($acad_year, $acad_session) {
	$pdo = Database::connect();
	$students = get_stu_by_status('active', $acad_session, $acad_year);
	$option = 'a';
	$message = "";
	$filename_all = "../logs/course/session{$acad_session}-{$acad_year}.sql";
	$acad_term = get_term_from_session($acad_year, $acad_session);
	$new_line = ";\n\n";
	
	foreach ($students as $student) {
		// if ($student != '25266') {continue;}
		$courses = get_courses_array($student, $acad_year, $acad_session);
		$message .= implode($courses);
		$filename = "../logs/course/{$student}-session{$acad_session}-{$acad_year}.sql";
		
		
		
		foreach ($courses as $course) {
			$course_type = get_type_from_course($course);
			$dates = get_course_dates_by_type($course_type, $acad_year, $acad_session);
			
			$sql_insert_coursegrade = "INSERT INTO SESSIONGRADE
										(course_type, acad_year, acad_session, student_id)
									VALUES
									('{$course_type}', {$acad_year}, {$acad_session}, '{$student}')";
			$sql_insert_termgrade = "INSERT INTO termGRADE
										(course_type, acad_year, acad_term, student_id)
									VALUES
									('{$course_type}', {$acad_year}, {$acad_term}, '{$student}')";
									
			$sql_insert_final_attn = "INSERT INTO ATTENDANCE_FINAL (acad_year, acad_session, course_id, student_id)
										VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student}')";
			$sql_insert_grade40a_total = "INSERT INTO GRADE40ATOTAL (acad_year, acad_session, course_id, student_id)
										VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student}')";
			$sql_insert_grade40b_total = "INSERT INTO GRADE40BTOTAL (acad_year, acad_session, course_id, student_id)
										VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student}')";
			$sql_insert_grade20_total = "INSERT INTO GRADE20TOTAL (acad_year, acad_session, course_id, student_id)
									VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student}')";
					
			// $message .= $sql_insert_coursegrade."<br><BR>";
			
		
			$pdo->exec($sql_insert_coursegrade);
			$pdo->exec($sql_insert_termgrade);
			$pdo->exec($sql_insert_final_attn);
			$pdo->exec($sql_insert_grade40a_total);
			$pdo->exec($sql_insert_grade40b_total);
			$pdo->exec($sql_insert_grade20_total);
			
			$sql_array = array($sql_insert_coursegrade, $sql_insert_termgrade, $sql_insert_final_attn, 
						$sql_insert_grade40a_total, $sql_insert_grade40b_total, $sql_insert_grade20_total);
			
			
			foreach ($sql_array as $statement) {
				$content = $statement.$new_line;
				write_to_file($filename, $option, $content);
				write_to_file($filename_all, $option, $content);
				$message .= $content."<br>";
			}
			
			foreach ($dates as $date) {
				$sql_insert_date = "insert into attendance (acad_year, acad_session, course_id, student_id, class_date)
				values ({$acad_year}, {$acad_session}, '{$course}', '{$student}', {$date})";
				
				$content = $sql_insert_date.$new_line;
				write_to_file($filename, $option, $content);
				write_to_file($filename_all, $option, $content);
				$pdo->exec($sql_insert_date);
			}
		}
		$message .= "<br><Br>";
		// break;
	}
	
	
	Database::disconnect();
	$pdo = null;
	return $message;
}

?>