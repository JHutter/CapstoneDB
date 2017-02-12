<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/classlist.php");
		include_once("../php/pdf_button.php");
		include_once("../php/get_name.php");
		include_once("../php/couse_info.php");
		
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$target_url = "classlists_01.php";
		$form = get_classlist_form($target_url);
		
		$target_url2 = "classlists_02.php";
		$form2 = get_assigned_course_form($target_url2, 'get');
		
		

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
		<h3>See Classlists</h3>
		<?php
			echo $form;
			echo "<br><br>";
			echo $form2;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
