<?php
//$session = $_SESSION['session'];
include("../11/database.php");
$pdo = Database::connect();
$benchmark = $_GET['benchmark'];
$rev_benchmark = 100 - $benchmark;
$acad_year = $_GET['acad_year'];
$acad_session = $_GET['acad_session'];

echo "Showing all students with absence over {$benchmark}% in any course. (Percent present {$rev_benchmark}% or below.) <br>
Note: Any attendance grade below {$rev_benchmark}% is shown in red.<br><br>";

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
	
	$echo_str = "<table bgcolor=\"#aaaaff\" cellspacing=1 cellpadding=\"2\">
			<tr bgcolor=\"#d9d9d9\"><th colspan=\"8\" width=\"650\" align=\"left\"><a href=\"../99/admit_10_01.php?stu_id={$id}\" style=\"color:black\" target=\"_blank\">{$name}</a></th>			
			<tr bgcolor=\"#d9d9d9\"><td colspan=\"8\"><table><tr><td width=\"100\">ID:{$id}</td><td>Email:{$email}</td></tr></table></td></tr>
			<tr bgcolor=\"#f2f2f2\"><td></td>
			<td>Present #</td><td>Late #</td>
			<td>Absent #</td><td>Other #</td>
			<td>Remaining #</td>
			<td>Percent Present</td>
			<td>Hours Missed</td></tr>";
	
	$sql_get_courses = "select courseenrollment.course_id as 'course_id'
						from courseenrollment
						left join course on courseenrollment.course_id = course.course_id
						left join course_type on course.course_type = course_type.course_type
						where student_id = '{$id}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						order by course_order asc";

	foreach ($pdo->query($sql_get_courses) as $row2) {
		$course = $row2['course_id'];
		$echo_str .= "<tr bgcolor=\"#ffffff\"><td>{$course}</td>";
		
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
		
		foreach ($pdo->query($sql_get_attn) as $row3) {
			$attn = $row3['attendance_yn'];
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
		$grade = round(((0.0 + $here) / (0.0 + $count) * 100), 0);
		
		$echo_str .= "<td>{$present}</td><td>{$late}</td><td>{$absent}</td><td>{$other}</td><td>{$remain}</td>";
		if ($grade < $rev_benchmark + 0.0) {
			$echo_str .= "<td align=\"right\"><font color=\"#B40404\">{$grade}%</font></td>";
			$above_benchmark = 1;
		
		}
		else {
			$echo_str .= "<td align=\"right\"><font>{$grade}%</font></td>";
		}
		
		$type = substr($course, 0,1);
		if ($type == "S" or $type == "R") { $class_hour = 1.0;}
		else if ($type == "G") { $class_hour = 1.0 + 2.0/3.0;}
		else { $class_type = 1.5;}
		
		$hours_missed = round($not_here * $class_hour, 2);
		$hours_total = round($class_total * $class_hour, 2);
		$echo_str .= "<td><table><tr><td width=\"30\">{$hours_missed}</td><td> of {$class_total}</td></tr></table></td></tr>";
	}
	
	
	
	
	$echo_str .= "</table><br><br>";
	if ($above_benchmark and $any_attn) {
		echo $echo_str;
		$stu_count++;
	}
	
}

echo "Attendance warning count: {$stu_count}";

Database::disconnect();

?>