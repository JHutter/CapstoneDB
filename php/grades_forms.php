<?php
// include_once('database.php');
// include_once("get_name.php");
// include_once("course_info.php");
// include_once("assignment.php");
// include_once("grade_category.php");
// include_once("grade_tables.php");
// include_once("forms.php");


/*function get_category_table_term_view_only_stu($course_type, $acad_year, $acad_term, $category, $student_id) {
	$pdo = Database::connect();
	$acad_session = get_session1($acad_year, $acad_term);
	$acad_session2 = get_session2($acad_year, $acad_term);
	$course = get_course_from_skill($course_type, $student_id, $acad_year, $acad_session);
	// return $course;
	$course2 = get_course_from_skill($course_type, $student_id, $acad_year, $acad_session2);
	// return $acad_session;
	
	$table = "<table style:float=left><thead><tr align=left>";

	$sql_get_assn1 = "SELECT 	DISTINCT assignment,
								points
					from grade{$category}
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					order by acad_session, assignment";
	$sql_get_assn2 = "SELECT 	DISTINCT assignment,
								points
					from grade{$category}
					where acad_year = {$acad_year}
					and acad_session = {$acad_session2}
					and course_id = '{$course2}'
					order by acad_session, assignment";
	// return $sql_get_assn;

	$totalPts = 0;
	$assn_array = []; //reset array

	$results_assn1 = $pdo->query($sql_get_assn1);
	if ($results_assn1->rowCount() != 0) {
		foreach ($pdo->query($sql_get_assn1) as $row1) {
			// table header
			$assn = $row1['assignment'];
			$url_assn = urlencode($assn);
			$points = $row1['points'];
			$assign = new Assignment;
			$assign->desc = $assn;
			$assign->points = $points;
			$assn_array[] = $assign;
			
			$totalPts += $points;
			$table .= "<th align=left>{$assn}<br>{$points} pts</th>";
		}
	}
	$results_assn2 = $pdo->query($sql_get_assn2);
	if ($results_assn2->rowCount() != 0) {
		foreach ($pdo->query($sql_get_assn2) as $row2) {
			// table header
			$assn = $row2['assignment'];
			$url_assn = urlencode($assn);
			$points = $row2['points'];
			$assign = new Assignment;
			$assign->desc = $assn;
			$assign->points = $points;
			$assn_array[] = $assign;
			
			$totalPts += $points;
			$table .= "<th align=left>{$assn}<br>{$points} pts</th>";
		}
	}
	$table .= "<th align=center>Total<br>{$totalPts} pts</th>";
	$table .= "<th align=right>%</th></tr></thead><tbody>";
				
				
	// foreach ($students as $student_id) {
		$student_name = get_student_name($student_id);
		
		$table .= "<tr>";
		
		$stu_pts = get_point_cat_stu($student_id, $course, $category, $acad_year, $acad_session);
		$total_pts = get_point_cat_total($student_id, $course, $category, $acad_year, $acad_session);
		foreach ($assn_array as $assn) {
			$desc = $assn->desc;
			$points = $assn->points;
			
			// now search for matching stu record
			$sql_get_assn_stu_grade = "SELECT percent
										from grade{$category}
										where student_id = '{$student_id}'
										and acad_year = {$acad_year}
										and acad_session in ({$acad_session}, {$acad_session2})
										and assignment = '{$desc}'
										and course_id in ('{$course}', '{$course2}')
										order by acad_session, assignment";
			
			
			// check if 0 rowCount() (if so, mark as --)
			$results = $pdo->query($sql_get_assn_stu_grade);
			if ($results->rowCount() == 0) { // ie, this assignment doesn't exist for this stu, like level change
				$stu_perc = '---';
				$table .= "<td align=right>{$stu_perc}</td>";
			}
			
			// else, grab the result
			else {
				
				foreach ($pdo->query($sql_get_assn_stu_grade) as $row_assn) {
					$stu_perc = $row_assn['percent'];
				}
				
				if ($stu_perc == '') {
					$table .= "<td align=right>none</td>";
					// $stu_pts += 0;
				}
				else {
					$table .= "<td align=right>{$stu_perc}%</td>";
					
					if ($points == 0) {
						// $stu_pts = 0;
					}
					else {
						// $stu_pts += $stu_perc/100.0 * $points;
					}
					// $total_pts += $points;
				}
			}
		}
		// echo total grade in category so far
		// if ($total_pts == 0 and $stu_pts == 0) {
			// $grade = 'none';
		// }
		// else {
			// $grade = (($stu_pts) / ($total_totals)) * 100.0;
		// }
		// $grade = get_grade_cat_total($student_id, $course, $category, $acad_year, $acad_session);
		$stu_pts1 = get_point_cat_stu($student_id, $course, $category, $acad_year, $acad_session);
		$stu_pts2 = get_point_cat_stu($student_id, $course2, $category, $acad_year, $acad_session2);
		$total_pts1 = get_point_cat_total($student_id, $course, $category, $acad_year, $acad_session);
		$total_pts2 = get_point_cat_total($student_id, $course2, $category, $acad_year, $acad_session2);
		
		$stu_pts = $stu_pts1 + $stu_pts2;
		$total_pts = $total_pts1 + $total_pts2;
		$grade = ($stu_pts / $total_pts) * 100;
		
		
		$has_a_grade = (has_grade($student_id, $course, $category, $acad_year, $acad_session) or has_grade($student_id, $course2, $category, $acad_year, $acad_session2));
		$show_red = "";
		if ($has_a_grade and $grade < 80.0) { 
			$show_red = "class=show_red";
		}
		if ($has_a_grade) {
			$grade_data = round($grade,1)."%";
		}
		else {
			$grade_data = 'none';
		}
		$table .= "<td align=right >{$stu_pts}  /  {$total_pts}</td>";
		$table .= "<td align=right {$show_red}>{$grade_data}</td>";
		$table .= "</tr>";
	// }
	$table .= "</table>";
	
	Database::disconnect();
	$pdo = null;
	return $table;
}*/

// function get_student_grade_form($target_url, $students_datalist) {
	// $terms = get_terms_array();
	// $form = "<Form action={$target_url} method=get target=_blank>";
	// $form .= "<table>";
	// $form .= "<thead><tr><th align=center colspan=2>Lookup Grades by Student</th></tr></thead>";
	// $form .= "<tbody>";
	
	// $form .= "<tr><td>Student:</td><td><input list=student name=student><datalist id=student>";
	// $form .= get_stu_datalist($students_datalist);
	// $form .= "</datalist></td></tr>";
	
	// $form .= "<tr><td>Course: </td><td>";
	// $form .= get_course_type_select('course_type');
	// $form .= "</td></tr>";
	
	// $form .= "<tr><td>Term: </td><td>";
	// $form .= get_session_select('term', $terms);
	// $form .= "</td></tr>";
	
	// $form .= "<tr><td>Year: </td><td>";
	// $form .= get_year_input('year');
	// $form .= "</td></tr>";
	
	
	// $form .= "</table><input value='Lookup Grades' type=submit class=button></form>";
	// $form = 'foobar';
	// return $form;
// }


?>