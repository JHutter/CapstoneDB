<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn_forms.php");
		include_once("../php/pdf_button.php");
		include_once("../php/student_schedule.php");
		
		$acad_year = $_GET['year'];
		$acad_session = $_GET['session'];
		$student_id = $_GET['student'];
		$name_no_space = str_replace(" ","",get_student_name($student_id));
		
		$heading = "<img src=../resources/img/heading_img.jpg alt='Capstone English Mastery Center'><br>";
		$info = student_schedule_block($student_id, $acad_year, $acad_session);
		$table = get_schedule_table($student_id, $acad_year, $acad_session);
		$full_schedule = str_replace("'", "", $heading.$info.$table);
		
		$css_filepath = "../css/pdf_style.css";
		$filename = "{$name_no_space}_{$student_id}_schedule_session{$acad_session}_{$acad_year}.pdf";
		$button_name = "    Download Schedule    ";
		$button = get_pdf_form_button_portrait($full_schedule, $css_filepath, $filename, 'download', $button_name);
	
		
		

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
			echo $info;
			echo $table;
			echo "<br><br>";
			echo $button;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
