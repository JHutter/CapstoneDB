<?php
include_once('database.php');
include_once('write_wrapper.php');
include_once('course_info.php');

function get_new_student_form($target_url) {
	$form = "<Form action={$target_url} method=post target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Form: Add New Student</th></tr></thead>";
	$form .= "<tbody><tr><td align=left>Student ID</td><td><input required type=text name=student_id></td></tr>";
	$form .= "<tr><td align=left>First Name</td><td><input required type=text name=stu_first></td></tr>";
	$form .= "<tr><td align=left>Last Name</td><td><input required type=text name=stu_last></td></tr>";
	$form .= "<tr><td align=left>Email</td><td><input required type=text name=email></td></tr>";
		
	$form .= "<tr><td align=left>Start Year</td>";
		$form .= "<td><select required name=year>";
		$form .= "<option>Select...</option>";
		$form .= "<option value=2016>2016</option>";
		$form .= "<option value=2017>2017</option>";
		$form .= "</select></td></tr>";
		
	$form .= "<tr><td align=left>Start Session</td>";
		$form .= "<td><select required name=session >";
		$form .= "<option>Select...</option>";
		$form .= "<option value=1>1</option>";
		$form .= "<option value=2>2</option>";
		$form .= "<option value=3>3</option>";
		$form .= "<option value=4>4</option>";
		$form .= "<option value=5>5</option>";
		$form .= "<option value=6>6</option>";
		$form .= "<option value=7>7</option>";
		$form .= "<option value=8>8</option>";
		$form .= "</select></td></tr>";
		
	$form .= "<tr><td align=left>Speaking / Listening</td>";
		$form .= "<td><select name=10am style=min-width:95%>";
		$form .= "<option value='' >Select...</option>";
		$form .= "<option value=SL1A-1>1A Speaking / Listening</option>";
		$form .= "<option value=SL1B-1>1B Speaking / Listening</option>";
		$form .= "<option value=SL2A-1>2A Speaking / Listening</option>";
		$form .= "<option value=SL2B-1>2B Speaking / Listening</option>";
		$form .= "<option value=SL3A-1>3A Speaking / Listening</option>";
		$form .= "<option value=SL3B-1>3B Speaking / Listening</option>";
		$form .= "<option value=PS4A-1>4A Presentations / Public Speaking</option>";
		// $form .= "<option value=none>none</option>";
		$form .= "</select></td></tr>";
		
	$form .= "<tr><td align=left>Reading / Writing</td>";
		$form .= "<td><select name=11am style=min-width:95%>";
		$form .= "<option value='' >Select...</option>";
		$form .= "<option value=RW1A-1>1A Reading / Writing</option>";
		$form .= "<option value=RW1B-1>1B Reading / Writing</option>";
		$form .= "<option value=RW2A-1>2A Reading / Writing</option>";
		$form .= "<option value=RW2B-1>2B Reading / Writing</option>";
		$form .= "<option value=RW3A-1>3A Reading / Writing</option>";
		$form .= "<option value=RW3B-1>3B Reading / Writing</option>";
		$form .= "<option value=WG4A-1>4A Applied Writing / Grammar</option>";
		// $form .= "<option value=none>none</option>";
		$form .= "</select></td></tr>";
		
	$form .= "<tr><td align=left >Grammar</td>";
		$form .= "<td><select name=mwfpm style=min-width:95%>";
		$form .= "<option value=''>Select...</option>";
		$form .= "<option value=GR1A-1>1A Grammar</option>";
		$form .= "<option value=GR1B-1>1B Grammar</option>";
		$form .= "<option value=GR2A-1>2A Grammar</option>";
		$form .= "<option value=GR2B-1>2B Grammar</option>";
		$form .= "<option value=GR3A-1>3A Grammar</option>";
		$form .= "<option value=CE4A-1>4A Current Events</option>";
		// $form .= "<option value=none>none</option>";
		$form .= "</select></td></tr>";
		
	$form .= "<tr><td align=left >Skills Course</td>";
		$form .= "<td><select name=tthpm style=min-width:95%>";
		$form .= "<option value=''>Select...</option>";
		$form .= "<option value=LF1A-1>1A Life Skills</option>";
		$form .= "<option value=LF1B-1>1B Life Skills</option>";
		$form .= "<option value=VC2A-1>2A Vocabulary</option>";
		$form .= "<option value=VC2B-1>2B Vocabulary</option>";
		$form .= "<option value=VC3A-1>3A Vocabulary</option>";
		$form .= "<option value=VC3B-1>3B Vocabulary</option>";
		$form .= "<option value=TOEFL-1>3 TOEFL</option>";
		$form .= "<option value=NR4A-1>4A Novel Reading</option>";
		// $form .= "<option value=none>none</option>";
		$form .= "</select></td></tr>";
		
	
		
	$form .= "<tr></table><input value='Add Student' type=submit class=button></form>";
	return $form;
}

function add_new_student($student_id, $stu_first, $stu_last, $email, $courses, $acad_year, $acad_session){
	$pdo = Database::connect();
	$filename = "../logs/enrollment/{$student_id}_enrollment.sql";
	$option = "a";
	$date = date('m/d/Y');
	$acad_term = get_term_from_session($acad_year, $acad_session);

	$check_stu_id_new = "SELECT student_id from studentinfo where student_id = '{$student_id}'";
	$stu_id = "";
	foreach ($pdo->query($check_stu_id_new) as $row_check_id) {
			$stu_id = $row_check_id['student_id'];
	}

	// check to make sure that new stu ID is not already in use
	if ($stu_id == $student_id){ // entered student id is already in use
		return "Invalid id. The student ID you entered already exists in the database.";
	} 
	else {
		// execute sql to add new student
		//echo "Valid id";
		$insert_new_stu = "INSERT INTO studentinfo (student_id, stu_first, stu_last)
		VALUES ('{$student_id}', '{$stu_first}', '{$stu_last}')";
		$pdo->exec($insert_new_stu);
		$table = "<br>New record created successfully for {$stu_first} {$stu_last}, ID {$student_id}<br>";
		
		//write to backup folder
		$content = "\n#{$stu_first} {$stu_last}, ID {$student_id} added on {$date}\n".$insert_new_stu.";\n\n";
		write_to_file($filename, $option, $content);
		
		//insert email here
		$sql_add_stu_to_contact = "INSERT INTO studentcontact (student_id, stu_email1)
							VALUES ('{$student_id}', '{$email}')";
		$pdo->exec($sql_add_stu_to_contact);
		$table .= "<br>New student contact record created successfully for {$student_id}, email {$email}<br>";
		
		// execute sql to add stu to courses
		foreach ($courses as $course){
			$insert_new_stu_course = "INSERT INTO courseenrollment (course_id, acad_year, acad_session, student_id)
				VALUES ('{$course}', {$acad_year}, {$acad_session}, '{$student_id}')";
			$pdo->exec($insert_new_stu_course);
			//$table .= $insert_new_stu_course;
			$table .= "<br>ID {$student_id} added to {$course}";
			$course_type = get_type_from_course($course);
			
			// write to backup folder
			$content = "\n#{$course} added to student schedule for {$acad_year}, Session {$acad_session}\n".$insert_new_stu_course.";\n\n";
			write_to_file($filename, $option, $content);
			$table .= "<br>{$course} added to student schedule<br>";
			
			$sql_insert_coursegrade = "INSERT INTO sessiongrade (course_type, acad_year, acad_session, student_id)
									VALUES ('{$course_type}', {$acad_year}, {$acad_session}, '{$student_id}')";
			$pdo->exec($sql_insert_coursegrade);
			$sql_insert_termgrade = "INSERT INTO termgrade (course_type, acad_year, acad_term, student_id)
									VALUES ('{$course_type}', {$acad_year}, {$acad_term}, '{$student_id}')";
			$pdo->exec($sql_insert_termgrade);
			$sql_insert_final_attn = "INSERT INTO attendance_final (acad_year, acad_session, course_id, student_id)
										VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student_id}')";
			$pdo->exec($sql_insert_final_attn);
			$sql_insert_grade40a_total = "INSERT INTO grade40atotal (acad_year, acad_session, course_id, student_id)
										VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student_id}')";
			$pdo->exec($sql_insert_grade40a_total);
			$sql_insert_grade40b_total = "INSERT INTO grade40btotal (acad_year, acad_session, course_id, student_id)
										VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student_id}')";
			$pdo->exec($sql_insert_grade40b_total);
			$sql_insert_grade20_total = "INSERT INTO grade20total (acad_year, acad_session, course_id, student_id)
									VALUES ({$acad_year}, {$acad_session}, '{$course}', '{$student_id}')";
			$pdo->exec($sql_insert_grade20_total);
			$content = $sql_insert_coursegrade.";\n";
			$content .= $sql_insert_termgrade.";\n";
			$content .= $sql_insert_final_attn.";\n";
			$content .= $sql_insert_grade40a_total.";\n";
			$content .= $sql_insert_grade40b_total.";\n";
			$content .= $sql_insert_grade20_total.";\n";
			write_to_file($filename, $option, $content);
			
			$table .= "<br>New student grade record created successfully for {$student_id}, course {$course}<br>";
			
			
			// $sql_course_type = "SELECT course_type from course where course_id = '{$course}'";
			// foreach ($pdo->query($sql_course_type) as $row) {
				// $type = $row['course_type'];
			// }

			//get dates for that course
			$sql_get_type_dates = "SELECT DATE_FORMAT(class_date, '%Y%m%d') AS 'class_date'  
								from session_dates
								where acad_year = {$acad_year}
								and acad_session = {$acad_session}
								and course_type = '{$course_type}'";
		
			//foreach date, add stu/course/date
			foreach ($pdo->query($sql_get_type_dates) as $row_dates) {
				$class_date = $row_dates['class_date'];
				$sql_insert_attendance = "INSERT INTO attendance (course_id, acad_year, acad_session, student_id, class_date)
										VALUES ('{$course}', {$acad_year}, {$acad_session}, '{$student_id}', {$class_date})";
				$pdo->exec($sql_insert_attendance);
				
				//write to backup folder
				$student_backup_file = fopen("..\logs\startnewsession.sql", "a");
				$content = "\n".$sql_insert_attendance.";\n\n";
				write_to_file($filename, $option, $content);
			}
		}
		
		if ($acad_session % 2 == 1) {
			$entry = 'full-term entry';
		}
		else {
			$entry = 'mid-term entry';
		}
		$sql_insert_entry = "insert into stu_entry_status
							(student_id, entry_type, acad_year, acad_term)
							VALUES ('{$student_id}', '{$entry}', {$acad_year}, {$acad_term})";
		$pdo->exec($sql_insert_entry);
		$table .= "<br>Entry status for this student is {$entry}.";
		
		$content = "\n#{$entry} status added\n".$sql_insert_entry.";\n\n";
		write_to_file($filename, $option, $content);
		
		$enroll = 'active';
		$sql_insert_enroll = "insert into enroll_status
							(student_id, enroll_status, acad_year, acad_session)
							VALUES ('{$student_id}', '{$enroll}', {$acad_year}, {$acad_session})";
		$pdo->exec($sql_insert_enroll);
		$table .= "<br>Enroll status for this student is {$enroll}.";
		
		$content = "\n#{$enroll} status added\n".$sql_insert_enroll.";\n\n";
		write_to_file($filename, $option, $content);
	}
	Database::disconnect();
	return $table;
}













?>