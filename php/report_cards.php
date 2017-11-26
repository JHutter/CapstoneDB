<?php
include_once("database.php");
include_once("get_name.php");
include_once("course_info.php");
include_once("attn.php");
include_once("grade_tables.php");

function get_diagnostic_table($acad_year, $acad_term, $student_id) {
	$class_border = 'class=bordered';
	$pdo = Database::connect();
	$score = '---';
	
	$sql_diagnostic = "select score from diagnostic_scores where student_id = '{$student_id}'";
	foreach ($pdo->query($sql_diagnostic) as $row_score) {
		$score = $row_score['score'];
		$score = $score.'%';
	}
	$table = "<table><tbody><tr><td>Diagnostic Score</td><td colspan=3>{$score}</td></tr>";
	
	Database::disconnect();
	$pdo = null;
	return $table;
}

function has_diagnostic($student_id) {
	$pdo = Database::connect();
	
	$bool = false;
	$sql_diagnostic = "select score from diagnostic_scores where student_id = '{$student_id}'";
	foreach ($pdo->query($sql_diagnostic) as $row_score) {
		$bool = true;
	}
	
	Database::disconnect();
	$pdo = null;
	return $bool;
}

function get_report_card_mid($acad_year, $acad_term, $student_id) {
	$class = "";
	$acad_session = get_session1($acad_year, $acad_term);
	$acad_session2 = get_session2($acad_year, $acad_term);
	$status1 = get_stu_status_by_session_nice($student_id, $acad_session, $acad_year);
	$status2 = get_stu_status_by_session_nice($student_id, $acad_session2, $acad_year);
	// $acad_session = 1;
	$student_name = get_student_name($student_id);
	// $term_dates = 'Dec 01, 2015 - Mar 04, 2016'; // todo add actual dates in here
	$ses1_dates = get_start_date_formatted($acad_year, $acad_session)." - ".get_end_date_formatted($acad_year, $acad_session);
	$ses2_dates = get_start_date_formatted($acad_year, $acad_session2)." - ".get_end_date_formatted($acad_year, $acad_session2);
	$term_dates = get_start_date_formatted($acad_year, $acad_session)." - ".get_end_date_formatted($acad_year, $acad_session+1);
	$entry_type = get_student_entry_name($student_id);
	$entry_term = get_entry_term($student_id);
	$has_diag = has_diagnostic($student_id);
	
	$policies = '<h4>Academic Policy</h4>';
	$policies .= '<ul><li>Student must achieve an overall Term course grade of 80% or better to progress to next level.</li>';
	$policies .= '<li>A student who receives two Academic Warnings per course per Term, will be put on Academic Probation and must repeat the course.</li>';
	$policies .= '<li>Students are asked to exit Capstone and enrollment is terminated if he/she does not meet the <em>academic</em> requirements for the <em>term</em> following academic probation.</li>';
	$policies .= '</ul>';
	
	$policies .= '<h4>Attendance Policy</h4>';
	$policies .= '<ul><li>All F1 students are required to attend 18 hours of class per week per Term.</li>';
	$policies .= '<li>Per USCIS policy, F1 students are required to attend all hours of assigned courses.</li>';
	$policies .= '<li>Students who are absent more than 20% per course per Term will fail attendance requirements and must exit the Capstone program.</li>';
	$policies .= '</ul>';
	
	$html = "<img src='../resources/img/heading_img.jpg' alt='Capstone English Mastery Center'>";
	$html .= "<h2>Student Report Card</h2>";
	$html .= "<table class=info-table><tr class=info-table><td class=info-table>Student Name: {$student_name}</td><td class=info-table>Term #: {$acad_term}</td><tr class=info-table>";
	$html .= "<tr class=info-table><td class=info-table>Student #: {$student_id}</td><td class=info-table>Period of Study: {$term_dates}</td></tr>";
	$html .= "<tr class=info-table><td class=info-table valign=top>Initial Entry: {$entry_type}</td>";
	$html .= "<td class=info-table>Status Session {$acad_session}: {$status1}<br>Status Session {$acad_session2}: {$status2}</td></tr>";
	// $html .= "<tr class=info-table><td class=info-table><td class=info-table></td></tr>";
	$html .= "</tbody></table>";
		
	// session 1 table
	if ($status1 == 'None' and $status2 == 'Active' and $entry_term == $acad_term) {
		$html .= get_diagnostic_table($acad_year, $acad_term, $student_id);
	}
	else {
		//$rw_attn_grade = get_attn_grade($skill, $student_id, $acad_year, $acad_session);
		$class_border = 'class=bordered';
		$html .= "<table class=wide><tr><td colspan=4 align=left class=info-table><br><strong>Mid-Term Grade and Attendance: {$ses1_dates}</strong></td></tr>";
		$html .= "<tr ><td {$class_border}>course</td><td {$class_border}>TEACHER</td><td {$class_border}>MID-TERM<br>GRADE</td><td {$class_border}>attendance %</td></tr>";
		
		$skills = array('10am','11am','mwfpm','tthpm');
			
		foreach ($skills as $skill) {
			$course = get_course_from_skill($skill, $student_id, $acad_year, $acad_session);
			$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
			$course_title = get_course_title($course);
			if ($course != null ) {
				$attn_grade = round(get_attn_grade($course, $student_id, $acad_year, $acad_session), 0);
				$attn_grade_data = $attn_grade."%";
				$grade = round(get_session_grade($student_id, $course, $acad_year, $acad_session), 0);
				$grade_data = $grade."%";
			}
			
			$html .= "<tr><td {$class}>{$course_title}</td><td {$class}>{$teacher}</td><td {$class}>{$grade_data}</td><td {$class}>{$attn_grade_data}</td></tr>";
			$attn_grade = "";
			$attn_grade_data = "";
			$grade = "";
			$grade_data = "";
			$course = null;
		}
		// $html .= "</tbody></table>";
	}
	
	
	$html .= "<tr><td colspan=4 align=left class=info-table><br><strong>Term Grade and Attendance: {$term_dates}</strong></td>";
	$html .= "<tr ><td {$class}>course</td><td {$class}>TEACHER</td><td {$class}>TERM<br>GRADE</td><td {$class}>attendance %</td></tr>";
	$html .= "<tr><td {$class}>Speaking & Listening </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	$html .= "<tr><td {$class}>Reading & Writing </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	$html .= "<tr><td {$class}>Grammar </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	$html .= "<tr><td {$class}>Skills Class </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	$html .= "</table>";
	$html .= $policies;
	
	return $html;
}
	
function get_report_card_form($target_url){
	$class = "";
	$form = "<form method=post action={$target_url} target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Report Card</th></tr></thead>";
	$form .= "<tbody><tr><td>Student ID:</td><td>";
	$form .= "<input required type=text name=student_id></td></tr>";
	$form .= "<tr><td>Term:</td><td><select required name=term>";
	$form .= "<option value=1>1</option><option  value=2>2</option>";
	$form .= "<option value=3>3</option><option  value=4>4</option></td></tr>";
	$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
	$form .= "<tr><td>Mid-Term or<br>Full Term Grade:</td><td><select required name=term_option>";
	$form .= "<option value=mid>Mid-Term Grades</option>";
	$form .= "<option value=full>Full Term Grades</option></td></tr>";
	
	// $form .= "<tr><td>Report style:</td><td><select required name=pdf_option>";
	// $form .= "<option value=browser>See in browswer</option>";
	// $form .= "<option value=pdf>Download pdf</option></td></tr>";
	$form .= "</tbody></table><input type=submit class=button value='     Generate Report     '></form>";
	
	return $form;
}

function get_report_card_form_batch($target_url){
		$class = "";
		$form = "<form method=post action={$target_url} target=_blank>";
		$form .= "<table><thead><tr><th colspan=2>Generate Report Cards as PDF on Server</th></tr></thead>";
		$form .= "<tbody>";
		$form .= "<tr><td>Term:</td><td><select required name=term>";
		$form .= "<option value=1>1</option><option  value=2>2</option>";
		$form .= "<option value=3>3</option><option  value=4>4</option></td></tr>";
		$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
		$form .= "<tr><td>Mid-Term or<br>Full Term Grade:</td><td><select required name=term_option>";
		$form .= "<option value=mid>Mid-Term Grades</option>";
		$form .= "<option value=full>Full Term Grades</option></td></tr>";
		
		// $form .= "<tr><td>Report style:</td><td><select required name=pdf_option>";
		// $form .= "<option value=browser>See in browswer</option>";
		// $form .= "<option value=pdf>Download pdf</option></td></tr>";
		$form .= "</tbody></table><input type=submit class=button value='     Create All Report Cards     '></form>";
	
	return $form;
}

function get_report_card_term($acad_year, $acad_term, $student_id) {
	$class = "";
	$acad_session = get_session1($acad_year, $acad_term);
	$acad_session2 = get_session2($acad_year, $acad_term);
	$status_ses1 = get_stu_status_by_session_nice($student_id, $acad_session, $acad_year);
	$status_ses2 = get_stu_status_by_session_nice($student_id, $acad_session2, $acad_year);
	// $acad_session = 1;
	$student_name = get_student_name($student_id);
	// $term_dates = 'Dec 01, 2015 - Mar 04, 2016'; // todo add actual dates in here
	$ses1_dates = get_start_date_formatted($acad_year, $acad_session)." - ".get_end_date_formatted($acad_year, $acad_session);
	$term_dates = get_start_date_formatted($acad_year, $acad_session)." - ".get_end_date_formatted($acad_year, $acad_session+1);
	$entry_type = get_student_entry_name($student_id);
	$entry_term = get_entry_term($student_id);
	$has_diag = has_diagnostic($student_id);
	
	$policies = '<h4>Academic Policy</h4>';
	$policies .= '<ul><li>Student must achieve an overall Term course grade of 80% or better to progress to next level.</li>';
	$policies .= '<li>A student who receives two Academic Warnings per course per Term, will be put on Academic Probation and must repeat the course.</li>';
	$policies .= '<li>Students are asked to exit Capstone and enrollment is terminated if he/she does not meet the <em>academic</em> requirements for the <em>term</em> following academic probation.</li>';
	$policies .= '</ul>';
	
	$policies .= '<h4>Attendance Policy</h4>';
	$policies .= '<ul><li>All F1 students are required to attend 18 hours of class per week per Term.</li>';
	$policies .= '<li>Per USCIS policy, F1 students are required to attend all hours of assigned courses.</li>';
	$policies .= '<li>Students who are absent more than 20% per course per Term will fail attendance requirements and must exit the Capstone program.</li>';
	$policies .= '</ul>';
	
	$html = "<img src='../resources/img/heading_img.jpg' alt='Capstone English Mastery Center'>";
	$html .= "<h2>Student Report Card</h2>";
	$html .= "<table class=info-table><tr class=info-table><td class=info-table>Student Name: {$student_name}</td><td class=info-table>Term #: {$acad_term}</td><tr class=info-table>";
	$html .= "<tr class=info-table><td class=info-table>Student #: {$student_id}</td><td class=info-table>Period of Study: {$term_dates}</td></tr>";
	$html .= "<tr class=info-table><td class=info-table>Initial Entry: {$entry_type}</td><td class=info-table>Status Session {$acad_session}: {$status_ses1}<br>Status Session {$acad_session2}: {$status_ses2}</td></tr></table>";
		
		// session 1 table
	if ($status_ses1 == 'None' and $status_ses2 == 'Active' and $entry_term == $acad_term) {
		$html .= get_diagnostic_table($acad_year, $acad_term, $student_id);
	}
	else {
		//$rw_attn_grade = get_attn_grade($skill, $student_id, $acad_year, $acad_session);
		$class_border = 'class=bordered';
		$html .= "<table class=wide><tr><td colspan=4 align=left class=info-table><br><strong>Mid-Term Grade and Attendance: {$ses1_dates}</strong></td></tr>";
		$html .= "<tr ><td {$class_border}>course</td><td {$class_border}>TEACHER</td><td {$class_border}>MID-TERM<br>GRADE</td><td {$class_border}>attendance %</td></tr>";
		
		$skills = array('10am','11am','mwfpm','tthpm');
			
		foreach ($skills as $skill) {
			$course = get_course_from_skill($skill, $student_id, $acad_year, $acad_session);
			$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
			$course_title = get_course_title($course);
			if ($course != null ) {
				$attn_grade = round(get_attn_grade($course, $student_id, $acad_year, $acad_session), 0);
				$attn_grade_data = $attn_grade."%";
				$grade = round(get_session_grade($student_id, $course, $acad_year, $acad_session), 0);
				$grade_data = $grade."%";
			}
			
			$html .= "<tr><td {$class}>{$course_title}</td><td {$class}>{$teacher}</td><td {$class}>{$grade_data}</td><td {$class}>{$attn_grade_data}</td></tr>";
			$attn_grade = "";
			$attn_grade_data = "";
			$grade = "";
			$grade_data = "";
			$course = null;
		}
		// $html .= "</tbody></table>";
	}
	
	//ses2 table
	$html .= "<tr><td colspan=4 align=left class=info-table><br><strong>Term Grade and Attendance: {$term_dates}</strong></td>";
	$html .= "<tr ><td {$class}>course</td><td {$class}>TEACHER</td><td {$class}>TERM<br>GRADE</td><td {$class}>attendance %<br>SESSION {$acad_session2}</td></tr>";
	$skills = array('10am','11am','mwfpm','tthpm');
	foreach ($skills as $skill) {
		$course = get_course_from_skill($skill, $student_id, $acad_year, $acad_session2);
		// if ($course == null) {
			// $course = get_course_from_skill($skill, $student_id, $acad_year, $acad_session);
			// $teacher = get_teacher_from_course($course, $acad_year, $acad_session);
			// $course_title = get_course_title($course);
			// $attn_grade_data = "N/A";
		// }
		// else {
			// $teacher = get_teacher_from_course($course, $acad_year, $acad_session2);
			// $course_title = get_course_title($course);
		// }
		
		if ($course != null ) {
			$attn_grade = round(get_attn_grade($course, $student_id, $acad_year, $acad_session2), 0);
			$attn_grade_data = $attn_grade."%";
			$grade = round(get_term_grade($student_id, $skill, $acad_year, $acad_term), 0);
			$grade_data = $grade."%";
			$teacher = get_teacher_from_course($course, $acad_year, $acad_session2);
			$course_title = get_course_title($course);
		}
		else {
			$course = get_course_from_skill($skill, $student_id, $acad_year, $acad_session);
			$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
			$course_title = get_course_title($course);
			$attn_grade_data = "N/A";
			$grade = round(get_term_grade($student_id, $skill, $acad_year, $acad_term), 0);
			$grade_data = $grade."%";
		}
		
		$html .= "<tr><td {$class}>{$course_title}</td><td {$class}>{$teacher}</td><td {$class}>{$grade_data}</td><td {$class}>{$attn_grade_data}</td></tr>";
		$attn_grade = "";
		$attn_grade_data = "";
		$grade = "";
		$grade_data = "";
		$course = null;
	}
	
	
	// $html .= "<tr><td {$class}>Speaking & Listening </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	// $html .= "<tr><td {$class}>Reading & Writing </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	// $html .= "<tr><td {$class}>Grammar </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	// $html .= "<tr><td {$class}>Skills Class </td><td {$class}>---</td><td {$class}>---</td><td {$class}>---</td></tr>";
	$html .= "</table>";
	$html .= $policies;
	
	return $html;
}

?>