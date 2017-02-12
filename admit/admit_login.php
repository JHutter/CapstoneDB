<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/login_menu.php");
			echo get_login_menu('admit');
		?>
	</div>
	
	
	
	<div id="section">
		<H3>Please log in as admissions to continue:</H3>

		<?php
			include("../php/admit_login.php");
		?>
		
		<?php
			include('../php/login_form.php');
		?>

		
	</div>


	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
