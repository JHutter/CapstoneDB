<?php
include_once('database.php');

function get_teacher_name($teacher_id) {
	$pdo = Database::connect();
	
	$sql_get_teacher_name = "select concat(teacher_first, ' ', teacher_last) as 't_name' from teacher where teacher_id = '{$teacher_id}'";
	foreach ($pdo->query($sql_get_teacher_name) as $row_name) {
		$name = $row_name['t_name'];
	}
	Database::disconnect();
	$pdo = null;
	return $name;
}

function get_student_name($student_id) {
	$pdo = Database::connect();
	
	$sql_get_stu_name = "select concat(stu_first, ' ', stu_last) as 's_name' from studentinfo where student_id = '{$student_id}'";
	
	foreach ($pdo->query($sql_get_stu_name) as $row_name) {
		$name = $row_name['s_name'];
	}
	Database::disconnect();
	$pdo = null;
	return $name;
}

function get_student_entry($student_id) {
	$pdo = Database::connect();
	
	$sql_get_entry = "select entry_type
					from stu_entry_status
					where student_id = {$student_id}";
	
	foreach ($pdo->query($sql_get_entry) as $row_entry) {
		$entry = $row_entry['entry_type'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $entry;
	
}

function get_student_entry_name($student_id) {
	$pdo = Database::connect();
	
	$sql_get_entry = "select entry_type
					from stu_entry_status
					where student_id = {$student_id}";
	
	foreach ($pdo->query($sql_get_entry) as $row_entry) {
		$entry = $row_entry['entry_type'];
	}
	
	if ($entry == 'full-term entry') {
		$entry = 'Full Term Entry';
	}
	else if ($entry == 'mid-term entry') {
		$entry = 'Mid-Term Entry';
	}
	
	Database::disconnect();
	$pdo = null;
	return $entry;
	
}

function get_student_email($student_id) {
	$pdo = Database::connect();
	
	$sql_get_email = "select stu_email1 from studentcontact where student_id = '{$student_id}'";
	
	foreach ($pdo->query($sql_get_email) as $row_email) {
		$email = $row_email['stu_email1'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $email;
}

function get_all_stus() {
	$pdo = Database::connect();
	$sql_all_stus = "select student_id from studentinfo order by student_id";
	$students = [];
	foreach ($pdo->query($sql_all_stus) as $row_all_stus) {
		$student = $row_all_stus['student_id'];
		
		$students[] = $student;
	}
	
	Database::disconnect();
	$pdo = null;
	return $students;
}

function get_years(){ 
	$pdo = Database::connect();
	$sql_get_years = "select distinct acad_year from session_dates order by acad_year";
	$years = [];
	foreach ($pdo->query($sql_get_years) as $row_years) {
		$year = $row_years['acad_year'];
		$years[] = $year;
	}
	
	Database::disconnect();
	$pdo = null;
	return $years;
}

function get_attendance_type_admit(){ 
	$pdo = Database::connect();
	$sql_get_attn = "select attendance_yn from attendance_type order by attendance_yn";
	$types = [];
	foreach ($pdo->query($sql_get_attn) as $row_attn) {
		$type = $row_attn['attendance_yn'];
		$types[] = $type;
	}
	
	Database::disconnect();
	$pdo = null;
	return $types;
}

function get_attendance_type_teach(){ 
	$pdo = Database::connect();
	$sql_get_attn = "select attendance_yn from attendance_type where permission = 'teach' order by attn_order";
	$types = [];
	foreach ($pdo->query($sql_get_attn) as $row_attn) {
		$type = $row_attn['attendance_yn'];
		$types[] = $type;
	}
	
	Database::disconnect();
	$pdo = null;
	return $types;
}

function get_course_types() {
	$pdo = Database::connect();
	
	$sql_course_types = "select course_type from course_type where course_type_name is not null";
	$types = [];
	
	foreach ($pdo->query($sql_course_types) as $row_types) {
		$type = $row_types['course_type'];
		$types[] = $type;
	}
	
	Database::disconnect();
	$pdo = null;
	return $types;
}

function get_courses() {
	$pdo = Database::connect();
	
	$sql_courses = "select distinct course_id from courseassignment";
	$courses = [];
	
	foreach ($pdo->query($sql_courses) as $row_courses) {
		$course = $row_courses['course_id'];
		$courses[] = $course;
	}
	
	Database::disconnect();
	$pdo = null;
	return $courses;
}

function get_course_type_name($type) {
	$pdo = Database::connect();
	
	$sql_course_type_name = "select course_type_name from course_type where course_type = '{$type}'";
	
	foreach ($pdo->query($sql_course_type_name) as $row_name) {
		$name = $row_name['course_type_name'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $name;
}

function get_session_by_date($date) {
	$pdo = Database::connect();
	$sql_get_session = "select acad_session from calendar 
				where session_start <= {$date}
				order by acad_session asc";
	foreach ($pdo->query($sql_get_session) as $row_session) {
		$session = $row_session['acad_session'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $session;
}

function get_student_course_by_type($student_id, $acad_year, $acad_session, $type) {
	$pdo = Database::connect();
	$sql_course = "select courseenrollment.course_id from courseenrollment
					left join course on courseenrollment.course_id = course.course_id
					where student_id = '{$student_id}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_type = '{$type}'";
					
	foreach ($pdo->query($sql_course) as $row_course) {
		$course = $row_course['course_id'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $course;
}

function get_courses_array($student_id, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_sched = "select	distinct COURSEENROLLMENT.course_id AS 'course_id'
				from COURSEENROLLMENT
				left join COURSE on COURSEENROLLMENT.course_id = COURSE.course_id
				WHERE 	COURSEENROLLMENT.acad_year = {$acad_year}
				AND 	COURSEENROLLMENT.acad_session = {$acad_session}
				AND 	COURSEENROLLMENT.student_id = '{$student_id}'
				ORDER BY COURSE.course_type";

	$courses = [];
	foreach ($pdo->query($sql_sched) as $row_course){
		$course = $row_course['course_id'];
		$courses[] = $course;
	}
	
	
	Database::disconnect();
	$pdo = null;
	return $courses;
}

function get_sessions_array() {
	$pdo = Database::connect();
	
	$sql_sessions = "select distinct acad_session from calendar order by acad_session";
	$sessions = [];
	
	foreach ($pdo->query($sql_sessions) as $row) {
		$session = $row['acad_session'];
		$sessions[] = $session;
	}
	
	Database::disconnect();
	$pdo = null;
	return $sessions;
}

function get_stu_status_by_session($student_id, $acad_session, $acad_year) {
	$pdo = Database::connect();
	
	$sql_status = "select enroll_status from enroll_status
				where acad_year = {$acad_year}
				and acad_session = {$acad_session}
				and student_id = '{$student_id}'";
	
	$status = 'none';
	
	foreach ($pdo->query($sql_status) as $row_status) {
		$status = $row_status['enroll_status'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $status;
}

function get_stu_status_by_session_nice($student_id, $acad_session, $acad_year) {
	$status = get_stu_status_by_session($student_id, $acad_session, $acad_year);
	
	switch ($status) {
		case 'active':
			$status = 'Active';
			break;
		case 'med leave':
			$status = 'Medical Leave';
			break;
		case 'term':
			$status = 'Terminated';
			break;
		case 'loa':
			$status = 'Leave of Absence';
			break;
		case 'none':
			$status = 'None';
			break;
		default:
	}
	
	return $status;
}

function get_sessions_by_term($acad_year, $acad_term) {
	$pdo = Database::connect();
	$sessions = [];
	
	$sql_sessions = "select acad_session from calendar where acad_term = {$acad_term} order by acad_session";
	
	foreach ($pdo->query($sql_sessions) as $row_ses) {
		$session = $row_ses['acad_session'];
		$sessions[] = $session;
	}
	
	Database::disconnect();
	$pdo = null;
	return $sessions;
}

function get_terms_array() {
	$pdo = Database::connect();
	
	$sql_terms = "select distinct acad_term from calendar order by acad_term";
	$terms = [];
	
	foreach ($pdo->query($sql_terms) as $row) {
		$term = $row['acad_term'];
		$terms[] = $term;
	}
	
	Database::disconnect();
	$pdo = null;
	return $terms;
}

function get_entry_term($student) {
	$pdo = Database::connect();
	$term = 0;
	
	$sql_get_entry_term = "select acad_term from stu_entry_status where student_id = '{$student}'";
	
	foreach ($pdo->query($sql_get_entry_term) as $row_term) {
		$term = $row_term['acad_term'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $term;
}

?>