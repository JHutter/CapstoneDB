<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/both_sessions.php");
		include_once("../php/assignment.php");
		include_once("../php/course_list.php");
		include_once("../php/course_info.php");
		include_once("../php/get_name.php");
		include_once("../php/grade_tables.php");
		include_once("../php/pdf_button.php");
		include_once("../php/classlist.php");
		// include_once("assignment.php");
		include_once("grade_category.php");
		include_once("forms.php");
		
		function get_category_table_term_view_only_stu($course_type, $acad_year, $acad_term, $category, $student_id) {
				$pdo = Database::connect();
				$acad_session = get_session1($acad_year, $acad_term);
				$acad_session2 = get_session2($acad_year, $acad_term);
				$course = get_course_from_skill($course_type, $student_id, $acad_year, $acad_session);
				// return $course;
				$course2 = get_course_from_skill($course_type, $student_id, $acad_year, $acad_session2);
				// return $acad_session;
				
				$table = "<table class=align_left><thead><tr align=left>";

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
			}
		
		$acad_year = $_GET['year'];
		$acad_term = $_GET['term'];
		$course_type = $_GET['course_type'];
		$course_type_name = get_nice_skills($course_type);
		$student = trim($_GET['student']);
		$student_name = get_student_name($student);
		$date = date('m d, y');
		$categories = ['40a','40b','20'];
		
		$course_is_assigned = course_is_assigned($teacher, $course, $acad_year, $chosen_session);
		$course_name = get_course_title($course);
		$content = "<h3>Grades for {$course_type_name}, Term {$acad_term}, {$acad_year}, as of {$date}</h3>";
		

		$table = "<table class=plain_no_hover><thead><tr><th>Grades for {$student_name} for Term {$acad_term} for {$course_type_name}</th></tr></thead><tbody>";
		foreach ($categories as $category) {
			$cat_name = get_course_name_plural($course, $category);
			$cat_ident = "Grade Category: {$cat_name}";
			$cat_table = get_category_table_term_view_only_stu($course_type, $acad_year, $acad_term, $category, $student) ;

			$cell = $cat_ident."<br><br>".$cat_table;
			$table .= "<tr class=plain_no_hover><td class=plain_no_hover align=left>".$cell."<br><br></td></tr>";
			$count++;
		}
		
		
		
		$overall_ident = "Grade Category: Overall";
		$overall_table = "Term Grade: <b>".get_term_grade($student, $course_type, $acad_year, $acad_term)."%</b>";
		$overall_text = $overall_ident."<br><br>".$overall_table;
		$cell = $overall_text;
		$table .= "<tr class=plain_no_hover><td class=plain_no_hover align=left>".$cell."<br><br></td></tr>";
		$table .= "</tbody></table>";
		$pdf_button = get_pdf_form_button_landscape($table, "../css/pdf_style.css", "{$student}_{$course_type}_grades_term{$acad_term}-{$acad_year}.pdf", "download", "Download Grades for {$student_name}, {$course_type_name}");
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/admit_menu.php");
		?>
	</div>
	
	
	
	<div id="section">
		<H3>Assignments for <?php echo $student_name.' for '.$course_type_name;?>, Term <?php echo $acad_term;?></H3>

		<br><br>


		<div id="change_category">
			<?php 
				// echo implode("<br><br><br>",$categories_text);
				echo $table;
				echo "<br><br><br>";
				// echo $overall_table;
			?>
		</div>
		<?php 
			echo $pdf_button;
		?>

		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    