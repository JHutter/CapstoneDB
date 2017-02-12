<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/admit_session.php");
		include("../php/teach_schedule.php");
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		
		$target_url = "teacher_schedule_01.php?teacher=";
		$links = get_active_teacher_links($acad_year, $acad_session, $target_url);
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
		<H3>Look up schedule by teacher</H3>
		<p>Click on the list of active teachers:</p>
		
		<?php
			echo $links;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
