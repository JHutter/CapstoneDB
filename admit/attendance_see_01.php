<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		
		$year = $_POST['year'];
		$session = $_POST['session'];
		$student_id = trim($_POST['student_id']);
		$student_name = get_student_name($student_id);
		
		$table = get_student_attn_table($student_id, $year, $session);
		$heading = "<h3>Attendance for {$student_name} {$student_id}, Session {$session}, {$year}</h3>";
		$results = $heading.$table;
		$css_filepath = '../css/pdf_style.css';
		$filename = "Student{$student_id}-attendance-Session{$session}-{$year}.pdf";
		$button = get_pdf_form_button_landscape($results, $css_filepath, $filename, 'download', 'Download Student Attendance');
		
		

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
