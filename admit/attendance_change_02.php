<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn_forms.php");
		include_once("../php/attn.php");
		include_once("../php/attn_exec.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		include_once("../php/forms.php");
		
		$acad_session = $_SESSION['session'];
		$acad_year = $_SESSION['year'];
		
		$student = $_POST['student'];
		$student_name = get_student_name($student);
		$attendance = $_POST['attendance'];
		
		$month = $_POST['month_start'];
		$day = $_POST['day_start'];
		$year = $_POST['year_start'];
		$date_start = $year.$month.$day;
		
		$month = $_POST['month_end'];
		$day = $_POST['day_end'];
		$year = $_POST['year_end'];
		$date_end = $year.$month.$day;
		
		$session = get_session_by_date($date_start);
		$year = substr($date_start, 0, 4);
		
		$option = $_POST['option'];
		if ($option == 'course') {
			$courses = $_POST['courses'];
			foreach ($courses as $course_type) {
				$course = get_student_course_by_type($student, $year, $session, $course_type);
				exec_attn_student($course, $student, $date_start, $date_end, $attendance);
			}
			
		}
		else { //option is 'all'
			exec_attn_student_date($student, $date_start, $date_end, $attendance);
		}
		
		
		
		

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
		Success. Redirecting to previous page.
		
		<br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>  
<script type="text/javascript">
	var student = '<?php echo $student;?>';
	location.replace("attendance_change_01.php?student=" + student);
</script> 
</html>    
