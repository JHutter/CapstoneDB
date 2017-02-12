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
		
		$year = $_POST['year'];
		$session = $_POST['session'];
		$sort = $_POST['sort'];
		$sort2 = $_POST['sort2'];
		$active = (isset($_POST['active']) ? $_POST['active'] : ' ');
		$loa = (isset($_POST['loa']) ? $_POST['loa'] : ' ');
		$med = (isset($_POST['med']) ? $_POST['med'] : ' ');
		$term = (isset($_POST['term']) ? $_POST['term'] : ' ');
		
		switch ($sort) {
			case 'student_id':
				$sort_name = ", sorted by student ID";
				break;
			case 'stu_first':
				$sort_name = ", sorted by first name";
				break;
			case 'stu_last':
				$sort_name = ", sorted by last name";
				break;
			case 'enroll_status':
				$sort_name = ", sorted by enrollment status";
				break;
			default:
				$sort_name = "";
		}
		
		$results = get_student_report($year, $session, $sort, $sort2, $active, $loa, $med, $term);
		$heading = "<h3>Student Enrollment for Session {$session}, {$year}</h3>";
		$heading .= "<p>Showing {$active} {$loa} {$med} {$term} students{$sort_name}.</p>";
		$table = $heading."<br>".$results;
		$css_filepath = '../css/pdf_style.css';
		$filename = "Student-list-Session{$session}-{$year}.pdf";
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
