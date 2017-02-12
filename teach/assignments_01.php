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
		include_once("../php/pdf_button.php");
		include_once("../php/classlist.php");
		
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$chosen_session = $_GET['session'];
		$acad_term = $_SESSION['term'];
		$course = $_GET['course'];
		$date = date('Md');
		$categories_text = [];
		$cat_names = [];
		$sessions = get_session_array($acad_term);
		$course_is_assigned = course_is_assigned($teacher, $course, $acad_year, $chosen_session);
		$course_name = get_course_title($course);
		$target_url_input_grade = "assignments_02.php?session={$chosen_session}&course=";
		$content = "<h3>Grades for {$course}, Session {$chosen_session}, {$acad_year}, as of {$date}</h3>";
		$students = ['24364'];
		$students = get_stus_by_course_alpha($course, $acad_year, $chosen_session);
		$categories = ['40a','40b','20'];
		
		
		
		
		foreach ($categories as $category) {
			$cat_name = get_course_name_plural($course, $category);
			$cat_ident = "Grade Category: {$cat_name}";
			$cat_names[] = get_course_name_plural($course, $category);
			
			// if (!$course_is_assigned AND $chosen_session == 2) {
				// $cat_text = "<p>You don't have access to this course for Session {$chosen_session}</p>";
			// }
			// else {
				
				$target_url_add = "assignments_04.php?session={$chosen_session}&course={$course}&category={$category}";
				$button_add_for_cat = get_category_add_button($target_url_add, $category, $course);
				$cat_table = get_category_table($course, $acad_year, $chosen_session, $category, $target_url_input_grade, $students);
				$cat_text = $button_add_for_cat.$cat_table;
			// }
			
			$categories_text[] = $cat_ident."<br><br>".$cat_text;
			$content .= $cat_ident."<br><br>".$cat_text."<pagebreak />";
			
		}
		
		
		$cat_ident = "Grade Category: Overall";
		// if (!$course_is_assigned AND $chosen_session == 2) {
			// $overall_text = $cat_ident."<br><br><p>You don't have access to this course for Session {$chosen_session}</p>";
		// }
		// else {
			$overall_table = get_overall_grade_table($course, $acad_year, $chosen_session, $categories, $students);
			$overall_text = $cat_ident."<br><br>".$overall_table;
			$content .= $overall_text;
			$pdf_button = get_pdf_form_button_landscape($content, "../css/pdf_style.css", "{$course}_grades_session{$chosen_session}-{$acad_year}.pdf", "download", "Download Grades for {$course}");
		// }
	?>
	<script type="text/javascript">
	function toggle_category() {
		var x = "" + document.getElementById("category_select").value;
		var category1_text = "<?php echo $categories_text[0];?>";
		var category2_text = "<?php echo $categories_text[1];?>";
		var category3_text = "<?php echo $categories_text[2];?>";
		var overall_text = "<?php echo $overall_text;?>";
		
		if (x == "1") {
			echo_string = category1_text;
		}
		else if (x == "2") {
			echo_string = category2_text;
		}
		else if (x == "3") {
			echo_string = category3_text;
		}
		else if (x == "4") {
			echo_string = overall_text;
		}
		else {
			echo_string = "Please select a grading category from the pulldown menu.";
		}

		document.getElementById("change_category").innerHTML = echo_string;
	}
	</script>
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

		Change grade category:
		<select id="category_select" onchange="toggle_category()">

		  <option selected value="">...</option>
		  <option value="1"><?php echo $cat_names[0];?></option>
		  <option value="2"><?php echo $cat_names[1];?></option>
		  <option value="3"><?php echo $cat_names[2];?></option>
		  <option value="4">Overall</option>
		</select>
		<br><br>


		<div id="change_category">
			<?php 
				echo $overall_text;
			?>
		</div>
		<?php 
			echo $pdf_button;
		?>

		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    