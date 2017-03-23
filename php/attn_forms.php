<?php
	include_once('database.php');
	include_once('course_info.php');
	include_once('get_name.php');
	include_once('forms.php');

	function get_input_class_attn($course, $acad_year, $acad_session, $current_date, $target_url) {
		$pdo = Database::connect();
		
		$present = 0;
		$not_taken = 0;
		$late = 0;
		$absent = 0;
		$can_take_attn = 0;
		$sql_get_stus = "SELECT courseenrollment.student_id AS 'sID',
						CONCAT(stu_first, ' ', stu_last) AS 'SName'
						FROM courseenrollment
						left join studentinfo on courseenrollment.student_id = studentinfo.student_id
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						order by stu_last";
		
		$form = "<Form action={$target_url}><table><thead><th align=center colspan=4>Input Attendance: ";
		$form .= "<input type=hidden name=date value={$current_date}>";
		$form .= '<select required name=course>';
		$form .= "<option value={$course}>{$course}</option></select></td></th></thead><tbody><tr><td class=bold>Student </td><td class=bold>ID</td>";
		$form .= '<td class=bold>Attendance</td><td class=bold>Enter/Change Attendance</td></tr>';

		//each student	
		foreach ($pdo->query($sql_get_stus) as $row_stus) {
			$name = $row_stus['SName'];
			$id = $row_stus['sID'];
			
			$sql_get_attn = "SELECT attendance_yn
							from attendance
							where student_id = '{$id}'
							and course_id = '{$course}'
							and acad_year = {$acad_year}
							and acad_session = {$acad_session}
							and class_date = {$current_date}";	
							
		foreach ($pdo->query($sql_get_attn) as $row_attn) {
			$attn = $row_attn['attendance_yn'];
		}
		
		$td_class = '';
		switch ($attn) {
			case 'none':
				$not_taken++;
				$td_class = "class='bold'";
				break;
			case 'present':
				$present++;
				break;
			case 'late':
				$late++;
				$td_class = "class=show_red";
				break;
			case 'absent':
				$absent++;
				$td_class = "class=show_red";
				break;
		}

		$form .= "<tr><td>{$name}</td><td>{$id}</td><td {$td_class}>{$attn}</td>";
			
		
		if ($attn == 'loa' or $attn == 'excused' or $attn == 'term' or $attn == 'med leave') {
			$form .= "<td>{$attn}</td>";
		}
		else if ($attn == '') {
			$form .= '<td>No attendance for this date</td>';
		}
		else {
			$form .= "<td><select name='{$id}'><option value='blank'>Select...</option>";
			$form .= '<option value=present>present</option><option value=late>late</option>';
			$form .= '<option value=absent>absent</option></select></p></td>';
			$can_take_attn = 1;
		}
		$form .= "</tr>";
	}	
	$form .= '</table><br>';
	
	if ($can_take_attn) {
		$form .= "<input value='Input attendance' type=submit class=button>";
	}
	$form .= '</form><br><br><br>';
	
	$summary_table = '<table><thead><th>Attendance so far:</th></thead><tbody>';
	$summary_table .= "<tr><td>Present: {$present}</td></tr><tr><td>Late {$late}</td></tr>";
	$summary_table .= "<tr><td>Absent {$absent}</td></tr><tr><td class=show_red>Not taken: {$not_taken}</td></tr></table>";
	
	Database::disconnect();	
	return $form.$summary_table;
}

function get_student_attn_form($target_url) {
	$form = "<form method=post action={$target_url} target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Student Attendance</th></tr></thead>";
	$form .= "<tbody><tr><td>Student ID:</td><td>";
	$form .= "<input required type=text name=student_id></td></tr>";
	$form .= "<tr><td>Session:</td><td><select required name=session>";
	$form .= "<option value=1>1</option><option  value=2>2</option>";
	$form .= "<option value=3>3</option><option  value=4>4</option>";
	$form .= "<option value=5>5</option><option  value=6>6</option>";
	$form .= "<option value=7>7</option><option  value=8>8</option></select></td></tr>";
	$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
	$form .= "</tbody></table><input type=submit class=button value='     Generate Report     '></form>";
	
	return $form;
}

function get_class_attn_form($target_url) {
	$pdo = Database::connect();
	$form = "<form method=post action={$target_url} target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Class Attendance</th></tr></thead><tbody>";
	$form .= "<tr><td>Session:</td><td><select required name=session>";
	$form .= "<option value=1>1</option><option  value=2>2</option>";
	$form .= "<option value=3>3</option><option  value=4>4</option>";
	$form .= "<option value=5>5</option><option  value=6>6</option>";
	$form .= "<option value=7>7</option><option  value=8>8</option></select></td></tr>";
	$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
	$form .= "<tr><td>Course:</td><td><select required name=course>";
	
	$sql_get_all_courses = "select distinct courseassignment.course_id from courseassignment
							left join course on courseassignment.course_id = course.course_id
							order by course_type, course_id";
	
	foreach ($pdo->query($sql_get_all_courses) as $row_all_courses) {
		$course = $row_all_courses['course_id'];
		$course_name = get_course_title($course);
		$section = substr($course, -1,1);
		$form .= "<option value={$course} >{$course_name} - {$section}</option>";
	}
	$form .= "</select></td></tr>";
	
	
	
	$form .= "</tbody></table><input type=submit class=button value='     Generate Report     '></form>";
	
	
	Database::disconnect();
	return $form;
}

function get_attn_by_stu_form($target_url) {
	$students = get_all_stus();
	$form = "<form action={$target_url} method=get target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Attendance By Student</th></tr></thead><tbody>";
	$form .= "<tr><td>Student:</td><td> <input list=student name=student><datalist id=student>";
	$form .= get_stu_datalist($students);
	$form .= "</datalist>";
	$form .= "</tbody></table><input type=submit class=button value='     Next Step     '></form>";
	return $form;
}

function get_attn_by_course_form($target_url) {
	$courses = get_courses();
	$form = "<form action={$target_url} method=post target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Attendance By Course</th></tr></thead><tbody>";
	$form .= "<tr><td>Course:</td>";
	$form .= "<td>";
	$name = "course";
	$form .= get_course_select($name, $courses);
	$form .= "</td></tr>";
	$form .= "<tr><td>Date:</td>";
	$form .= "<td>";
	$form .= get_month_input('month');
	$form .= get_day_input('day');
	$form .=  get_year_input('year');
	$form .= "</td></tr>";
	$form .= "</tbody></table><input type=submit class=button value='     Next Step     '></form>";
	return $form;
}

function get_attn_by_date_form($target, $student_id) {
	$student_name = get_student_name($student_id);
	
	$form = "<Form action={$target} method=post>";
	$form .= "<input type=hidden name=student value={$student_id}>";
	$form .= "<table><thead><tr><th colspan=2>Change Attendance for {$student_name}, ID {$student_id}</th></tr>";
	$form .= "<tr><td align=left>Start Date</td>";
	$form .= "<td>";
	$form .= get_month_input('month_start');
	$form .= get_day_input('day_start');
	$form .=  get_year_input('year_start');
	$form .= "</td></tr>";
	
	$form .= "<tr><td align=left>End Date</td>";
	$form .= "<td>";
	$form .= get_month_input('month_end');
	$form .= get_day_input('day_end');
	$form .=  get_year_input('year_end');
	$form .= "</td></tr>";
	
	$form .= "<tr><td align=left>Option</td>";
	$form .= "<td>";
	$form .= "<input type=radio name=option value=all checked=checked>Full Day<br><br>";
	$form .= "<input type=radio name=option value=course >By Course<br>";
	$form .= "<p style='margin:0px; margin-left:20px'>";
	$form .= get_course_type_checkbox();
	$form .= "</p>";
	$form .= "</td></tr>";
	
	$form .= "<tr><td align=left>Attendance</td>";
	$form .= "<td>";
	$form .= get_attendance_option_admit();
	$form .= "</td></tr>";
	
	
	$form .= "</table><input value='Update Attendance' type=submit class=button></form>";

	return $form;
}

?>