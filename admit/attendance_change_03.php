<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once('../php/attn_forms.php');
		include_once('../php/course_info.php');
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$acad_term = $_SESSION['term'];
		
		
		$course = $_POST['course'];
		$course_name = get_course_title($course);
		$month = $_POST['month'];
		$day = $_POST['day'];
		$year = $_POST['year'];
		$date = $year.$month.$day;
		$friendly_date = $month."/".$day."/".$year;
		$session = get_session_by_date($date);
		
		
		$count = $_GET['count'];
		if ($count == ''){
			$count = 0;
		}
		$target_url = "attendance_change_04.php";
		$form = get_input_class_attn($course, $year, $session, $date, $target_url);;
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
		<H3>Attendance for <?php echo $course_name." (".$course."), ".$friendly_date;?></H3>
		
		<?php
			echo $form;
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