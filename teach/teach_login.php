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
			echo get_login_menu('teach');
		?>
	</div>
	
	
	
	<div id="section">
		<H3>Please log in as a teacher to continue:</H3>

		<?php
			include("../php/teach_login.php");
		?>
		
		<?php
			$default_username = "lord.kelvin";
			$default_pass = "5555555";
			include('../php/login_form.php');
		?>
		
		<p>Teacher usernames consist of first name period last name (first.last).</p>
		
	</div>


	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
