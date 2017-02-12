<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/admit_session.php");
		include("../php/teach_schedule.php");
		include("../php/student_reports.php");
		include("../php/pdf_button.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$student_id = $_GET['student_id'];
		$session = $_GET['session'];
		$year = $_GET['year'];
		
		// $target_url = "attendance_report_01.php";
		$page = get_attendance_page_student($student_id, $year, $session);
		$css_filepath = '../css/pdf_style.css';
		$filename = "Student-info-Session{$session}-{$year}.pdf";
		$button = get_pdf_form_button_landscape($page, $css_filepath, $filename, 'download', 'Download Student Page');
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
		<H3>Student Report</H3>
		
		<?php
			echo $page;
			echo "<br>";
			echo $button;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
