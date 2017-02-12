<?php
include_once("database.php");
include_once("get_name.php");
include_once("course_info.php");
include_once("assignment.php");
include_once("grade_category.php");
// include_once("grades.php");
include_once("forms.php");

function has_grade($student_id, $course, $category, $acad_year, $acad_session) {
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
}

function get_category_table($course, $acad_year, $acad_session, $category, $target_url, $students) {
	$pdo = Database::connect();
	
	$table = "<table><thead><tr align=left><th>Student</th><th>ID</th>";

	$sql_get_assn = "SELECT 	DISTINCT assignment,
								points
					from grade{$category}
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					order by assignment";

	$totalPts = 0;
	$assn_array = []; //reset array

	$results_assn = $pdo->query($sql_get_assn);
	if ($results_assn->rowCount() != 0) {
		foreach ($pdo->query($sql_get_assn) as $row) {
			// table header
			$assn = $row['assignment'];
			$url_assn = urlencode($assn);
			$points = $row['points'];
			$assign = new Assignment;
			$assign->desc = $assn;
			$assign->points = $points;
			$assn_array[] = $assign;
			
			$totalPts += $points;
			$table .= "<th align=left><a href={$target_url}{$course}&assignment={$url_assn}&category={$category} target=_blank class=button>{$assn}</a><br>{$points} pts</th>";
		}
	}
	$table .= "<th align=center>Total<br>{$totalPts} pts</th>";
	$table .= "<th align=right>%</th></tr></thead><tbody>";

	// $sql_get_stus = "select grade{$category}total.student_id as 'sID'
				// from grade{$category}total
				// left join studentinfo on grade{$category}total.student_id = studentinfo.student_id
				// where acad_year = {$acad_year}
				// and acad_session = {$acad_session}
				// and course_id = '{$course}'
				// order by stu_last";
				
				
	foreach ($students as $student_id) {
		$student_name = get_student_name($student_id);
		
		$table .= "<tr><td nowrap><strong>{$student_name}</strong></td><td>{$student_id}</td>";
		
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
										and acad_session = {$acad_session}
										and assignment = '{$desc}'
										and course_id = '{$course}'";
			
			
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
		$grade = get_grade_cat_total($student_id, $course, $category, $acad_year, $acad_session);
		$stu_pts = get_point_cat_stu($student_id, $course, $category, $acad_year, $acad_session);
		$total_pts = get_point_cat_total($student_id, $course, $category, $acad_year, $acad_session);
		$has_a_grade = has_grade($student_id, $course, $category, $acad_year, $acad_session);
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
	}
	$table .= "</table>";
	
	Database::disconnect();
	$pdo = null;
	return $table;
}

function get_category_add_button($target_url, $category, $course) {
	$cat_name = get_course_name_plural($course, $category);
	$link = "<a href={$target_url} class=button target=_blank>Add or Change {$cat_name}</a>";
	
	return $link;
}

function get_overall_grade_table($course, $acad_year, $acad_session, $categories, $students) {
	$pdo = Database::connect();
	
	$table = "<table><thead><tr><th>Student</th><th>ID</th>";
	
	foreach ($categories as $category) {
		$cat_name = get_course_name_plural($course, $category);
		$cat_weight = substr($category, 0,2);
		$table .= "<th>{$cat_name}<br>{$cat_weight}%</th>";
	}
	$table .= "<th>Overall<br>Grade</td></tr></thead><tbody>";
	
	// $sql_get_stus = "select grade40atotal.student_id from grade40atotal
					// left join studentinfo on grade40atotal.student_id = studentinfo.student_id
					// where acad_year = {$acad_year}
					// and acad_session = {$acad_session}
					// and course_id = '{$course}'
					// order by stu_last";
					
	foreach ($students as $student_id) {
		$student_name = get_student_name($student_id);
		
		$table .= "<tr><td><strong>{$student_name}</strong></td><td>{$student_id}</td>";
		
		foreach ($categories as $category) {
			$cat_grade = get_grade_cat_total($student_id, $course, $category, $acad_year, $acad_session);
			$cat_bool = has_grade($student_id, $course, $category, $acad_year, $acad_session);
			// $cat_points = get_point_cat_total($student_id, $course, $category, $acad_year, $acad_session);
			if ($cat_bool) {
				$cat_grade = round($cat_grade,1);
				$data = $cat_grade."%";
				if ($cat_grade < 80.0){
					$class_red = "class=show_red";
				}
				else {
					$class_red = "";
				}
			}
			else {
				$data = 'none';
				$class_red = "";
			}
			$table .= "<td {$class_red}>{$data}</td>";
		}
		$overall_grade = get_session_grade($student_id, $course, $acad_year, $acad_session);
		if ($overall_grade != 'none') {
			$overall_data = round($overall_grade,0)."%";
			if ($overall_grade < 80.0){
					$class_red = "class=show_red";
				}
				else {
					$class_red = "";
				}
		}
		else {
			$overall_data = $overall_grade;
		}
		$table .= "<td {$class_red}>{$overall_data}</td></tr>";
	}
	$table .= "</tbody></table>";
	
	Database::disconnect();
	return $table;
}

function get_grade_input_form($target_url, $assignment, $course, $acad_year, $acad_session, $category, $students) {
	$pdo = Database::connect();
	$assignment_url = urlencode($assignment);
			
	$form = "<Form action={$target_url} method=post>";
	$form .= "<table>";
	$form .= "<thead><tr><th align=center colspan=4>Input Grades for {$assignment}</th></tr></thead>";
	$form .= "<input type=hidden name=course value={$course}>";
	$form .= "<input type=hidden name=category value={$category}>";
	$form .= "<input type=hidden name=session value={$acad_session}>";
	$form .= "<input type=hidden name=year value={$acad_year}>";
	$form .= "<input type=hidden name=assignment value='{$assignment}'>";
	$form .= "<tbody>";
	
	$form .= "<tr><td>Student </td><td>ID</td><td>Grade</td><td>Enter Grade</td></tr>";
	
	foreach ($students as $student) {
		$student_name = get_student_name($student);
		$sql_get_stus = "select percent
				from grade{$category}
				where acad_year = {$acad_year}
				and acad_session = {$acad_session}
				and course_id = '{$course}'
				and assignment = '{$assignment}'
				and student_id = '{$student}'";
		foreach ($pdo->query($sql_get_stus) as $row_stu) {
			$percent = $row_stu['percent'];
		}
		$form .= "<tr><td>{$student_name}</td><td>{$student}</td>";
		$form .= "<td>{$percent}%</td>";
		$form .= "<td><input name='{$student}' type=number step=0.1 size=3 min=0 max=200 value={$percent}>%</td></tr>";
		
		
	}
	$form .= "</table> <input value='Input grades' type=submit class=button></form>";
	
	Database::disconnect();
	return $form;
}

?>