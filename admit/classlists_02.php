<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/classlist.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		include_once("../php/couse_info.php");
		
		$acad_year = $_GET['year'];
		$acad_session = $_GET['session'];
		$sort = $_GET['sort'];
		
		$courses = get_assigned_courses_by_session($acad_year, $acad_session, $sort);
		$table = get_assigned_course_table($acad_year, $acad_session, $courses);
		
		$css_filepath = "../css/pdf_style.css";
		$filename = "course_assignment_session{$acad_session}_{$acad_year}.pdf";
		$button_name = "    Download Course Assignment    ";
		$button = get_pdf_form_button_portrait($table, $css_filepath, $filename, 'download', $button_name);
		
		

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
			echo "<br>";
			echo $button;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
