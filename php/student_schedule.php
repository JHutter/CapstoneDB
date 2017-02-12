<!--- Begin body --->  
<?php 
include_once('database.php');
include_once('course_info.php');
include_once('forms.php');
include_once('get_name.php');


class Course{
		public $skill = '---';
		public $level = '---';
		public $room = '---';
		public $teacher = '---';
		public $type = '---';
}

function get_schedule_form_batch($target_url){
		$sessions = get_sessions_array();
		$form = "<form method=post action={$target_url} target=_blank>";
		$form .= "<table><thead><tr><th colspan=2>Generate Schedules as PDF on Server</th></tr></thead>";
		$form .= "<tbody>";
		$form .= "<tr><td>Session:</td><td>";
		$form .= get_session_select('session', $sessions);
		$form .= "</td></tr>";
		
		// <select required name=session>";
		// $form .= "<option value=1>1</option><option  value=2>2</option>";
		// $form .= "<option value=3>3</option><option  value=4>4</option></td></tr>";
		$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
		$form .= "<tr><td>Teacher/room option:</td><td><select required name=teacher_option>";
		$form .= "<option value=yes>With teacher/room</option>";
		$form .= "<option value=no>Without teacher/room (blank)</option></td></tr>";
		
		// $form .= "<tr><td>Report style:</td><td><select required name=pdf_option>";
		// $form .= "<option value=browser>See in browswer</option>";
		// $form .= "<option value=pdf>Download pdf</option></td></tr>";
		$form .= "</tbody></table><input type=submit class=button value='     Create All Report Cards     '></form>";
	
	return $form;
}

function student_schedule_block($student_id, $acad_year, $acad_session) {
	$start_date = get_start_date_formatted($acad_year, $acad_session);
	$end_date = get_end_date_formatted($acad_year, $acad_session);
	$session_dates = $start_date." - ".$end_date;
	$acad_term = get_term_from_session($acad_year, $acad_session);
	$student_name = get_student_name($student_id);
	$student_email = get_student_email($student_id);
	
	$html = "<h2>Student Schedule</h2>";

	$class_info = "class=info-table";
	$html .= "<table {$class_info}>";
	$html .= "<tbody><tr {$class_info}><td {$class_info}>Session: </td><td {$class_info}>Term {$acad_term}, Session {$acad_session}, {$acad_year}</td></tr>";
	$html .= "<tr {$class_info}><td {$class_info}>Session Dates: </td><td {$class_info}>{$session_dates}</td></tr>";
	$html .= "<tr {$class_info}><td {$class_info}>Name: </td><td {$class_info}>{$student_name}</td></tr><tr {$class_info}>";
	$html .= "<td {$class_info}>Student ID: </td><td {$class_info}>{$student_id}</td></tr>";
	$html .= "<tr {$class_info}><td {$class_info}>Email: </td><td {$class_info}>{$student_email}</td></tr></table><br>";
	
	return $html;
}

function get_schedule_table($student_id, $acad_year, $acad_session) {
	$courses = get_courses_array($student_id, $acad_year, $acad_session);
	
	$class = "class=bordered";
	$table .= "<table {$class}><thead><tr {$class}><th {$class}> </th><th {$class}>Monday</th><th {$class}>Tuesday</th>";
	$table .= "<th {$class}>Wednesday</th><th {$class}>Thursday</th><th {$class}>Friday</th></tr></thead><tbody>";
	
	
	$blank_block = "<td {$class}>---No class---<br><br>Level ---<br><br>Room ---<br><br><br></td>";
	$am10_block = $blank_block;
	$am11_block = $blank_block;
	$lunch_block = "<td {$class}><br>LUNCH<br><br></td>";
	$mwfpm_block = $blank_block;
	$tthpm_block = $blank_block;
	
	foreach ($courses as $course) {
		$type = get_type_from_course($course);
		$skill = str_replace("/", " /<br>", get_skill_from_course($course));
		$skill = get_skill_from_course($course);
		$level = get_level_from_course($course);
		$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
		$room = get_room_from_course($course, $acad_year, $acad_session);
		
		if ($type == '10am') {
			$am10_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}</td>";
		}
		else if ($type == '11am') {
			$am11_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}</td>";
		}
		else if ($type == 'mwfpm') {
			$mwfpm_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}<br><br>Finish 2:40 pm</td>";
		}
		else if ($type == 'tthpm') {
			$tthpm_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}<br><br>Finish 2:30 pm</td>";
		}
	}
	
	$table .= "<tr><td {$class}>10:00 - 10:55</td>";
	$table .= str_repeat($am10_block,5);
	$table .= "</tr>";
	
	$table .= "<tr><td {$class}>11:05 - 12:00</td>";
	$table .= str_repeat($am11_block,5);
	$table .= "</tr>";
	
	$table .= "<tr><td {$class}>12:00 - 12:55</td>";
	$table .= str_repeat($lunch_block,5);
	$table .= "</tr>";
	
	$table .= "<tr><td {$class}>1:00 pm</td>";
	$table .= str_repeat($mwfpm_block.$tthpm_block, 2);
	$table .= $mwfpm_block;
	$table .= "</tr>";
	
	$table .= "</tbody></table>";

	return $table;
}

function get_schedule_table_blank_teacher($student_id, $acad_year, $acad_session) {
	$courses = get_courses_array($student_id, $acad_year, $acad_session);
	
	$class = "class=bordered";
	$table .= "<table {$class}><thead><tr {$class}><th {$class}> </th><th {$class}>Monday</th><th {$class}>Tuesday</th>";
	$table .= "<th {$class}>Wednesday</th><th {$class}>Thursday</th><th {$class}>Friday</th></tr></thead><tbody>";
	
	
	$blank_block = "<td {$class}>---No class---<br><br>Level ---<br><br>Room ---<br><br><br></td>";
	$am10_block = $blank_block;
	$am11_block = $blank_block;
	$lunch_block = "<td {$class}><br>LUNCH<br><br></td>";
	$mwfpm_block = $blank_block;
	$tthpm_block = $blank_block;
	
	foreach ($courses as $course) {
		$type = get_type_from_course($course);
		$skill = str_replace("/", " /<br>", get_skill_from_course($course));
		$skill = get_skill_from_course($course);
		$level = get_level_from_course($course);
		$teacher = "__________";
		$room = "__________";
		
		if ($type == '10am') {
			$am10_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}</td>";
		}
		else if ($type == '11am') {
			$am11_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}</td>";
		}
		else if ($type == 'mwfpm') {
			$mwfpm_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}<br><br>Finish 2:40 pm</td>";
		}
		else if ($type == 'tthpm') {
			$tthpm_block = "<td {$class}>{$skill}<br><br>{$level}<br><br>Room {$room}<br><br>{$teacher}<br><br>Finish 2:30 pm</td>";
		}
	}
	
	$table .= "<tr><td {$class}>10:00 - 10:55</td>";
	$table .= str_repeat($am10_block,5);
	$table .= "</tr>";
	
	$table .= "<tr><td {$class}>11:05 - 12:00</td>";
	$table .= str_repeat($am11_block,5);
	$table .= "</tr>";
	
	$table .= "<tr><td {$class}>12:00 - 12:55</td>";
	$table .= str_repeat($lunch_block,5);
	$table .= "</tr>";
	
	$table .= "<tr><td {$class}>1:00 pm</td>";
	$table .= str_repeat($mwfpm_block.$tthpm_block, 2);
	$table .= $mwfpm_block;
	$table .= "</tr>";
	
	$table .= "</tbody></table>";

	return $table;
}
	 
function get_student_sched_form($target_url) {
	$students = get_all_stus();
	$sessions = get_sessions_array();
	$years = get_years();
	
	$form = "<form method=get action={$target_url} target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Student Schedule</th></tr></thead>";
	$form .= "<tbody><tr><td>Student ID:</td>";
	$form .= "<td><input name=student list=student><datalist id=student>";
	$form .= get_stu_datalist($students);
	$form .= "</datalist></td></tr>";

	$form .= "<tr><td>Session:</td><td>";
	$form .= get_session_select('session', $sessions);
	$form .= "</td></tr>";

	$form .= "<tr><td>Year:</td><td>";
	$form .= get_session_select('year', $years);
	$form .= "</td></tr>";
	
	$form .= "</tbody></table><input type=submit class=button value='     Generate Report     '></form>";
	
	return $form;
}

?>