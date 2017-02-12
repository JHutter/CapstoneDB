<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/teach_session.php");
		include('../php/attn_forms.php');
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$acad_term = $_SESSION['term'];
		$course = $_GET['course'];
		
		$current_date = date('Ymd');
		
		$count = $_GET['count'];
		if ($count == ''){
			$count = 0;
		}
		$target_url = "attendance_03.php";
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
			echo get_input_class_attn($course, $acad_year, $acad_session, $current_date, $target_url);
		?>
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body> 
<script>
	var count = "<?php echo $count;?>";
	if (count > 0) {
		if (count == 1) {
			alert("Attendance is missing for " + count + " student.");
		}
		else {
			alert("Attendance is missing for " + count + " students.");
		}
	}
	</script>  
</html>    