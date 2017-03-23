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
		$chosen_session = $_GET['session'];
		$acad_term = $_SESSION['term'];
		$course = $_GET['course'];
		$category = $_GET['category'];
		$cat_name_sg = get_course_name_singular($course, $category);
		$cat_name_pl = get_course_name_plural($course, $category);
		$date = '03/18';//date('Md');
		$course_name = get_course_title($course);
		
		$target_url_prev = "assignments_06.php";
		$target_url_delete = "assignments_08.php";
		$target_url_new = "assignments_05.php";
		$prev_assigned = get_assigned_table($course, $category, $acad_year, $chosen_session, $target_url_prev, $target_url_delete);
		$new_assignment = get_new_assignment_form($target_url_new, $course, $category, $acad_year, $chosen_session);
		
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
		<H3>Add or Edit <u><?php echo $cat_name_pl;?></u> for <?php echo $course_name;?>, Session <?php echo $chosen_session;?></H3>

		<?php
		
			echo $prev_assigned;
			echo "<br><br>";
			echo $new_assignment;
		
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    