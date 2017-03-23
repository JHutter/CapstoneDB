<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	
	<?php
		include_once("../php/teach_session.php");
		include_once("../php/attn.php");
		include_once("../php/course_list.php");
		include_once("../php/both_sessions.php");
		include_once("../php/pdf_button.php");
		include_once("../php/classlist.php");
		
		// vars for attn
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_SESSION['session'];
		$chosen_session = $_GET['session'];
		$acad_term = $_SESSION['term'];
		$course = $_GET['course'];
		$take_attn_url = "attendance_02.php?course=";
		$acad_session_index = (($acad_session % 2) == 1 ? 0 : 1);
		
		$sessions = get_session_array($acad_term);
		$sessions_text = [];
		$css_filepath = '../css/pdf_style.css';
		$date = '03/18';//date('Md');
		// $ses1 = 1;
		// $ses2 = 2;
		
		

		
		foreach ($sessions as $session) {
			$course_is_assigned = course_is_assigned($teacher, $course, $acad_year, $session);
			// $course_is_assigned_ses1 = course_is_assigned($teacher, $course, $acad_year, $ses1);
			$session_ident = "Session ".$session;
			
			if (!$course_is_assigned AND $session == 2) {
				$sessions_text[] = $session_ident."<p>You don't have access to this course for Session {$session}</p>";
			}
			else {
				$session_table = get_class_attn_table($course, $acad_year, $session);
				$session_grades = get_class_attn_grades($course, $acad_year, $session);
				$blank_attn_table = get_stu_list_attn($course, $acad_year, $session);
				
				if ($session != $acad_session) {
					$take_attn_link = "<br><br>";
				}
				else {
					$take_attn_link = take_attn_link($course, $take_attn_url);
				}
				
				$filename_attn = "{$course}-Session{$session}-{$acad_year}-attendance.pdf";
				$filename_attn_grades = "{$course}-Session{$session}-{$acad_year}-attendance-grades.pdf";
				$filename_blank_attn = "{$course}-attendance-{$date}.pdf";
				
				$attn_button = get_pdf_form_button_landscape($session_table, $css_filepath, $filename_attn, 'download', "Download Attendance Record for {$session_ident}");
				$attn_grade_button = get_pdf_form_button_portrait($session_grades, $css_filepath, $filename_attn_grades, 'download', "Download Attendance Grades for {$session_ident}");
				$attn_blank_button = get_pdf_form_button_portrait($blank_attn_table, $css_filepath, $filename_blank_attn, 'download', "Download Blank Attendance Form for {$session_ident}");
				$button_block = "<br><br>{$attn_blank_button}<br><br>{$attn_button}<br><br>{$attn_grade_button}"; 
				
				$sessions_text[] = "{$session_ident}<br><br>{$take_attn_link}<br>{$session_table}<br><br>{$session_grades}{$button_block}{$attn_message}";
			}	
		}
	?>
	<script type="text/javascript">
		function toggle_session() {
			var x = "" + document.getElementById("session_select").value;
			var session1_text = "<?php echo $sessions_text[0];?>";
			var session2_text = "<?php echo $sessions_text[1];?>";
			
			if (x == "1") {
				echo_string = session1_text;
			}
			else if (x == "2") {
				echo_string = session2_text;
			}
			else {
				echo_string = "Please select a session from the pulldown menu.";
			}

			document.getElementById("change_session").innerHTML = echo_string;
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
		<H3>Attendance</H3>
		
		Change session:
		<select id="session_select" onchange="toggle_session()">

		  <option selected value="">...</option>
		  <option value="1"><?php echo $sessions[0];?></option>
		  <option value="2"><?php echo $sessions[1];?></option>
		</select>
		<br><br>


		<div id="change_session">
			<?php 
				echo $sessions_text[$acad_session_index];
			?>
		</div>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    