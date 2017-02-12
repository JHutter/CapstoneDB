 
<!-- Begin Body -->  
 <?php
include('../11/admit_session.php');
include('../11/database.php');

$acad_year = $_SESSION['year'];
$acad_session = $_SESSION['session'];
$course = $_GET['course'];
$teacher = $_GET['teacher'];
$pdo = Database::connect();
echo "Selected course: {$course}<br><br>";


echo "<br><table bgcolor=\"#aaaaff\" cellspacing=1 cellpadding=\"2\">";
echo '<col width="100">';
echo "<tr bgcolor=\"#BDBDBD\">
	<td>Student Name</td><td>ID</td>";
$row_color = 1;
	
	//get dates from course+course_type+class_dates
	//echo td for each date
	$sql_get_dates = "SELECT DISTINCT DATE_FORMAT(class_date, '%a %c/%e') AS 'class_date',
							class_date AS 'date',
							DATE_FORMAT(class_date, '%Y%m%d') AS 'tableDate'
								from ATTENDANCE
								where acad_year = {$acad_year}
								and acad_session = {$acad_session}
								and course_id = '{$course}'
								order by date";
	
	foreach ($pdo->query($sql_get_dates) as $row){
		$date = $row['class_date'];
		echo "<td>{$date}</td>";
		}
	echo "</tr>";
	
	$sql_get_stus = "SELECT COURSEENROLLMENT.student_id AS 'sID',
							CONCAT(stu_first, ' ', stu_last) AS 'SName'
					FROM COURSEENROLLMENT
					left join STUDENTINFO on COURSEENROLLMENT.student_id = STUDENTINFO.student_id
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					order by stu_last";

	//each student				
	foreach ($pdo->query($sql_get_stus) as $row){
		$name =$row['SName'];
		$id = $row['sID'];
		
		if ($row_color % 2 == 1) {
			echo "<tr bgcolor=\"#FFFFFF\"><td nowrap><b>{$name}</b></td>";
		}
		else {
			echo "<tr bgcolor=\"#E6E6E6\"><td nowrap><b>{$name}</b></td>";
		}
		echo "<td>{$id}</td>";
		
		//each date in course
		foreach ($pdo->query($sql_get_dates) as $row2){
			$date = $row2['tableDate'];
			$sql_get_attn = "SELECT attendance_yn
							from ATTENDANCE 
							where acad_year = {$acad_year} 
							and acad_session = {$acad_session}
							and student_id = '{$id}'
							and course_id = '{$course}'
							and class_date = {$date}";

			//echo $sql_get_attn;

			foreach ($pdo->query($sql_get_attn) as $row3){
				$attn = $row3['attendance_yn'];
				if ($attn == 'none'){$attn = '-';}
				}
			
			if ($attn == 'absent') { echo "<td><font color=\"#B40404\">{$attn}</font></td>"; $stu_absent++;}
				else { 
					echo "<td>{$attn}</td>"; 
					if ($attn == 'present') {$stu_present++;}
					if ($attn == 'late') {$stu_late++;}
				}
			}
		echo "</tr>";
		$row_color++;
		}
	echo "</table>";

// table for attendance summary	
echo "<br><br><table bgcolor=\"#aaaaff\" cellspacing=1 cellpadding=\"2\">";
echo '<col width="100">';
echo "<tr bgcolor=\"#BDBDBD\">
	<td>Student Name</td><td>ID</td>
	<td>Present</td>
	<td>Late</td>
	<td>Absent</td>
	<td>Other</td>
	<td>Remaining</td>
	<td align=\"right\">%</td>
	</tr>";
$row_color = 1;

$sql_get_stus = "SELECT COURSEENROLLMENT.student_id AS 'sID',
							CONCAT(stu_first, ' ', stu_last) AS 'SName'
					FROM COURSEENROLLMENT
					left join STUDENTINFO on COURSEENROLLMENT.student_id = STUDENTINFO.student_id
					where acad_year = {$acad_year}
					and acad_session = {$acad_session}
					and course_id = '{$course}'
					order by stu_last";

	//each student				
foreach ($pdo->query($sql_get_stus) as $row4){
	$name =$row4['SName'];
	$id = $row4['sID'];
	
	if ($row_color % 2 == 1) {
		echo "<tr bgcolor=\"#FFFFFF\"><td nowrap><b>{$name}</b></td>";
	}
	else {
		echo "<tr bgcolor=\"#E6E6E6\"><td nowrap><b>{$name}</b></td>";
	}
	echo "<td>{$id}</td>";
$sql_get_attn = "SELECT attendance_yn
							from ATTENDANCE 
							where acad_year = {$acad_year} 
							and acad_session = {$acad_session}
							and student_id = '{$id}'
							and course_id = '{$course}'
							order by class_date";
	$count = 0;
	$present = 0;  // literally present
	$late = 0;
	$absent = 0;
	$other = 0;
	$here = 0; // it counts for a grade
	$remain = 0;
	
	foreach ($pdo->query($sql_get_attn) as $row5){
		$attn = $row5['attendance_yn'];
		// check each attendance condition
		if ($attn == 'present') { 
			$count++; 
			$present++; 
			$here++;
		}
		else if ($attn == 'late') {
			$count++;
			$late++;
			if ($late < 3) {
				$here++;
			}
		}
		else if ($attn == 'absent') {
			$count++;
			$absent++;
		}
		else if ($attn == 'none') {
			$remain++;
		}
		else {
			$other++;
		}
	}
$grade = round(((0.0 + $here) / (0.0 + $count) * 100), 0);
echo "<td>{$present}</td><td>{$late}</td><td>{$absent}</td><td>{$other}</td><td>{$remain}</td>";
if ($grade < 80.0) {
	echo "<td align=\"right\"><font color=\"#B40404\">{$grade}%</font></td></tr>";
}
else {
	echo "<td align=\"right\"><font>{$grade}%</font></td></tr>";
}

$row_color++;
$grade = 0.0;
}
echo "</table>";

Database::disconnect();	
?>
<br><br>

"Other" refers to loa, excused, med leave, or term.<br>
"Remaining" refers to class dates remaining in the <br>
term (i.e., not marked in attendance). In cases where <br>
attendance is missing (e.g., a student starts at the <br>
school in the second week), their "remaining" number <br>
will differ. Loa/excused/term/med leave is not counted <br>
in remaining days. Attendance grade (% column) is <br>
rounded to the nearest whole number.
 <br><br>
Lateness: Each late arrival after the third time is<br>
marked as absent. The first and second late arrival<br>
for that student in that class is marked as present.<br>