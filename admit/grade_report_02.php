<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/teach_schedule.php");
		include_once("../php/student_reports.php");
		include_once("../php/grade_tables.php");
		include_once("../php/pdf_button.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$year = $_POST['year'];
		$term = $_POST['term'];
		$benchmark = $_POST['benchmark'];
		
		$target_url = "student_page_grade.php?session={$term}&year={$year}&student_id=";
		$results = get_grade_report_term($benchmark, $year, $term, $target_url);
		$heading = "<h3>Student Grades for Session {$term}, {$year}</h3>";
		$table = $heading."<br>".$results;
		$css_filepath = '../css/pdf_style.css';
		$filename = "Student-grades-Term{$term}-{$year}.pdf";
		$button = get_pdf_form_button_portrait($table, $css_filepath, $filename, 'download', 'Download Student Report');
		
		

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
			echo $heading;
			echo $button;
			echo "<br>";
			echo $results;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
