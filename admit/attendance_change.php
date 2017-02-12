<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn_forms.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		include_once("../php/forms.php");
		
		$target_url = "attendance_change_01.php";
		$form = get_attn_by_stu_form($target_url);
		
		$target_url_course = "attendance_change_03.php";
		$form_course = get_attn_by_course_form($target_url_course);
		
		

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
			echo $form_course;
		?>
		
		<br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
