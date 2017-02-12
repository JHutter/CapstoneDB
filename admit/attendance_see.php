<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/attn_forms.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$target_url_stu = "attendance_see_01.php";
		$target_url_class = "attendance_see_02.php";
		$form_stu = get_student_attn_form($target_url_stu);
		$form_class = get_class_attn_form($target_url_class);
		
		

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
			echo $form_stu;
			echo "<br><br>";
			echo $form_class;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
