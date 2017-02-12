<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/teach_schedule.php");
		include_once("../php/report_cards.php");
		include_once("../php/classlist.php");
		include_once("../php/get_name.php");
		include_once("../php/course_info.php");
		include_once("../php/pdf_wrapper.php");
		include_once("../php/student_schedule.php");
		
		
		$acad_year = $_POST['year'];
		//$acad_session = $_POST['session'];
		$acad_session = $_POST['session'];
		// $acad_session = get_session1($acad_year, $acad_term);
		// $student_id = trim($_POST['student_id']);
		$teacher_option = $_POST['teacher_option'];
		$students = get_stu_by_status('active', $acad_session, $acad_year);
		// echo count($students);
		$path = "../records/schedules/";
		$css_file = "../css/pdf_style.css";
		$heading = "<img src=../resources/img/heading_img.jpg alt='Capstone English Mastery Center'><br>";
		
		if ($teacher_option == 'yes') {
			foreach ($students as $student_id) {
				$student_name = str_replace(" ", "", get_student_name($student_id));
				$info = student_schedule_block($student_id, $acad_year, $acad_session);
				$table = get_schedule_table($student_id, $acad_year, $acad_session);
				$schedule = str_replace("'", "", $heading.$info.$table);
				// echo $schedule;
				// break;

				$filename = "{$path}2016_{$acad_session}_{$student_id}-{$student_name}-schedule.pdf";
				// server_download_pdf($schedule, $css_file, $filename);
			}
		}
		else {
			foreach ($students as $student_id) {
				$student_name = str_replace(" ", "", get_student_name($student_id));
				$info = student_schedule_block($student_id, $acad_year, $acad_session);
				$table = get_schedule_table_blank_teacher($student_id, $acad_year, $acad_session);
				$schedule = str_replace("'", "", $heading.$info.$table);
				
				$filename = "{$path}2016_{$acad_session}_{$student_id}-{$student_name}-schedule.pdf";
				 // server_download_pdf($schedule, $css_file, $filename);
			}
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
	</div>
	

	
	
	
	
		
		<div id="section">
		<?php
			echo "Success. Check the server for reports";
		?>
		</div>
		
		<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    

   
