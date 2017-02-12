<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	
	<?php
		include_once("../php/teach_session.php");
		include_once("../php/both_sessions.php");
		include_once("../php/assignment.php");
		include_once("../php/course_list.php");
		include_once("../php/course_info.php");
		include_once("../php/grade_tables.php");
		include_once("../php/grade_category.php");
		
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$chosen_session = $_POST['session'];
		$acad_term = $_SESSION['term'];
		$course = $_POST['course'];
		$category = $_POST['category'];
		$cat_name_sg = get_course_name_singular($course, $category);
		$cat_name_pl = get_course_name_plural($course, $category);
		$course_name = get_course_title($course);
		$option = $_POST['option'];
		
		$assignment = $_POST['assignment'];
		
		if ($option == 'edit') {
			$new_description = $_POST['new_description'];
			$points = $_POST['new_points'];
			$filename = "../logs/grades/{$course}_grade{$category}_edit.sql";
			$message = update_assignment($course, $acad_year, $chosen_session, $category, $assignment, $filename, $new_description, $points);
		}
		
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
		<H3>Assignments for <?php echo $course_name;?>, Session <?php echo $chosen_session;?></H3>

		<?php
		
			echo $message;
		
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>  
<script>
var course = "<?php echo $course; ?>";
var session = "<?php echo $chosen_session; ?>";
var category = "<?php echo $category; ?>";
location.replace("assignments_04.php?session=" + session + "&course=" + course + "&category=" + category);
</script>    
</html>    