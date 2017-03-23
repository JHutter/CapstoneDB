<?php

include_once('database.php');
include_once('get_name.php');
include_once('course_info.php');
include_once('forms.php');

function get_stu_list($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$stu_count = 0;
	$sql_stus = "SELECT courseenrollment.student_id AS 'student_id',
					CONCAT(stu_first, ' ', stu_last) AS 'SName',
					stu_email1
			FROM courseenrollment
			left join studentinfo on courseenrollment.student_id = studentinfo.student_id
			left join studentcontact on studentinfo.student_id = studentcontact.student_id
			WHERE acad_year = {$acad_year} 
			AND acad_session = {$acad_session}
			AND course_id = '{$course}'
			ORDER BY stu_last";
	
	$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
	$room = get_room($course, $acad_year, $acad_session);
	
	$table = "<h3>{$course} - {$teacher} - Room {$room}</h3><table><thead><tr><th>Student Name</th><th>ID</th></tr></thead>";

	foreach ($pdo->query($sql_stus) as $row_stus) {
		$stu_id = $row_stus['student_id'];
		$stu_name = $row_stus['SName'];
		
		$table .= "<tr><td>{$stu_name}</td><td>{$stu_id}</td></tr>";
		$stu_count++;
	}
	$span = "colspan=2";
	$table .= "<tr><td {$span}><br>Student count: {$stu_count}</tr></table>";

	Database::disconnect();
	$pdo = null;
	return $table;		
}

function get_stu_list_attn($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$date = date('l, F d');
	$title = "<h3>Attendance for {$course} for {$date}</h3>";
	$stu_count = 0;
	$sql_stus = "SELECT courseenrollment.student_id AS 'student_id',
					CONCAT(stu_first, ' ', stu_last) AS 'SName',
					stu_email1
				FROM courseenrollment
				left join studentinfo on courseenrollment.student_id = studentinfo.student_id
				left join studentcontact on studentinfo.student_id = studentcontact.student_id
				WHERE acad_year = {$acad_year} 
				AND acad_session = {$acad_session}
				AND course_id = '{$course}'
				ORDER BY stu_last";
			
	$class = "class=bordered";
	$table = "{$title}<table><thead><tr {$class}><th>Student Name</th><th>     Present?     </th><th>Comments</th></tr></thead>";

	foreach ($pdo->query($sql_stus) as $row_stus) {
		//$stu_id = $row_stus['student_id'];
		$stu_name = $row_stus['SName'];
		
		$table .= "<tr {$class}><td>{$stu_name}</td><td></td><td></td></tr>";
		$stu_count++;
	}
	$span = "colspan=3";
	$table .= "<tr><td {$span}><br>Student count: {$stu_count}</tr></table>";

	Database::disconnect();
	$pdo = null;
	return $table;		
}

function get_email_list($course, $acad_year, $acad_session){
	$pdo = Database::connect();
	$table = "<table><thead><tr><th colspan=2>Class Email List</th></tr></thead><tbody>";
	$table .= "<tr><td class=bold>Student</td><td class=bold>Email Address</td></tr>";
	
	$sql_get_stus = "select student_id from courseenrollment
					where course_id = '{$course}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}";
					
	foreach ($pdo->query($sql_get_stus) as $row_stus) {
		$student_id = $row_stus['student_id'];
		$student_name = get_student_name($student_id);
		$student_email = get_student_email($student_id);
		
		$table .= "<tr><td>{$student_name}</td><td>{$student_email}</td></tr>";
	}
	$table .= "</tbody></table>";
	
	Database::disconnect();
	$pdo = null;
	return $table;
}

function get_email_block($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$table = "<table><thead><tr><th>Class Email List</th></tr></thead><tbody>";
	$table .= "<tr><td class=bold>Student Email Address</td></tr><tr><td>";
	
	$sql_get_stus = "select student_id from courseenrollment
					where course_id = '{$course}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}";
					
	foreach ($pdo->query($sql_get_stus) as $row_stus) {
		$student_id = $row_stus['student_id'];
		$student_name = get_student_name($student_id);
		$student_email = get_student_email($student_id);
		
		$table .= "{$student_email},<br>";
	}
	$table .= "</td></tr></tbody></table>";
	
	Database::disconnect();
	$pdo = null;
	return $table;
}

function get_classlist_form($target_url) {
	$pdo = Database::connect();
	$form = "<form method=post action={$target_url} target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Class Lists By Course</th></tr></thead><tbody>";
	$form .= "<tr><td>Session:</td><td><select required name=session>";
	$form .= "<option value=1>1</option><option  value=2>2</option>";
	$form .= "<option value=3>3</option><option  value=4>4</option>";
	$form .= "<option value=5>5</option><option  value=6>6</option>";
	$form .= "<option value=7>7</option><option  value=8>8</option></td></tr>";
	$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
	$form .= "<tr><td>Course:</td><td><select required name=course>";
	$form .= "<option value=skill>All Courses, by skill</option>";
	$form .= "<option value=level>All Courses, by level</option>";
	
	$sql_get_all_courses = "select distinct courseassignment.course_id from courseassignment
							left join course on courseassignment.course_id = course.course_id
							order by course_type, sk_level, course_id";
	
	foreach ($pdo->query($sql_get_all_courses) as $row_all_courses) {
		$course = $row_all_courses['course_id'];
		$course_name = get_course_title($course);
		$section = substr($course, -1,1);
		$form .= "<option value={$course} >{$course_name} - {$section}</option>";
	}
	$form .= "</select></td></tr>";
	$form .= "</tbody></table><input type=submit class=button value='     Generate Report     '></form>";
	
	Database::disconnect();
	$pdo = null;
	return $form;
}

function get_assigned_course_form($target_url, $method) {
	$sessions = get_sessions_array();
	$years = get_years();
	
	$form = "<form method={$method} action={$target_url} target=_blank >";
	$form .= "<table><thead><tr><th colspan=2>Course Assignment Overview</th></tr></thead><tbody>";
	$form .= "<tr><td>Session:</td><td>";
	$form .= get_session_select('session', $sessions);
	$form .= "</td></tr>";

	$form .= "<tr><td>Year:</td><td>";
	$form .= get_session_select('year', $years);
	$form .= "</td></tr>";
	
	$form .= "<tr><td>Sort:</td>";
	$form .= "<td><input type=radio name=sort value=skill checked=checked>Skill<br>";
	$form .= "<input type=radio name=sort value=sk_level>Level<br></td></tr>";
	
	$form .= "</tbody></table><input type=submit class=button value='     Generate Report     '></form>";
	
	return $form;
}

function get_stu_by_status($status, $acad_session, $acad_year) {
	$pdo = Database::connect();
	
	$sql_stus = "select student_id from enroll_status
				where acad_session = {$acad_session}
				and acad_year = {$acad_year}
				and enroll_status = '{$status}'
				order by student_id";
	$students = [];
	
	foreach ($pdo->query($sql_stus) as $row_stus) {
		$student = $row_stus['student_id'];
		$students[] = $student;
	}
	
	Database::disconnect();
		$pdo = null;
	return $students;
}

function get_stu_by_status_term($status, $acad_term, $acad_year) {
	$pdo = Database::connect();
	$ses1 = get_session1($acad_year, $acad_term);
	$ses2 = get_session2($acad_year, $acad_term);
	
	$sql_stus = "select distinct student_id from enroll_status
				where (acad_session in ({$ses1}, {$ses2}))
				and acad_year = {$acad_year}
				and enroll_status = '{$status}'
				order by student_id";
	$students = [];
	
	foreach ($pdo->query($sql_stus) as $row_stus) {
		$student = $row_stus['student_id'];
		$students[] = $student;
	}
	
	Database::disconnect();
	$pdo = null;
	return $students;
}

function get_stus_by_course_alpha($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_stus = "select courseenrollment.student_id from courseenrollment
				left join studentinfo on courseenrollment.student_id = studentinfo.student_id
				where course_id = '{$course}'
				and acad_year = {$acad_year}
				and acad_session = {$acad_session}
				order by stu_last";
	$students = [];
	
	foreach ($pdo->query($sql_stus) as $row_stus) {
		$student = $row_stus['student_id'];
		$students[] = $student;
	}
	
	Database::disconnect();
	$pdo = null;
	return $students;
}

function get_teacher_courses_term($teacher) {
	$pdo = Database::connect();
	
	$courses = [];
	$sql_courses = "select distinct course_id from courseassignment where teacher_id = '{$teacher}'";
	
	foreach ($pdo->query($sql_courses) as $row_courses) {
		$course = $row_courses['course_id'];
		$courses[] = $course;
	}
	
	Database::disconnect();
	$pdo = null;
	return $courses;
	
	
}


			



?>