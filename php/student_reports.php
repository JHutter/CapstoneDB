<?php
include_once("database.php");
include_once("get_name.php");
include_once("course_info.php");
include_once("grade_tables.php");
include_once("classlist.php");
include_once("attn.php");

class Course{
	public $course_id;
	public $room = '---';
	public $teacher = '---';
}

function get_all_student_form($target_url){
	$form = "<form method=post action={$target_url} target=_blank>";
	$form .= "<table><thead><tr><th colspan=2>Report: All Students</th></tr></thead>";
	$form .= "<tbody><tr><td>Student Status:</td><td>";
	$form .= "<input type=checkbox name=active value=active checked=checked>Active<br>";
	$form .= "<input type=checkbox name=loa value=loa>LOA<br>";
	$form .= "<input type=checkbox name=med value='med leave'>Med Leave<br>";
	$form .= "<input type=checkbox name=term value=term>Terminated<br></td></tr>";
	$form .= "<tr><td>Session:</td><td><select required name=session>";
	$form .= "<option value=1>1</option><option  value=2>2</option>";
	$form .= "<option value=3>3</option><option  value=4>4</option>";
	$form .= "<option value=5>5</option><option  value=6>6</option>";
	$form .= "<option value=7>7</option><option  value=8>8</option></select></td></tr>";
	$form .= "<tr><td>Year:</td><td><select required name=year><option value=2016>2016</option><option value=2017>2017</option></select></td></tr>";
	$form .= "<tr><td>First sort:</td><td><input type=radio name=sort value=student_id checked=checked>Student ID<br>";
	$form .= "<input type=radio name=sort value=stu_last>Last Name<br><input type=radio name=sort value=stu_first>First Name<br>";
	$form .= "<input type=radio name=sort value=enroll_status>Enrollment Status<br></td></tr>";
	$form .= "<tr><td>Second Sort:</td><td><input type=radio name=sort2 value=student_id checked=checked>Student ID<br>";
	$form .= "<input type=radio name=sort2 value=stu_last>Last Name<br><input type=radio name=sort2 value=stu_first>First Name<br>";
	$form .= "<input type=radio name=sort2 value=enroll_status >Enrollment Status<br></td></tr></tbody>";
	$form .= "</table><input type=submit class=button value='     Generate Report     '></form>";
	
	return $form;
}

function get_student_report($acad_year, $acad_session, $sort, $sort2, $active, $loa, $med, $term){
	$pdo = Database::connect();
	
	$status_list = "'{$active}', '{$loa}', '{$med}', '{$term}'";
	
	$sql_get_stus = "select enroll_status.student_id, enroll_status
					from enroll_status join studentinfo on enroll_status.student_id = studentinfo.student_id
					where acad_session = {$acad_session}
					and acad_year = {$acad_year}
					and enroll_status in ({$status_list})
					order by {$sort}, {$sort2}";
					
	$table = "<table><thead><tr><th colspan=4>Student List</th></tr></thead>";
	$table .= "<tbody><tr><td class=bold>Student Name</td><td class=bold>ID</td><td class=bold>Email</td><td class=bold>Enrollment Status</td></tr>";
	$stu_count = 0;
	foreach ($pdo->query($sql_get_stus) as $row_stus) {
		$stu_count++;
		$student_id = $row_stus['student_id'];
		$student_name = get_student_name($student_id);
		$email = get_student_email($student_id);
		$enroll_status = $row_stus['enroll_status'];
		
		$table .= "<tr><td>{$student_name}</td><td>{$student_id}</td><td>{$email}</td><td>{$enroll_status}</td></tr>";
	}
	
	$table .= "<tr></tr><tr><td class=bold colspan=4>Count: {$stu_count}</td></tr></tbody></table>";
	
	Database::disconnect();
	$pdo = null;
	return $table;
}

function get_attn_report_form($target_url) {
	$form = "<Form action={$target_url} method=POST target=_blank><table><thead><tr><th colspan=2>Attendance Report</th></thead><tbody>";
	$form .= "<tr><td>Attendance Benchmark</td><td><input required type=number name=benchmark size=70 step=1 min=0 max=100>%</td></tr>";
	$form .= "<tr><td>Year/Session</td><td><select required name=year>";
	$form .= "<option value=2016>2016</option><option value=2017>2017</option><option value=2018>2018</option></select>";
	$form .= "<select required name=session><option value=1>1</option><option value=2>2</option>";
	$form .= "<option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option>";
	$form .= "<option value=7>7</option><option value=8>8</option></select></td></tr></table>";
	$form .= "<input value='Generate attendance report' type=submit class=button></input></form><br><br>";
	
	$instructions = "<p>Instructions: Enter the attendance percentage to search for in the ";
	$instructions .= "attendance benchmark box. This number is the absence rate. For example, ";
	$instructions .= "to find all students with greater than 20% absence in the session so far, ";
	$instructions .= "enter 20 in the box (in other words, if you enter 20, 80% attendance is ok, ";
	$instructions .= "and the report will identify 79% attendance and below.)";

	return $form.$instructions;
}

function get_attn_report($benchmark, $acad_year, $acad_session, $target_url) {
	$final_table = "";
	$rev_benchmark = 100 - $benchmark;
	$pdo = Database::connect();
	
	$instructions = "<p>Showing all students with absence over {$benchmark}% in any course. ";
	$instructions .= "(Percent present {$rev_benchmark}% or below.) ";
	$instructions .= "Note: Any attendance grade below {$rev_benchmark}% is shown in red.</p>";
	
	$sql_get_stus = "select concat(stu_first, ' ', stu_last) as 'SName',
				studentinfo.student_id as 'id',
				stu_email1 as 'email'
				from studentinfo
				left join studentcontact on studentinfo.student_id = studentcontact.student_id
				order by stu_last
				";
	$stu_count = 0;
	foreach($pdo->query($sql_get_stus) as $row) {
		$name = $row['SName'];
		$id = $row['id'];
		$email = $row['email'];
		$above_benchmark = 0;
	
		$table = "<table><thead><tr><th colspan=8><a href={$target_url}{$id} target=_blank class=button>{$name}</a></th></tr>";
		$table .= "<tr><td class=bold colspan=4>ID:{$id}</th><td class=bold colspan=4>Email:{$email}</th></tr></thead><tbody>";
		$table .= "<tr ><td></td><td>Present #</td><td>Late #</td><td>Absent #</td><td>Other #</td>";
		$table .= "<td>Remaining #</td><td>Percent Present</td><td>Hours Missed</td></tr>";
		
		$sql_get_courses = "select courseenrollment.course_id as 'course_id'
						from courseenrollment
						left join course on courseenrollment.course_id = course.course_id
						left join course_type on course.course_type = course_type.course_type
						where student_id = '{$id}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						order by course_order asc";

		foreach ($pdo->query($sql_get_courses) as $row_course) {
			$course = $row_course['course_id'];
			$table .= "<tr><td>{$course}</td>";
			
			$sql_get_attn = "select attendance_yn
						from attendance
						where student_id = '{$id}'
						and course_id = '{$course}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}";
			$count = 0;
			$present = 0;  // literally present
			$late = 0;
			$absent = 0;
			$other = 0;
			$here = 0; // it counts for a grade
			$remain = 0;
			$not_here = 0;
			$class_total = 0;
			$any_attn = 0;
			
			foreach ($pdo->query($sql_get_attn) as $row_attn) {
				$attn = $row_attn['attendance_yn'];
				$class_total++;
				// check each attendance condition
				if ($attn == 'present') { 
					$count++; 
					$present++; 
					$here++;
					$any_attn = 1;
				}
				else if ($attn == 'late') {
					$count++;
					$late++;
					$any_attn;
					if ($late < 3) {
						$here++;
					}
					else {
						$not_here++;
					}
				}
				else if ($attn == 'absent') {
					$count++;
					$absent++;
					$not_here++;
					$any_attn = 1;
				}
				else if ($attn == 'none') {
					$remain++;
				}
				else {
					$other++;
				}
			}
			if ($count != 0) {
				$grade = round(((0.0 + $here) / (0.0 + $count) * 100), 0);
			}
			else {
				$grade = 0.0;
			}
			
			$show_red = "";
			$table .= "<td>{$present}</td><td>{$late}</td><td>{$absent}</td><td>{$other}</td><td>{$remain}</td>";
			if ($grade < $rev_benchmark + 0.0) {
				$above_benchmark = 1;
				$show_red = "class=show_red";
			}
			$table .= "<td {$show_red}>{$grade}%</td>";
			
			$type = substr($course, 0,1);
			if ($type == "S" or $type == "R") { $class_hour = 1.0;}
			else if ($type == "G") { $class_hour = 1.6667;}
			else { $class_hour = 1.5;}
			
			$hours_missed = round($not_here * $class_hour, 2);
			$hours_total = round($class_total * $class_hour, 2);
			$table .= "<td align=right>{$hours_missed} of {$hours_total}</td></tr>";
	}
	
	$table .= "</table><br><br>";
	if ($above_benchmark and $any_attn) {
		$final_table .= $table;
		$stu_count++;
	}
	
	}

	$count_str = "<p><strong>Attendance warning count: {$stu_count}</strong></p>";
	
	Database::disconnect();
	$pdo = null;
	return $instructions.$count_str.$final_table;
}

function get_attendance_page_student($student_id, $acad_year, $acad_session) {
	$pdo = Database::connect();
	$student_name = get_student_name($student_id);
	
	$page = "Name: {$student_name}, ID {$student_id}<br><br><br>";
	$page .= "<table><thead><tr><th align=left>Course</th><th align=left>Teacher</th><th align=left>Room</th></tr></thead><tbody>";

	$courses = get_courses_array($student_id, $acad_year, $acad_session);
	// $sql_sched = "select	distinct COURSEENROLLMENT.course_id AS 'course_id'
				// from COURSEENROLLMENT
				// left join COURSE on COURSEENROLLMENT.course_id = COURSE.course_id
				// left join COURSE_TYPE on COURSE.course_type = COURSE_TYPE.course_type
				// WHERE 	COURSEENROLLMENT.acad_year = {$acad_year}
				// AND 	COURSEENROLLMENT.acad_session = {$acad_session}
				// AND 	COURSEENROLLMENT.student_id = '{$student_id}'
				// ORDER BY COURSE.course_type";


	// $course_array = [];
	foreach ($courses as $course){
		$room = get_room_from_course($course, $acad_year, $acad_session);
		$teacher = get_teacher_from_course($course, $acad_year, $acad_session);
		$title = get_course_title($course)."({$course})";
		$page .= "<tr><td>{$title}</td><td>{$teacher}</td><td>{$room}</td></tr>";
	}
	$page .= "</table>";

	$absent = 0;
	$late = 0;
	
	$page .= "<br><br><br>";
	$page .= "<table><thead><tr><th>Course</th>";
		
	//get dates from course+course_type+class_dates
	//echo td for each date
	$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
					class_date AS 'date',
					DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
						from ATTENDANCE
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id like 'SL%'
						order by date";
		
	foreach ($pdo->query($sql_get_dates) as $row_dates){
		$date = $row_dates['class_date'];
		$page .= "<th>{$date}</th>";
		}
	$page .= "</tr></thead>";

	$sql_get_courses = "select	courseenrollment.course_id
						from COURSEENROLLMENT left join course on courseenrollment.course_id = course.course_id
						WHERE 	COURSEENROLLMENT.acad_year = {$acad_year}
						AND 	COURSEENROLLMENT.acad_session = {$acad_session}
						AND 	COURSEENROLLMENT.student_id = '{$student_id}'
						order by course.course_type";

	foreach ($pdo->query($sql_get_courses) as $row_courses) {
		$course = $row_courses['course_id'];
		$page .= "<tr>";
		$page .= "<td>{$course}</td>";
		
		$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
								class_date AS 'date',
								DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
									from ATTENDANCE
									where acad_year = {$acad_year}
									and acad_session = {$acad_session}
									and course_id like 'SL%'
									order by date";
									
		foreach ($pdo->query($sql_get_dates) as $row_attn_dates) {
			$show_red = "";
			$date = $row_attn_dates['tableDate'];
			$sql_get_attn = "select attendance_yn
						from attendance
						where student_id = '{$student_id}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						and class_date = {$date}";
			$results = $pdo->query($sql_get_attn);
			
			if ($results->rowCount() == 0) {
				$attn = '---';
			}
			else {
				foreach ($pdo->query($sql_get_attn) as $row_attn) {
					$attn = $row_attn['attendance_yn'];
				}
			}
			if ($attn == 'absent') {
				$show_red = "class=show_red";
				$absent++; 
			}
			else if ($attn == 'late') {
				$show_red = "class=show_red";
				$late++; 
			}
			$page .= "<td {$show_red}>{$attn}</td>";
		}
		$page .= "</tr>";
	}
	$page .= "</table><br><br>Times absent: {$absent}<br>Times late:   {$late}<br>";
	
	Database::disconnect();
	$pdo = null;
	return $page;
	
}

function get_grade_report_form($target_url) {
	$form = "<Form action={$target_url} method=POST target=_blank><table><thead><tr><th colspan=2>Grade Report - By Session</th></thead><tbody>";
	$form .= "<tr><td>Grade Benchmark</td><td><input required type=number name=benchmark size=70 step=1 min=0 max=200>%</td></tr>";
	$form .= "<tr><td>Year/Session</td><td><select required name=year>";
	$form .= "<option value=2016>2016</option><option value=2017>2017</option><option value=2018>2018</option></select>";
	$form .= "<select required name=session><option value=1>1</option><option value=2>2</option>";
	$form .= "<option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option>";
	$form .= "<option value=7>7</option><option value=8>8</option></select></td></tr></table>";
	$form .= "<input value='Generate grade report' type=submit class=button></input></form><br><br>";
	
	$instructions = "<p>Instructions: Enter the grade percentage to search for in the ";
	$instructions .= "grade benchmark box. For example, ";
	$instructions .= "to find all students with grades below 80% in the session so far, ";
	$instructions .= "enter 80 in the box (in other words, if you enter 80, 80% grade is ok, ";
	$instructions .= "and the report will identify 79% grade and below.)";

	return $form;//.$instructions;
}

function get_grade_report_form_term($target_url) {
	$form = "<Form action={$target_url} method=POST target=_blank><table><thead><tr><th colspan=2>Grade Report - By Term</th></thead><tbody>";
	$form .= "<tr><td>Grade Benchmark</td><td><input required type=number name=benchmark size=70 step=1 min=0 max=200>%</td></tr>";
	$form .= "<tr><td>Year/Term</td><td><select required name=year>";
	$form .= "<option value=2016>2016</option><option value=2017>2017</option><option value=2018>2018</option></select>";
	$form .= "<select required name=term><option value=1>1</option><option value=2>2</option>";
	$form .= "<option value=3>3</option><option value=4>4</option></select></td></tr></table>";
	$form .= "<input value='Generate grade report' type=submit class=button></input></form><br><br>";
	
	$instructions = "<p>Instructions: Enter the grade percentage to search for in the ";
	$instructions .= "grade benchmark box. For example, ";
	$instructions .= "to find all students with grades below 80% in the session so far, ";
	$instructions .= "enter 80 in the box (in other words, if you enter 80, 80% grade is ok, ";
	$instructions .= "and the report will identify 79% grade and below.)";

	return $form;//.$instructions;
}

function get_grade_report($benchmark, $acad_year, $acad_session, $target_url) {
	$final_table = "";
	$pdo = Database::connect();
	
	$instructions = "<p>Showing all students with grades below {$benchmark}% in any course. ";
	$instructions .= "Note: Any grade below {$benchmark}% is shown in red.</p>";
	
	$students = get_stu_by_status('active', $acad_session, $acad_year);
	if ($acad_session == 8) {
		$next_year = $acad_year + 1;
		$next_session = 1;
	}
	else {
		$next_year = $acad_year;
		$next_session = $acad_session + 1;
	}

	$stu_count = 0;
	$stu_total = count($students);
	foreach($students as $id) {
		$name = get_student_name($id);
		$email = get_student_email($id);
		$next_status = get_stu_status_by_session($id, $next_session, $next_year);
		$below_benchmark = false;
	
		$table = "<table><thead><tr><th colspan=6>{$name}</th></tr></thead>";
		$table .= "<tr><td class=bold colspan=4>ID:{$id}</td>";
		$table .= "<td class=bold>Next session:</td><td class=bold>{$next_status}</td></tr>";
		$table .= "<tr><td class=bold colspan=6>Email:{$email}</td></tr><tbody>";
		$table .= "<tr><td></td><td></td></tr><tr ><td class=bold>Course</td><td class=bold>Course ID</td>";
		$table .= "<td class=bold>Grade 40%</td><td class=bold>Grade 40%</td><td class=bold>Grade 20%</td><td class=bold>Grade Total</td></tr>";
		
		$courses = get_courses_array($id, $acad_year, $acad_session);
		

		foreach ($courses as $course) {
			$course_name = get_course_title($course);
			$course_type = get_type_from_course($course);
			$table .= "<tr><td>{$course_name}</td><td>{$course}</td>";
			
			$categories = get_categories();
			
			foreach ($categories as $category) {
				$grade = get_quick_category_grade($id, $course, $category, $acad_year, $acad_session);
				if ($grade == null) {
					$grade = 'none';
					$show_red = "";
				}
				else {
					if ($grade < $benchmark) {
						$below_benchmark = true;
						$show_red = "class=show_red";
					}
					else {
						$show_red = "";
					}
					$grade = $grade."%";
				}
				$table .= "<td align=right {$show_red}>{$grade}</td>";
			}
			
			
			$show_red = "";
			$final_grade = get_quick_session_grade($id, $course_type, $acad_year, $acad_session);
			if ($final_grade == null) {
				$final_grade = 'none';
				$show_red = "";
			}
			else {
				$final_grade = round($final_grade,0);
				if ($final_grade < ($benchmark)) {
					$show_red = "class=show_red";
					$below_benchmark = true;
				}
				else {
					$show_red = "";
				}
				$final_grade = $final_grade."%";
			}
			$table .= "<td align=right {$show_red}>{$final_grade}</td></tr>";
		}
	
		$table .= "</table><br><br>";
		if ($below_benchmark) {
			$final_table .= $table;
			$stu_count++;
		}
	}

	$percent_fail = round(($stu_count*1.0) / ($stu_total*1.0) * 100,1);
	$count_str = "<p><strong>Active students this session: {$stu_total}";
	$count_str .= "<br>Below benchmark: <u>{$stu_count}</u> ({$percent_fail}% of active students)</strong></p>";
	
	Database::disconnect();
	$pdo = null;
	return $instructions.$count_str.$final_table;
}

function get_grade_report_term($benchmark, $acad_year, $acad_term, $target_url) {
	$final_table = "";
	$acad_session = get_session1($acad_year, $acad_term);
	$acad_session2 = get_session2($acad_year, $acad_term);
	
	$instructions = "<p>Showing all students with grades below {$benchmark}% in any course. ";
	$instructions .= "Any grade below {$benchmark}% is shown in red.</p>";
	$instructions .= "<p>Note: due to point differences between grading in sessions {$acad_session} and {$acad_session2}, ";
	$instructions .= "the overall grade will not be an exact average of the grade in the first and second session of the term.";
	$instructions .= "<p>Also, because of a memory issue in the database, this will just grab the first 50 students. ";
	
	$students = get_stu_by_status_term('active', $acad_term, $acad_year);
	if ($acad_session2 == 8) {
		$next_year = $acad_year + 1;
		$next_session = 1;
	}
	else {
		$next_year = $acad_year;
		$next_session = $acad_session2 + 1;
	}

	$stu_count = 0;
	$stu_total = count($students);
	foreach($students as $student_id) {
		// break;
		// $student_id = '24364';
		// $student_id = '24889';
		// if ($student_id <= '25701') {continue;}
		$name = get_student_name($student_id);
		$email = get_student_email($student_id);
		$next_status = get_stu_status_by_session($student_id, $next_session, $next_year);
		$below_benchmark = false;
	
		$table = "<table><thead><tr><th colspan=5>{$name}</th></tr></thead>";
		$table .= "<tr><td class=bold colspan=3>ID:{$student_id}</td>";
		$table .= "<td class=bold>Next session:</td><td class=bold>{$next_status}</td></tr>";
		$table .= "<tr><td class=bold colspan=5>Email:{$email}</td></tr><tbody>";
		$table .= "<tr><td></td><td></td></tr><tr ><td class=bold>Course</td><td class=bold>Course ID</td>";
		$table .= "<td class=bold>Session 1</td><td class=bold>Session 2</td><td class=bold>Overall</td></tr>";
		
		$skills = get_course_types();
		
		
		// $courses = get_courses_array($id, $acad_year, $acad_session);
		

		foreach ($skills as $course_type) {
			$show_red1 = "";
			$show_red2 = "";
			$course1 = "";
			$course2 = "";
			$course_name1 = "";
			$course_name2 = "";
			$grade1 = "";
			$grade2 = "";
			$grade_tot = "";
			$grade_data = "";
			
			$course1 = get_student_course_by_type($student_id, $acad_year, $acad_session, $course_type);
			if ($course1) {
				$course_name1 = get_course_title($course1);
				if ($acad_term == 2) {$grade1 = get_session_grade($student_id, $course1, $acad_year, $acad_session);}
				else {
					$grade1 = get_quick_session_grade($student_id, $course_type, $acad_year, $acad_session);
				}
				
				if ($grade1 < ($benchmark * 1.0)) {
					$show_red1 = "class=show_red";
					$below_benchmark = true;
				}
				$grade1 = round($grade1,0)."%";
			}
			$course2 = get_student_course_by_type($student_id, $acad_year, $acad_session2, $course_type);
			// $course2 = 'RW2B-1';
			if ($course2) {
				$course_name2 = get_course_title($course2);
				$grade2 = get_quick_session_grade($student_id, $course_type, $acad_year, $acad_session2);
				if ($grade2 < ($benchmark * 1.0)) {
					$show_red2 = "class=show_red";
					$below_benchmark = true;
				}
				$grade2 = round($grade2,0)."%";
			}

			if ($course1 or $course2) {
				if ($acad_term == 2) {$grade_data = $grade1;}
				else {
					$grade_tot = get_quick_term_grade($student_id, $course_type, $acad_year, $acad_term);
					$grade_data = $grade_tot."%";
				}
				
				if ($grade_tot != null and $grade_tot < benchmark) {
					$below_benchmark = true;
					$show_row = "class=show_red";
				}
				else {
					$show_red = "";
				}
				
			}
			
			
			if ($course1 == $course2) {
				$table .= "<tr><td>{$course_name1}<td>{$course1}</td>";
			}
			else {
				$table .= "<tr><td>{$course_name1}<br>{$course_name2}</td><td>{$course1}<br>{$course2}</td>";
			}
			
			$table .= "<td>{$grade1}</td><td>{$grade2}</td><td>{$grade_data}</td></tr>";
		}
	
		$table .= "</table><br><br>";
		if ($below_benchmark) {
			$final_table .= $table;
			$stu_count++;
		}
		// if ($stu_count == 50) {break;}
	}

	$percent_fail = round(($stu_count*1.0) / ($stu_total*1.0) * 100,1);
	$count_str = "<p><strong>Active students this term: {$stu_total}";
	$count_str .= "<br>Below benchmark: <u>{$stu_count}</u> ({$percent_fail}% of active students)</strong></p>";
	
	Database::disconnect();
	$pdo = null;
	return $instructions.$count_str.$final_table;
}

// function get_student_attn_grades($student_id, $acad_year, $acad_session) {
	// $name = get_student_name($student_id);
	// $courses = get_courses_array($student_id, $acad_year, $acad_session);
	
	// $table = "<table><thead><tr><th>Attendance Grades for {$name}, {$student_id} for Session {$acad_session}, {$acad_year}</th></tr></thead>";
	// $table .= "<tbody><tr><td class=bold>Class</td><td class=bold>Present</td><td class=bold>Late</td>";
	// $table .= "<td class=bold>Absent</td><td class=bold>Excused</td><td class=bold>Med Leave</td>";
	// $table .= "<td class=bold>LOA</td><td class=bold>Term</td><td class=bold>%</td></tr>";
	
	// foreach ($courses as $course) {
		// $course_name = get_course_title($course);
		// $present = get_attn_count_by_attn($student_id, $course, $acad_year, $acad_session, 'present');
		// $table .= "<tr><td>{$course_name}</td><td>{$present}</td></tr>";
	// }
	// $table .= "</tbody></table>";
	
	// return $table;
// }




?>