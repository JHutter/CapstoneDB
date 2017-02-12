<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/teach_session.php");
		include("../php/course_list.php");
		include("../php/pdf_button.php");
		include("../php/both_sessions.php");
		
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_term = $_SESSION['term'];
		$acad_session = $_SESSION['session'];	
		$acad_session_index = (($acad_session % 2) == 1 ? 0 : 1);

		$sessions = get_session_array($acad_term);
		
		foreach ($sessions as $session) {
			$target_url = "assignments_01.php?session={$session}&course=";
			$courses = get_link_courses($teacher, $acad_year, $session, $target_url);
			
			$sessions_text[] = $courses;
		
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
		<H3>Assignments</H3>
		
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