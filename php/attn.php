<?php
// this header file contains php functions related to attendance
// the functions are divided by number (course or student)
// and also purpose (see attendance, attendance report)
// tables are returned unstyled, except for id or class of element

include_once("database.php");
include_once("course_info.php");
$attn_message = "<p>'Other' refers to loa, excused, med leave, or term. ";
$attn_message .= "'Remaining' refers to class dates remaining in the ";
$attn_message .= "term (i.e., not marked in attendance). In cases where ";
$attn_message .= "attendance is missing (e.g., a student starts at the ";
$attn_message .= "school in the second week), their 'remaining' number ";
$attn_message .= "will differ. Loa/excused/term/med leave is not counted ";
$attn_message .= "in remaining days. Attendance grade (% column) is ";
$attn_message .= "rounded to the nearest whole number. ";
$attn_message .= "Lateness: Each late arrival after the third time is ";
$attn_message .= "marked as absent. The first and second late arrival ";
$attn_message .= "for that student in that class is marked as present.</p>";

/*
Name: get_class_attn_table
@param $course, $acad_year, $acad_session
@return sting $table with attendance for class, unstyled
table class: attn_table
*/
function get_class_attn_table($course, $acad_year, $acad_session) {
	// connect to db
	$pdo = Database::connect();
	$course_type = get_type_from_course($course);
	
	// sql queries, th dates and students
	$sql_get_dates = "SELECT 	DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
								class_date AS 'date',
								DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
						from session_dates
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_type = '{$course_type}'
						order by date";
						
	$sql_get_stus = "SELECT courseenrollment.student_id AS 'sID',
							CONCAT(stu_first, ' ', stu_last) AS 'SName'
					FROM courseenrollment
					left join studentinfo on courseenrollment.student_id = studentinfo.student_id
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					order by stu_last";
	
	// start the table
	$class_attn = "class=attn-table";
	$table = "<table {$class_attn}>";
	$table .= "<thead><tr><th>Student Name</th><th>ID</th>";
	
	// get the th for each date
	foreach ($pdo->query($sql_get_dates) as $row_date_th) {
		$date = $row_date_th['class_date'];
		$table .= "<th>{$date}</th>";
	}
	$table .= "</tr></thead><tbody>";
	
	// get the list of stus and iterate
	foreach ($pdo->query($sql_get_stus) as $row_stus) {
		$name =$row_stus['SName'];
		$id = $row_stus['sID'];
		
		// start the row with name and ID
		$class_bold = "class=bold";
		$table .= "<tr><td class=bold>{$name}</td><td>{$id}</td>";
		
		// get the attn, first get student attendance dates to use
		foreach ($pdo->query($sql_get_dates) as $row_dates_attn){
			$date = $row_dates_attn['tableDate'];
			$sql_get_attn = "SELECT attendance_yn
							from attendance 
							where acad_year = {$acad_year} 
							and acad_session = {$acad_session}
							and student_id = '{$id}'
							and course_id = '{$course}'
							and class_date = {$date}";
				
			// now get the student attendance
			$result = $pdo->prepare($sql_get_attn); 
			$result->execute(); 
			if ($result->rowCount() == 0) {
				$table .= "<td>-</td>";
			}
			else {
				foreach ($pdo->query($sql_get_attn) as $row_attn){
					$attn = $row_attn['attendance_yn'];
					if ($attn == 'none') {
						$attn = '-';}
					}
					
					if ($attn == 'absent' or $attn == 'late') {
						$class = "class=show_red";
					}
					else {
						$class = "";
					}
					$table .= "<td {$class}>{$attn}</td>"; 
				}
			}
		// end the row
		$table .= "</tr>";
	}
	
	// end the table
	$table .= "</tbody></table>";
	
	
	
	Database::disconnect();
	return $table;
}

/*
Name: get_class_attn_grades
@param $course, $acad_year, $acad_session
@return sting $table with attendance grade for class, unstyled
table class: attn_grades
*/
function get_class_attn_grades($course, $acad_year, $acad_session) {
	// connect to db
	$pdo = Database::connect();
	
	$sql_get_stus = "SELECT courseenrollment.student_id AS 'sID',
							CONCAT(stu_first, ' ', stu_last) AS 'SName'
					FROM courseenrollment
					left join studentinfo on courseenrollment.student_id = studentinfo.student_id
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					order by stu_last";
	
	// set up table
	$class_attn_grades = "class=attn_grades";
	$table = "<table {$class_attn_grades}>";
	$table .= "<tr><th>Student Name</th><th>ID</th><th>Present</th><th>Late</th>";
	$text_align_right = "align=right";
	$table .= "<th>Absent</th><th>Other</th><th>Remaining</th><th {$text_align_right}>%</th></tr>";
	
	foreach ($pdo->query($sql_get_stus) as $row_stus){
		$name =$row_stus['SName'];
		$id = $row_stus['sID'];
		
		// name/id columns
		$class_bold = "class=bold";
		$table .= "<tr><td {$class_bold}>{$name}</td><td>{$id}</td>";
		
		// get attendance to tabulate grades
		$sql_get_attn = "SELECT attendance_yn
							from attendance 
							where acad_year = {$acad_year} 
							and acad_session = {$acad_session}
							and student_id = '{$id}'
							and course_id = '{$course}'
							order by class_date";
		
		// restart the vars to tabulate attendance
		$class_date_count = 0; // demoninator for attendance grade
		$present = 0;  // literally present
		$count_as_here = 0; // it counts for a grade
		$late = 0;
		$absent = 0;
		$other = 0; // excused/loa/med leave/term
		$remain = 0; // remaining
	
		foreach ($pdo->query($sql_get_attn) as $row_attn){
			$attn = $row_attn['attendance_yn'];
			// check each attendance condition
			if ($attn == 'present') { 
				$class_date_count++; 
				$present++; 
				$count_as_here++;
			}
			else if ($attn == 'late') {
				$class_date_count++;
				$late++;
				if ($late < 3) {
					$count_as_here++;
				}
			}
			else if ($attn == 'absent') {
				$class_date_count++;
				$absent++;
			}
			else if ($attn == 'none') {
				$remain++;
			}
			else {
				$other++;
			}
		}
		
		// calculate each student's grade, convert to float, round to the nearest tenth
		if ($class_date_count != 0) {
			$grade = round(((0.0 + $count_as_here) / (0.0 + $class_date_count) * 100)		, 0);
		}
		else {
			$grade = 0;
		}
		
		// student is failing attendance
		if ($grade < 80.0) {
			$class_red = "class=show_red";
		}
		else {
			$class_red = "";
		}
		$table .= "<td>{$present}</td><td>{$late}</td><td>{$absent}</td><td>{$other}</td><td>{$remain}</td>";
		$table .= "<td {$class_red}>{$grade}%</td></tr>";
	}
	
	// end table
	$table .= "</table>";
	
	Database::disconnect();
	return $table;
}

/*
Name: get_student_attn_table
@param $student, $acad_year, $acad_session
@return sting $table with attendance for student all classes, unstyled
table class: attn_table
*/
function get_student_attn_table($student_id, $acad_year, $acad_session) {
	$pdo = Database::connect();
	
	$table = "<table><thead><tr><th>Course</th>";
	$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
						class_date AS 'date',
						DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
						from attendance
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id like 'SL%'
						order by date";
	
	foreach ($pdo->query($sql_get_dates) as $row_dates){
		$date = $row_dates['class_date'];
		$table.= "<th>{$date}</th>";
	}
	$table .= "</tr></thead><tbody>";

	
	$sql_get_courses = "select courseenrollment.course_id from courseenrollment
						left join course on courseenrollment.course_id = course.course_id
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and student_id = '{$student_id}'
						order by course_type";
	foreach ($pdo->query($sql_get_courses) as $row_courses) {
		$course = $row_courses['course_id'];
		$table .= "<tr>";
		$table .= "<td>{$course}</td>";
		
		$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
								class_date AS 'date',
								DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
									from attendance
									where acad_year = {$acad_year}
									and acad_session = {$acad_session}
									and course_id like 'SL%'
									order by date";
									
		foreach ($pdo->query($sql_get_dates) as $row_stu_dates) {
			$date = $row_stu_dates['tableDate'];
			$sql_get_attn = "select attendance_yn
						from attendance
						where student_id = '{$student_id}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						and class_date = {$date}";
						
			$results = $pdo->query($sql_get_attn);
			$class_red = "";
			if ($results->rowCount() == 0) {
				$attn = '---';
			}
			else {
				foreach ($pdo->query($sql_get_attn) as $row_attn) {
					$attn = $row_attn['attendance_yn'];
				}
			}
			$class_red = "";
			if ($attn == 'late' or $attn == 'absent') {
				$class_red = "class=show_red";
			}
			$table .= "<td {$class_red}>{$attn}</td>";
		}
		$table .= "</tr>";
	}
	$table .= "</tbody></table>";
	Database::disconnect();
	return $table;
}

/*
Name: get_student_attn_grades
@param $student, $acad_year, $acad_session
@return sting $table with attendance grade for student all classes, unstyled
table class: attn_grades
*/
function get_student_attn_grades() {
	$pdo = Database::connect();
	
	
	Database::disconnect();
	return $table;
}

function take_attn_link($course, $url) {
	return "<a href={$url}{$course} class=button>    Take attendance    </a><br><br>";
}

function get_attn_grade($course, $student_id, $acad_year, $acad_session) {
		$pdo = Database::connect();
		
		$sql_get_attn= "select attendance_yn from attendance
						where student_id = '{$student_id}'
						and course_id = '{$course}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}";
						
		$numerator = 0;
		$denominator = 0;
		$late_count = 0;
						
		foreach ($pdo->query($sql_get_attn) as $row_attn) {
			$attn = $row_attn['attendance_yn'];
			
			// options are present, late, absent, excused/loa/term/med leave
			switch ($attn) {
				case 'present':
					$numerator++;
					$denominator++;
					break;
				case 'late':
					$denominator++;
					if ($late_count < 3) {
						$numerator++;
					}
					break;
				case 'absent':
					$denominator++;
					break;
				default:
					break;
			}
		}
		if ($denominator == 0) {
			return 0.0;
		}
		$attn_grade = round(( $numerator + 0.0) / ($denominator + 0.0) * 1000, 0) / 10;
		
		Database::disconnect();
		return $attn_grade;
	}
	
function get_attn_count_by_attn($student_id, $course, $acad_year, $acad_session, $attendance) {
	$pdo = Database::connect();
	
	$sql_attn = "select attendance_yn from attendance
				where course_id = '{$course}'
				and student_id = '{$student_id}'
				and acad_year = {$acad_year}
				and acad_session = {$acad_session}
				and attendance_yn = '{$attendance}'";
	
	$count = 0;
	
	$result = $pdo->prepare($sql_attn); 
	$result->execute(); 
	$count = $result->rowCount();
	
	Database::disconnect();
	$pdo = null;
	return $count;
}

?>