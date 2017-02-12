<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admin_session.php");
		include_once("../php/grades.php");
		include_once("../php/classlist.php");

		$student = '25755';
		$course = 'RW3A-1';
		$category = '20';
		$acad_year = 2016;
		$acad_session = 2;
		$acad_term = 2;
		// $students = ['25745','25690','25441'];
		// $students = get_stu_by_status('active', $acad_session, $acad_year);
		$students = get_stu_by_status_term('active', $acad_term, $acad_year);
		// $message = update_session_grades_stu_course($student, $course, $acad_year, $acad_session);
		// $message = update_session_grades_stu_course_total($student, $course, $acad_year, $acad_session);
		// $message = update_session_grades_all_stus($students, $acad_year, $acad_session);
		$message = update_term_grades_all_stus($students, $acad_year, $acad_term);
		// $message = str_replace(";", ";<br>", $message);
		
		
		$message = get_quick_term_grade($student, 'tthpm', $acad_year, $acad_session);
		// $message = get_courses_array('24889', 2016, 3);
		// $message = implode($message);
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/admin_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<H3>Rollover</H3>

		<?php
			echo $message;
			
		?>
		
		<br><br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
