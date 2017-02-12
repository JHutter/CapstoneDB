<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn_forms.php");
		include_once("../php/attn.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		include_once("../php/forms.php");
		
		$acad_session = $_SESSION['session'];
		$acad_year = $_SESSION['year'];
		
		$student = $_GET['student'];
		$student_name = get_student_name($student);
		
		$target_url = "attendance_change_02.php";
		$form = get_attn_by_date_form($target_url, $student);

		
		$attendance = get_student_attn_table($student, $acad_year, $acad_session);
		
		$table = "<h3>Attendance for current session for {$student_name}, ID {$student}</h3>".$attendance;
		$css_filepath = "../css/pdf_style.css";
		$filename = "{$student}_attendance_session{$acad_session}_{$acad_year}.pdf";
		$button_name = "     Download Attendance Record     ";
		$button = get_pdf_form_button_landscape($table, $css_filepath, $filename, 'download', $button_name);
		
		
		
		
		

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
			// echo $form_stu;
			// echo "<br><br>";
			// echo $form_class;
			echo $form;
			echo "<br><br>";
			echo "<h3>Attendance for {$student_name} for current session</h3>";
			echo $attendance;
			echo $button;
		?>
		
		<br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
