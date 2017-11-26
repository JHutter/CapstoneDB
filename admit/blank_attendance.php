<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/database.php");
		include_once("../php/pdf_button.php");
		function get_blank_attn($acad_session) {
			$pdo = Database::connect();
			$date = $_SESSION['date'];
			// return 'foobar';
			
			$table = "<table><thead><tr><th colspan=5>Missing Attendance</th></tr></thead><tbody>";
			$table .= "<tr><td class=bold>Course</td><td class=bold>Teacher</td><td class=bold>Date</td><td class=bold>Student</td><td class=bold>ID</td></tr>";
			
			$sql_blank_attn = "select attendance.course_id, teacher_id, class_date, attendance.student_id, concat(stu_first, ' ', stu_last) as 'Name'
								from attendance
								left join studentinfo on attendance.student_id = studentinfo.student_id
								left join courseassignment on attendance.course_id = courseassignment.course_id
								where attendance.acad_session = {$acad_session} 
								and courseassignment.acad_session = {$acad_session} 
								and class_Date < {$date}
								and attendance_yn = 'none'
								order by  teacher_id, course_id, class_date asc";
			
			foreach ($pdo->query($sql_blank_attn) as $row_attn) {  
				$course = $row_attn['course_id'];
				$teacher = $row_attn['teacher_id'];
				$class_date = $row_attn['class_date'];
				$student = $row_attn['student_id'];
				$name = $row_attn['Name'];
				
				$table .= "<tr><td>{$course}<td>{$teacher}</td><td>{$class_date}</td><td>{$name}</td><td>{$student}</td></tr>";
			}
			
			
			$table .= "</tbody></table>";
			
			
			Database::disconnect();
			$pdo = null;
			return $table;
		}
		
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$table = get_blank_attn($acad_session);
		// $button = '';
		$button = get_pdf_form_button_portrait($table, "../css/pdf_style.css", "missing_attendance.pdf", "download", "Download Record of Missing Attendance");
		
		

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
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<?php
			echo $table;
			echo '<br><br>';
			echo $button;

		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
