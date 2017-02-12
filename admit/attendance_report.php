<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/admit_session.php");
		include("../php/teach_schedule.php");
		include("../php/student_reports.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$target_url = "attendance_report_01.php";
		$form = get_attn_report_form($target_url);
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
			echo $form;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
