<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn_forms.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		include_once("../php/student_schedule.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$target_url = "student_schedule_01.php";
		$form = get_student_sched_form($target_url);
		
		$target_url = "student_schedule_02.php";
		$form2 = get_schedule_form_batch($target_url);
		
		

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
			echo $form;
			echo "<br>";
			echo $form2;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
