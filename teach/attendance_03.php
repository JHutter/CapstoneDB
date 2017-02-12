<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/teach_session.php");
		include('../php/attn_exec.php');
		
		$user_name = $_SESSION['user_name'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$acad_term = $_SESSION['term'];
		$course = $_GET['course'];
		
		$current_date = date('Ymd');
		
		
		$message = exec_attn_class($course, $acad_year, $acad_session, $user_name, $current_date);
		$count = check_none($course, $current_date);
		
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/teach_menu.php");
		?>
	</div>
	
	
	
	<div id="section">
		<H3>Attendance</H3>
		
		<?php
			echo $message;
		?>
		
		<script>
			var course = '<?php echo $course; ?>';
			var count = '<?php echo $count; ?>';
			location.replace("attendance_02.php?course=" + course + "&count=" + count);
		</script>
	</div>


	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    