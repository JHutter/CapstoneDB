<?php
// include_once('database.php');
// include_once('course_info.php');
// include_once('get_name.php');
// include_once('write_wrapper.php');

/*function has_grade($student_id, $course, $category, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$sql_get_assns = "select percent
					from grade{$category}
					where student_id = '{$student_id}'
					and course_id = '{$course}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and percent is not null
					and points <> 0";
	
	$result = $pdo->prepare($sql_get_assns); 
	$result->execute(); 
	if ($result->rowCount() == 0) {
		$grade_bool = false;
	}
	else {
		$grade_bool = true;
	}
	
	
	Database::disconnect();
	$pdo = null;
	return $grade_bool;
}


function get_point_cat_total($student_id, $course, $category, $acad_year, $acad_session) {
	$pdo  = Database::connect();
	
	//grab all grades in that category for that course/stu/year/ses
	$sql_get_assns = "select points
					from grade{$category}
					where student_id = '{$student_id}'
					and course_id = '{$course}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and percent is not null
					";
	
	$has_a_grade = has_grade($student_id, $course, $category, $acad_year, $acad_session);
	if ($has_a_grade == false) {
		return 0;
	}
	
	$points = 0;
	foreach ($pdo->query($sql_get_assns) as $row_assign) {
		$points_assn = $row_assign['points'];
		
		$points += $points_assn;
	}
	
	Database::disconnect();
	$pdo = null;
	return $points;
}

function get_point_cat_stu($student_id, $course, $category, $acad_year, $acad_session) {
	$pdo  = Database::connect();
	
	//grab all grades in that category for that course/stu/year/ses
	$sql_get_assns = "select percent, points
					from grade{$category}
					where student_id = '{$student_id}'
					and course_id = '{$course}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and percent is not null";
	
	$has_a_grade = has_grade($student_id, $course, $category, $acad_year, $acad_session);
	if ($has_a_grade == false) {
		return 0;
	}
	
	$points = 0;
	
	
	foreach ($pdo->query($sql_get_assns) as $row_assign) {
		$points_assn = $row_assign['points'];
		$percent_assn = $row_assign['percent'];
		$stu_points = $points_assn * ($percent_assn/100.0);
		
		$points += $stu_points;
	}
	
	Database::disconnect();
	$pdo = null;
	return $points;
}
// /* 	returns 'none' if the grade category doesn't have any entries,
	// returns the grade percent (eg 95 for 95% 87.3 for 87.3%, etc) if there is at least one grade
	// additionally, sets final grade for that category if there is at least one entry 
	// Params: $category should be 40a, 40b, or 20, based on the database design
// 
function get_grade_cat_total($student_id, $course, $category, $acad_year, $acad_session) {
	$points_actual = get_point_cat_stu($student_id, $course, $category, $acad_year, $acad_session);
	$points_total = get_point_cat_total($student_id, $course, $category, $acad_year, $acad_session);
	
	$final_percent = ($points_actual / $points_total) * 100.0;
	return $final_percent;
}

function get_session_grade($student_id, $course, $acad_year, $acad_session) {
	$grade_weight = 0.0;
	$grade40a = get_grade_cat_total($student_id, $course, '40a', $acad_year, $acad_session);
	$grade40a_bool = has_grade($student_id, $course, '40a', $acad_year, $acad_session);
	if ($grade40a_bool) {
		$grade_weight += .40;
	}
	$grade40a *= .40;
	
	$grade40b = get_grade_cat_total($student_id, $course, '40b', $acad_year, $acad_session);
	$grade40b_bool = has_grade($student_id, $course, '40b', $acad_year, $acad_session);
	if ($grade40b_bool) {
		$grade_weight += .40;
	}
	$grade40b *= .40;
	
	$grade20 = get_grade_cat_total($student_id, $course, '20', $acad_year, $acad_session);
	$grade20_bool = has_grade($student_id, $course, '20', $acad_year, $acad_session);
	if ($grade20_bool) {
		$grade_weight += .20;
	}
	$grade20 *= .20;
	
	$grade_total = ($grade40a + $grade40b + $grade20) / $grade_weight;
	return $grade_total;
}

function get_term_grade($student_id, $course_type, $acad_year, $acad_term) {
	$weight = 0.0;
	$session1 = get_session1($acad_year, $acad_term);
	$session2 = get_session2($acad_year, $acad_term);
	$grade = 0.0;
	$has_course1 = has_course_from_skill($course_type, $student_id, $acad_year, $session1);
	$has_course2 = has_course_from_skill($course_type, $student_id, $acad_year, $session2);
	
	// $has_course1 = true;
	// $has_course2 = true;
	$course1 = get_course_from_skill($course_type, $student_id, $acad_year, $session1);
	$course2 = get_course_from_skill($course_type, $student_id, $acad_year, $session2);
	// $course1 = 'RW2B-1';
	// $course2 = 'RW3A-1';
	
	if ($has_course1 and !$has_course2) {
		$grade = get_session_grade($student_id, $course1, $acad_year, $session1);
		return round($grade,0);
	}
	else if (!$has_course1 and $has_course2) {
		$grade = get_session_grade($student_id, $course2, $acad_year, $session2);
		return round($grade,0);
	}
	else if (!$has_course1 and !$has_course2) {
		return null;
	}
	
	$category = '40a';
	$has_40a = (has_grade($student_id, $course1, $category, $acad_year, $session1) 
		or has_grade($student_id, $course2, $category, $acad_year, $session2));
	$grade40a = 0.0;
	if ($has_40a) {
		$weight += .40;
		$points_stu = get_point_cat_stu($student_id, $course1, $category, $acad_year, $session1);
		$points_stu += get_point_cat_stu($student_id, $course2, $category, $acad_year, $session2);
		$points_stu += 0.0;
		
		$points_tot = get_point_cat_total($student_id, $course1, $category, $acad_year, $session1);
		$points_tot += get_point_cat_total($student_id, $course2, $category, $acad_year, $session2);
		$points_tot += 0.0;
		
		$grade40a = $points_stu / $points_tot;
	}
	$grade40a *= 0.40;
	
	$category = '40b';
	$has_40b = (has_grade($student_id, $course1, $category, $acad_year, $session1) 
		or has_grade($student_id, $course2, $category, $acad_year, $session2));
	$grade40b = 0.0;
	if ($has_40b) {
		$weight += .40;
		$points_stu = get_point_cat_stu($student_id, $course1, $category, $acad_year, $session1);
		$points_stu += get_point_cat_stu($student_id, $course2, $category, $acad_year, $session2);
		$points_stu += 0.0;
		
		$points_tot = get_point_cat_total($student_id, $course1, $category, $acad_year, $session1);
		$points_tot += get_point_cat_total($student_id, $course2, $category, $acad_year, $session2);
		$points_tot += 0.0;
		
		$grade40b = $points_stu / $points_tot;
		// return $weight;
	}
	$grade40b *= 0.40;
	
	$category = '20';
	$has_1 = has_grade($student_id, $course1, $category, $acad_year, $session1);
	$has_2 = has_grade($student_id, $course2, $category, $acad_year, $session2);
	
	$has_20 = ($has_1 OR $has_2);
	$grade20 = 0.0;
	if ($has_1 or $has_2) {
		$weight += .20;
		$points_stu = get_point_cat_stu($student_id, $course1, $category, $acad_year, $session1);
		$points_stu += get_point_cat_stu($student_id, $course2, $category, $acad_year, $session2);
		$points_stu += 0.0;
		
		$points_tot = get_point_cat_total($student_id, $course1, $category, $acad_year, $session1);
		$points_tot += get_point_cat_total($student_id, $course2, $category, $acad_year, $session2);
		$points_tot += 0.0;
		
		$grade20 = $points_stu / $points_tot;
	}
	$grade20 *= 0.20;	
	
	$grade = ($grade40a + $grade40b + $grade20) / $weight;
	return round($grade*100,0);

}

function update_session_grades_stu_course_cat($student, $course, $category, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$has_grade = has_grade($student, $course, $category, $acad_year, $acad_session);
	$path = "../logs/grades/";
	$filename = $path."{$student}_session{$acad_session}_{$acad_year}.sql";
	$new_line = ";\n\n";
	// $course_type = get_type_from_course($course);
	
	if (!$has_grade) {
		$points_total = 0;
		$sql_update = "update grade{$category}total
						set points = 0
						where student_id = '{$student}'
						and course_id = '{$course}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}";
						

	}
	else {
		$points_total = get_point_cat_total($student, $course, $category, $acad_year, $acad_session);
		$points_stu = get_point_cat_stu($student, $course, $category, $acad_year, $acad_session);
		
		$sql_update = "update grade{$category}total
						set points = {$points_total}, points_stu = {$points_stu}
						where student_id = '{$student}'
						and course_id = '{$course}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}";
	}
	
	$pdo->exec($sql_update);
	$content = $sql_update.$new_line;
	write_to_file($filename, 'a', $content);
	
	$message = $content;
	
	Database::disconnect();
	$pdo = null;
	return $message;
} 

function update_session_grades_stu_course($student, $course, $acad_year, $acad_session) {
	$categories = get_categories();
	$message = "";
	
	foreach ($categories as $category) {
		$message .= update_session_grades_stu_course_cat($student, $course, $category, $acad_year, $acad_session);
	}
	
	return $message;
}

function update_session_grades_stu_course_total($student, $course, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$message = update_session_grades_stu_course($student, $course, $acad_year, $acad_session);
	$course_type = get_type_from_course($course);
	$path = "../logs/grades/";
	$filename = $path."{$student}_session{$acad_session}_{$acad_year}.sql";
	$new_line = ";\n\n";
	
	$session_grade = get_session_grade($student, $course, $acad_year, $acad_session);
	
	$sql_update_session = "update sessiongrade
							set grade = {$session_grade}
							where student_id = '{$student}'
							and course_type = '{$course_type}'
							and acad_year = {$acad_year}
							and acad_session = {$acad_session}";
	
	$pdo->exec($sql_update_session);
	$content = $sql_update_session.$new_line;
	write_to_file($filename, 'a', $content);
	$message .= $content;
	
	Database::disconnect();
	$pdo = null;
	return $message;
}

function update_session_grades_all_stus($students, $acad_year, $acad_session) {
	$message = "";
	foreach ($students as $student) {
		$courses = get_courses_array($student, $acad_year, $acad_session);
		
		foreach ($courses as $course) {
			$message .= update_session_grades_stu_course_total($student, $course, $acad_year, $acad_session);
		}
	}
	
	return $message;
}

function update_term_grade_stu_course_type($student, $course_type, $acad_year, $acad_term) {
	$pdo = Database::connect();
	$path = "../logs/grades/";
	$filename = $path."{$student}_term{$acad_term}_{$acad_year}.sql";
	$new_line = ";\n\n";
	
	$term_grade = get_term_grade($student, $course_type, $acad_year, $acad_term);
	
	$sql_update_term = "update termgrade
						set grade = {$term_grade}
						where student_id = '{$student}'
						and course_type = '{$course_type}'
						and acad_year = {$acad_year}
						and acad_term = {$acad_term}";
						
	$pdo->exec($sql_update_term);
	$content = $sql_update_term.$new_line;
	$message = $content;
	
	write_to_file($filename, 'a', $content);
	
	Database::disconnect();
	$pdo = null;
	return $message;
}

function update_term_grades_all_stus($students, $acad_year, $acad_term) {
	$message = "";
	$course_types = get_skills();
	foreach ($students as $student) {
		foreach ($course_types as $course_type) {
			$message .= update_term_grade_stu_course_type($student, $course_type, $acad_year, $acad_term);
		}
	}
	return $message;
}

// function has_session_grade($student, $course_type, $acad_year, $acad_session) {
	// $pdo = Database::connect();
	
	// $sql_grade = "select grade from sessiongrade
				// where student_id = '{$student}'
				// and acad_year = {$acad_year}
				// and acad_session = {$acad_session}
				// and course_type = '{$course_type}'";
	// $result = $pdo->prepare($sql_grade); 
	// $result->execute(); 
	// if ($result->rowCount() == 0) {
		// $has_grade = false;
	// }
	// foreach ($pdo->query($sql_grade) as $row_grade) {
		// $grade = $row_grade['grade'];
		// if ($grade == null) {
			// $has_grade = false;
		// }
		// else {
			// $has_grade = true;
		// }
	// }
	
	// Database::disconnect();
	// $pdo = null;
	// return $has_grade;
	
// }

function get_quick_category_grade($student, $course, $category, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$grade = null;
	
	$sql_grade = "select points, points_stu from grade{$category}total
				where student_id = '{$student}'
				and acad_year = {$acad_year}
				and acad_session = {$acad_session}
				and course_id = '{$course}'";
	
	foreach ($pdo->query($sql_grade) as $row_grade) {
		$points_tot = $row_grade['points'];
		$points_stu = $row_grade['points_stu'];
		
		if ($points_tot == 0) {
			return null;
		}
		else if ($points_stu == 0) {
			return $points_stu;
		}
		else {
			$grade = round((($points_stu *1.0)/ ($points_tot*1.0)* 100),0);
		}
	}

	Database::disconnect();
	$pdo = null;
	return $grade;
}

function get_quick_session_grade($student, $course_type, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$grade = null;
	// $has_grade = has_session_grade($student, $course_type, $acad_year, $acad_session);
	
	$sql_grade = "select grade from sessiongrade
				where student_id = '{$student}'
				and acad_year = {$acad_year}
				and acad_session = {$acad_session}
				and course_type = '{$course_type}'";
	foreach ($pdo->query($sql_grade) as $row_grade) {
		$grade = $row_grade['grade'];
		$grade = round($grade,0);
	}
	
	Database::disconnect();
	$pdo = null;
	return $grade;
}

function get_quick_term_grade($student, $course_type, $acad_year, $acad_term) {
	$pdo = Database::connect();
	$grade = null;
	
	$sql_grade = "select grade from termgrade
				where student_id = '{$student}'
				and acad_year = {$acad_year}
				and acad_term = {$acad_term}
				and course_type = '{$course_type}'";
	foreach ($pdo->query($sql_grade) as $row_grade) {
		$grade = $row_grade['grade'];
	}
	
	if ($grade != null) {
		$grade = round($grade,0);
	}
	
	Database::disconnect();
	$pdo = null;
	return $grade;
}

function get_diagnostic($student) {
	$pdo = Database::connect();
	
	$score = 'none';
	
	$sql_diag = "select score from diagnostic_scores
				where student_id = '{$student}'";
				
	foreach ($pdo->query($sql_diag) as $row_diag) {
		$score = $row_diag['score'];
	}
	
	return $score;
}*/


?>