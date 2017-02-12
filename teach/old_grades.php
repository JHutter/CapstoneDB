<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/teach_session.php");
		include_once("../php/get_name.php");
		include_once("../php/classlist.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$sessions = [1,2,3];
		$teacher = $_SESSION['user_id'];
		$courses = get_teacher_courses_term($teacher);
		
		// $students = get_all_stus();
		
		// foreach ($sessions as $session) {
			// foreach ($courses as $course) {
				// $class = get_stus_by_course_alpha($course, $acad_year, $session);
				// $students = array_merge($students, $class);
			// }
		// }
		
		
		$target_url = "old_grades_01.php";
		$form = get_past_grades_form($target_url);
		

	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/teach_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<?php
			echo $form;

		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
