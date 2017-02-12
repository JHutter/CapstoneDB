<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
		<?php
		include_once("../php/teach_session.php");
		include_once("../php/forms.php");
		// include_once("../php/grade_forms.php");
		
		function get_student_grade_form($target_url, $students_datalist) {
			$terms = [1,2,3,4];
			$form = "<Form action={$target_url} method=get target=_blank>";
			$form .= "<table>";
			$form .= "<thead><tr><th align=center colspan=2>Lookup Grades by Student</th></tr></thead>";
			$form .= "<tbody>";
			
			$form .= "<tr><td>Student:</td><td><input list=student name=student><datalist id=student>";
			$form .= get_stu_datalist($students_datalist);
			$form .= "</datalist></td></tr>";
			
			$form .= "<tr><td>Course: </td><td>";
			$form .= get_course_type_select('course_type');
			$form .= "</td></tr>";
			
			$form .= "<tr><td>Term: </td><td>";
			$form .= get_session_select('term', $terms);
			$form .= "</td></tr>";
			
			$form .= "<tr><td>Year: </td><td>";
			$form .= get_year_input('year');
			$form .= "</td></tr>";
			
			
			$form .= "</table><input value='Lookup Grades' type=submit class=button></form>";
			// $form = 'foobar';
			return $form;
		}
		
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$students = get_all_stus();
		
		$target_url = "grades_01.php";
		$form = get_student_grade_form($target_url, $students);
		
		

	?>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/teach_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<?php
			echo $form;

		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
