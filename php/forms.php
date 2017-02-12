<?php
include_once('database.php');
include_once('get_name.php');
include_once('course_info.php');

function get_stu_datalist($students) {
	$datalist = "";
	foreach ($students as $student) {
		$name = get_student_name($student);
		$datalist .= "<option value={$student}>{$name}</option>";
	}
	return $datalist;
}

function get_month_input($name) {
	$select = "<select required name={$name}>";
	$select .= "<option value=01>1 January</option>";
	$select .= "<option value=02>2 February</option>";
	$select .= "<option value=03>3 March</option>";
	$select .= "<option value=04>4 April</option>";
	$select .= "<option value=05>5 May</option>";
	$select .= "<option value=06>6 June</option>";
	$select .= "<option value=07>7 July</option>";
	$select .= "<option value=08>8 August</option>";
	$select .= "<option value=09>9 September</option>";
	$select .= "<option value=10>10 October</option>";
	$select .= "<option value=11>11 November</option>";
	$select .= "<option value=12>12 December</option>";
	$select .= "</select>";
	
	return $select;
}

function get_day_input($name) {
	$days = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10",
					"11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
					"21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31");
	
	$select = "<select required name={$name}>";
	
	foreach ($days as $day) {
		$select .= "<option value={$day}>{$day}</option>";
	}
	$select .= "</select>";
	
	
	return $select;
}

function get_year_input($name){
	$years = get_years();
	
	$select = "<select required name={$name}>";
	
	foreach ($years as $year) {
		$select .= "<option value={$year}>{$year}</option>";
	}
	$select .= "</select>";
	
	
	return $select;
}

function get_attendance_option_admit(){
	$attendance_types = get_attendance_type_admit();
	
	$select = "<select required name=attendance>";
	
	foreach ($attendance_types as $attn) {
		$select .= "<option value='{$attn}'>{$attn}</option>";
	}
	$select .= "</select>";
	
	
	return $select;
}

function get_course_type_checkbox(){
	$course_types = get_course_types();
	// $course_types = array('10am', '11am');
	
	$checkboxes = "";
	
	foreach ($course_types as $type) {
		$type_name = get_course_type_name($type);
		$checkboxes .= "<input type=checkbox name=courses[] value='{$type}'>{$type_name}<br>";
	}
	
	
	return $checkboxes;
}

function get_course_select($name, $courses) {
	$select = "<select required name={$name}>";
	
	foreach ($courses as $course) {
		$course_name = get_course_title($course);
		$select .= "<option value='{$course}'>{$course_name} {$course}</option>";
	}
	$select .= "</select>";
	
	return $select;
}

function get_session_select($name, $sessions) {
	$select = "<select required name={$name}>";
	
	foreach ($sessions as $session) {
		$select .= "<option value={$session}>{$session}</option>";
	}
	$select .= "</select>";
	
	return $select;
	
}

function get_course_type_select($name) {
	$types = get_skills();
	$select = "<select required name={$name}>";
	
	foreach ($types as $type) {
		$type_name = get_nice_skills($type);
		$select .= "<option value={$type}>{$type_name}</option>";
	}
	$select .= "</select>";
	
	return $select;
}



?>