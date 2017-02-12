<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/enrollment.php");
		include_once("../php/pdf_button.php");
		
		$student_id = trim($_POST['student_id']);
		$stu_first = trim($_POST['stu_first']);
		$stu_last = trim($_POST['stu_last']);
		$email = trim($_POST['email']);
		
		$acad_year = $_POST['year'];
		$acad_session = $_POST['session'];
		
		$course1 = $_POST['10am'];
		$course2 = $_POST['11am'];
		$course3 = $_POST['mwfpm'];
		$course4 = $_POST['tthpm'];
		
		$courses = [];
		if ($course1 != ""){
			$courses[] = $course1;
		}
		if ($course2 != ""){
			$courses[] = $course2;
		}
		if ($course3 != ""){
			$courses[] = $course3;
		}
		if ($course4 != ""){
			$courses[] = $course4;
		}
		
		$message = add_new_student($student_id, $stu_first, $stu_last, $email, $courses, $acad_year, $acad_session);
		
		$filename = "{$student_id}_enrollment.pdf";
		$css_filepath = '../css/pdf_style.css';
		$option = "download";
		$button_name = "Download New Student Information";
		
		$button = get_pdf_form_button_portrait($message, $css_filepath, $filename, $option, $button_name);
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
		<H3>New Student</H3>
		
		<?php
			echo $message;
			echo "<br><br>";
			echo $button;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
