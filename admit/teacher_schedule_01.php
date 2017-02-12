<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/teach_schedule.php");
		include_once("../php/get_name.php");
		include("../php/pdf_button.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$teacher = $_GET['teacher'];
		$teacher_name = get_teacher_name($teacher);
		$teacher_name_no_space = str_replace(' ', '', $teacher_name);
		$css_filepath = '../css/pdf_style.css';
		$filename = "{$teacher_name_no_space}-schedule.pdf";
		
		$table = get_teacher_schedule($teacher, $acad_year, $acad_session);
		$pdf = "<h3>{$teacher_name} Schedule, Session {$acad_session}</hr><br><br><br>".$table;
			
		
		
		
		
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
		<H3><?php echo $teacher_name;?>'s Schedule</H3>
		
		
		<?php
			echo $table;
			echo '<br><br>';
			echo get_pdf_form_button_portrait($pdf, $css_filepath, $filename, 'download', 'Download Printer Friendly Schedule');
			
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
