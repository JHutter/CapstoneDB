<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/classlist.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		
		$acad_year = $_POST['year'];
		$acad_session = $_POST['session'];
		$course = $_POST['course'];
		$page_break = "<pagebreak />";
		$negative_length_page_break = -1 * strlen($page_break);
		if ($course == 'level' or $course == 'skill') {
			if ($course == 'skill') {
				$courses = get_assigned_courses_by_session($acad_year, $acad_session, 'skill');
			}
			else {
				$courses = get_assigned_courses_by_session($acad_year, $acad_session, 'sk_level');
			}
			
			$results = "";
			foreach ($courses as $course) {
				$course_name = get_course_title($course);
				$table = get_stu_list($course, $acad_year, $acad_session);
				$heading = "<h3>Classlist for {$course_name}, Session {$acad_session}, {$acad_year}</h3>";
				$classlist = $heading.$table;
				
				
				$results .= $classlist."<br><br>".$page_break;
			}
			$results = substr($results, 0,$negative_length_page_break);
			
			$css_filepath = '../css/pdf_style.css';
			$filename = "classlists-Session{$acad_session}-{$acad_year}.pdf";
	
			$button = get_pdf_form_button_portrait($results, $css_filepath, $filename, 'download', 'Download Classlists');
			
			
		}
		else {
			$course_name = get_course_title($course);
			$table = get_stu_list($course, $acad_year, $acad_session);
			$heading = "<h3>Classlist for {$course_name}, Session {$acad_session}, {$acad_year}</h3>";
			$results = $heading.$table;
			
			$css_filepath = '../css/pdf_style.css';
			$filename = "{$course}-classlist-Session{$acad_session}-{$acad_year}.pdf";
	
			$button = get_pdf_form_button_portrait($results, $css_filepath, $filename, 'download', 'Download Classlist');
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
		<?php
			echo $results;
			echo "<br>";
			echo $button;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
