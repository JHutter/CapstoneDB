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
		
		$assignment = $_POST['assignment'];
		$message = $assignment;
		$points = $_POST['old_pts'];
		
		$target_url = "assignments_07.php";
		$message = get_change_points_desc_form($course, $acad_year, $chosen_session, $category, $assignment, $target_url);
		
		
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
</html>    