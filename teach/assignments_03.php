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
		include_once('../php/database.php');
		include_once('../php/get_name.php');
		include_once('../php/classlist.php');
		
		
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$acad_term = $_SESSION['term'];
		
		
		$chosen_session = $_POST['session'];
		$course = $_POST['course'];
		$category = $_POST['category'];
		$assignment = $_POST['assignment'];
		$cat_name = get_course_name_singular($course, $category);
		
		$course_name = get_course_title($course);
		$filename = "../logs/grades/{$course}_grade{$category}_input.sql";
		
		$pdo = Database::connect();
		
		$sql_array = [];
		// get list of stus
		$students = $students = get_stus_by_course_alpha($course, $acad_year, $chosen_session);
		$sql_get_stus = "select grade{$category}.student_id as 'student_id'
							from grade{$category}
							left join studentinfo on grade{$category}.student_id = studentinfo.student_id
							where acad_year = {$acad_year}
							and acad_session = {$chosen_session}
							and course_id = '{$course}'
							and assignment = '{$assignment}'
							order by stu_last";

		// foreach ($pdo->query($sql_get_stus) as $row_stus) {
		foreach ($students as $student_id) {
			// $student_id = $row_stus['student_id'];
			$blar = 'blargh';
			$student_name = get_student_name($student_id);
			$grade_new = $_POST["{$student_id}"];
			if ($grade_new == null) {
				$grade_new = 'null';
			}
			
			$sql_update_grade = "update grade{$category}
						set percent = {$grade_new}
						where acad_year = {$acad_year}
						and acad_session = {$chosen_session}
						and course_id = '{$course}'
						and assignment = '{$assignment}'
						and student_id = '{$student_id}'";
			
	
			//execute
			$pdo->exec($sql_update_grade);
			
			$student_backup_file = fopen($filename, "a");
			$txt = "\n#Grade entered for {$assignment} for {$student_name}, ID {$student_id}: {$grade_new}\n".$sql_update_grade.";\n\n";
			fwrite($student_backup_file, $txt);
			fclose($student_backup_file);
		}
		Database::disconnect();
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
		<H3>Input Grades for <?php echo $course_name;?>, Session <?php echo $chosen_session;?></H3>

		<?php
			echo "Success! Redirecting to the previous page...";
		
		?>
		
		<br><br>
				
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body> 
<script>
var course = "<?php echo $course; ?>";
var session = "<?php echo $chosen_session; ?>";
var category = "<?php echo $category; ?>";
var assignment = "<?php echo urlencode($assignment); ?>";
location.replace("assignments_02.php?session=" + session + "&course=" + course + "&assignment=" + assignment + "&category=" + category);
</script>  
</html>    