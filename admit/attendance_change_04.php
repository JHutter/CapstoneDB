<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once('../php/attn_exec.php');
		include_once('../php/attn.php');
		include_once('../php/get_name.php');
		
		$user_name = 'Admissions';
		$course = $_GET['course'];
		$date = $_GET['date'];
		$year = substr($date, 0, 4);
		$session = get_session_by_date($date);
		
		
		$message = exec_attn_class($course, $year, $session, $user_name, $date);
		$count = check_none($course, $date);
		
		$attendance = get_class_attn_table($course, $year, $session);
		
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
	</div>
	
	
	
	<div id="section">
		<H3>Attendance</H3>
		
		<?php
			echo $attendance;
		?>
		
		<script>
			// var course = '<?php echo $course; ?>';
			// var count = '<?php echo $count; ?>';
			// location.replace("attendance_02.php?course=" + course + "&count=" + count);
		</script>
	</div>


	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    