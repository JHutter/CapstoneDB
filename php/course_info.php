<?php
include_once('database.php');
	
function get_course_from_skill($course_type, $student_id, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_course_id = "select courseenrollment.course_id as 'course_id'
						from courseenrollment
						join course on courseenrollment.course_id = course.course_id
						where student_id = '{$student_id}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course.course_type = '{$course_type}'
						";
	
	foreach ($pdo->query($sql_get_course_id) as $row) {
		$course = $row['course_id'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $course;
}

function has_course_from_skill($course_type, $student_id, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_course_id = "select courseenrollment.course_id as 'course_id'
						from courseenrollment
						join course on courseenrollment.course_id = course.course_id
						where student_id = '{$student_id}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course.course_type = '{$course_type}'
						";
	
	$result = $pdo->prepare($sql_get_course_id); 
	$result->execute(); 
	if ($result->rowCount() == 0) {
		$course = false;
	}
	else {
		$course = true;
	}
	
	Database::disconnect();
	$pdo = null;
	return $course;
}
	
function get_type_from_course($course) {
	$pdo = Database::connect();
	
	$sql_get_type = "select course_type from course where course_id = '{$course}'";
	
	foreach ($pdo->query($sql_get_type) as $row_type) {
		$course_type = $row_type['course_type'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $course_type;
}
	
function get_course_title($course) {
	$pdo = Database::connect();
	
	$sql_get_course_title = "select concat(skill, ' ', sk_level) as 'course_title'
						from course
						where course_id = '{$course}'
						";

	foreach ($pdo->query($sql_get_course_title) as $row) {
		$course = $row['course_title'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $course;
}
	

function get_teacher_from_course($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_teacher = "select concat(teacher_first, ' ', teacher_last) as 'teacher'
						from courseassignment
						join teacher on courseassignment.teacher_id = teacher.teacher_id
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						";
	
	foreach ($pdo->query($sql_get_teacher) as $row) {
		$course = $row['teacher'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $course;
}

function get_room_from_course($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_room = "select room
						from courseassignment
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						";
	
	foreach ($pdo->query($sql_get_room) as $row) {
		$course = $row['room'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $course;
}
	
function get_skill_from_course($course) {
	$pdo = Database::connect();
	
	$sql_skill = "select skill from course where course_id = '{$course}'";
	
	foreach ($pdo->query($sql_skill) as $row_skill) {
		$skill = $row_skill['skill'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $skill;
}
	
function get_level_from_course($course) {
	$pdo = Database::connect();
	
	$sql_level = "select sk_level from course where course_id = '{$course}'";
	
	foreach ($pdo->query($sql_level) as $row_level) {
		$level = $row_level['sk_level'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $level;
}
	
function get_start_date_formatted($acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_date_string = "select session_start from calendar 
							where acad_year = {$acad_year} and acad_session = {$acad_session}";
							
	foreach ($pdo->query($sql_get_date_string) as $row_date) {
		$date = $row_date['session_start'];
	}
	$date = DateTime::createFromFormat('Y-m-d', $date);
	
	Database::disconnect();
	$pdo = null;
	return date_format($date, 'M d, Y');
}
	
function get_end_date_formatted($acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_date_string = "select session_end from calendar 
							where acad_year = {$acad_year} and acad_session = {$acad_session}";
							
	foreach ($pdo->query($sql_get_date_string) as $row_date) {
		$date = $row_date['session_end'];
	}
	$date = DateTime::createFromFormat('Y-m-d', $date);
	
	Database::disconnect();
	$pdo = null;
	return date_format($date, 'M d, Y');
}
	
function get_term_from_session($acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_term = "select acad_term from calendar 
	where acad_session = {$acad_session} and acad_year = {$acad_year}";
	
	foreach ($pdo->query($sql_get_term) as $row_term) {
		$acad_term = $row_term['acad_term'];
	}
	
	Database::connect();
	return $acad_term;
}
	
function get_room($course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_room = "select room from courseassignment
				where acad_year = {$acad_year}
				and acad_session = {$acad_session}
				and course_id = '{$course}'";
				
	foreach ($pdo->query($sql_room) as $row_room) {
		$room = $row_room['room'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $room;
}
	
	//sort can be sk_level or skill
function get_assigned_courses_by_session($acad_year, $acad_session, $sort) {
	if ($sort == 'sk_level') {
		$sort2 = 'course_type';
	}
	else {
		$sort = 'course_type';
		$sort2 = 'sk_level';
	}
	$pdo = Database::connect();
	$sql_assigned_courses = "select courseassignment.course_id from courseassignment 
						left join course on courseassignment.course_id = course.course_id
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						order by {$sort}, {$sort2}";

	$courses = [];
	
	foreach ($pdo->query($sql_assigned_courses)as $row_courses) {
		$course = $row_courses['course_id'];
		$courses[] = $course;
	}
						
	Database::disconnect();
	$pdo = null;
	return $courses;
}

function get_stu_count_course($acad_year, $acad_session, $course) {
	$pdo = Database::connect();
	
	$sql_get_count = "select count(student_id) as 'count'
					from courseenrollment
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'";
	
	foreach ($pdo->query($sql_get_count) as $row) {
		$count = $row['count'];
	}
	
	Database::disconnect();
	$pdo = null;
	return $count;
}

function get_assigned_course_table($acad_year, $acad_session, $courses) {
	$table = "<table><thead><tr><th colspan=6>Course Assignment for Session {$acad_session}, {$acad_year}</th></tr></thead><tbody>";
	$table .= "<tr ><td class=bold>Course ID</td><td class=bold>Skill</td><td class=bold>Level</td>";
	$table .= "<td class=bold># Students</td><td class=bold>Teacher</td><td class=bold>Room</td></tr>";
	
	foreach ($courses as $course) {
		$skill = get_skill_from_course($course);
		$level = get_level_from_course($course);
		$stu_count = get_stu_count_course($acad_year, $acad_session, $course);
		$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
		$room = get_room_from_course($course, $acad_year, $acad_session);
		
		$table .= "<tr><td>{$course}</td><td>{$skill}</td><td>{$level}</td><td>{$stu_count}</td><td>{$teacher}</td><td>{$room}</td></tr>";
	}
	
	$course_count = sizeof($courses);
	$table .= "<tr><td colspan=6>Course count: {$course_count}</td></tr></tbody></table>";
	
	return $table;
}

function get_session1($acad_year, $acad_term) {
	$pdo = Database::connect();
	
	$sql_session = "select acad_session from calendar
					where acad_year = {$acad_year}
					and acad_term = {$acad_term}
					order by acad_session asc
					limit 1";
	
	foreach ($pdo->query($sql_session) as $row_ses) {
		$session = $row_ses['acad_session'];
	}
	
	
	Database::disconnect();
	$pdo = null;
	return $session;
}

function get_session2($acad_year, $acad_term) {
	$pdo = Database::connect();
	
	$sql_session = "select acad_session from calendar
					where acad_year = {$acad_year}
					and acad_term = {$acad_term}
					order by acad_session desc
					limit 1";
	
	foreach ($pdo->query($sql_session) as $row_ses) {
		$session = $row_ses['acad_session'];
	}
	
	
	Database::disconnect();
	$pdo = null;
	return $session;
}

function get_course_dates_by_type($course_type, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$dates = [];
	
	$sql_dates = "select DATE_FORMAT(class_date,'%Y%m%d') as class_date 
	from session_dates
	where acad_year = {$acad_year}
	and acad_session = {$acad_session}
	and course_type = '{$course_type}'
	order by class_date";
	
	foreach ($pdo->query($sql_dates) as $row_dates) {
		$date = $row_dates['class_date'];
		$dates[] = $date;
	}
	
	Database::disconnect();
	$pdo = null;
	return $dates;
}

function get_categories() {
	return ['40a', '40b', '20'];
}

function get_skills() {
	return ['10am','11am','mwfpm','tthpm'];
}

function get_nice_skills($type) {
	$pdo = Database::connect();
	$sql_types = "select course_type_name from course_type where course_type = '{$type}'";
	$type_name = "";
	
	foreach ($pdo->query($sql_types) as $row_type) {
		$type_name = $row_type['course_type_name'];
	}
	
	return $type_name;
}
?>