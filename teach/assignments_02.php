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
		include_once("../php/classlist.php");
		
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$chosen_session = $_GET['session'];
		$acad_term = $_SESSION['term'];
		$course = $_GET['course'];
		$category = $_GET['category'];
		$assignment = $_GET['assignment'];
		$cat_name = get_course_name_singular($course, $category);
		$date = date('Md');
		$course_name = get_course_title($course);
		$students = get_stus_by_course_alpha($course, $acad_year, $chosen_session);
		
		$target_url = "assignments_03.php";
		$form = get_grade_input_form($target_url, $assignment, $course, $acad_year, $chosen_session, $category, $students);
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
			echo $form;
		?>
		
		<br><br>
		<form 
		oninput="x.value=Math.round((parseFloat(a.value)/parseFloat(b.value))*1000)/10">
		  <table><thead><tr><th colspan=2>Calculate Percent</th></tr>
		  <tr><td>Correct Questions:</td><td>
		  <input type="number" id="a" name="a" ></td></tr>
		  
		  <tr><td>Total Questions:</td><td>
		  <input type="number" id="b" name="b" ></td></tr>
		  
		  <tr><td>Percent:</td><td>
		  <output name="x" for="a b" ></output>%</td></tr></table>
		  <br><br>
		  
		</form>

		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    