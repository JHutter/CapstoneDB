<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admin_session.php");
		include_once("../php/rollover_admin.php");
		
		$message = populate_session(2016, 4);
		
		// $message = get_courses_array('24889', 2016, 3);
		// $message = implode($message);
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/admin_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<H3>Rollover</H3>

		<?php
			echo $message;
			
		?>
		
		<br><br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
