<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		
		$acad_year = $_POST['year'];
		$acad_session = $_POST['session'];
		$course = $_POST['course'];
		$course_name = get_course_title($course);
		$table_attn = get_class_attn_table($course, $acad_year, $acad_session);
		$table_grades = get_class_attn_grades($course, $acad_year, $acad_session);
		$heading = "<h3>Attendance for {$course_name}, Session {$acad_session}, {$acad_year}</h3>";
		$results = $heading.$table_attn."<br>".$table_grades;
		$css_filepath = '../css/pdf_style.css';
		$filename = "{$course}-attendance-Session{$acad_session}-{$acad_year}.pdf";
		$button_attn = get_pdf_form_button_landscape($heading.$table_attn, $css_filepath, $filename, 'download', 'Download Attendance Record');
		$button_grades = get_pdf_form_button_portrait($heading.$table_grades, $css_filepath, $filename, 'download', 'Download Attendance Grades');
		$button_block = $button_attn."<br>".$button_grades;
		

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
			echo $button_block;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
