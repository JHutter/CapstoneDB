<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/teach_session.php");
		include("../php/classlist.php");
		include("../php/pdf_button.php");
		
		$teacher = $_SESSION['user_id'];
		$acad_year = $_SESSION['year'];
		$acad_session = $_GET['session'];	
		$course = $_GET['course'];
		// $css_filepath = '../css/pdf_style.css';
		// $filename = "{$course}-Session{$acad_session}-{$acad_year}-classlist.pdf";
		// $date = date('M-d');
		// $filename_attn = "{$course}-attn-{$date}.pdf";
		
		$table = get_email_list($course, $acad_year, $acad_session);
		$table_block = get_email_block($course, $acad_year, $acad_session);
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
		<H3>Class Lists</H3>
		
		<?php
			echo $table;
			
			echo "<br>";
			
			echo $table_block;
			/*$table = get_stu_list($course, $acad_year, $acad_session);
			
			echo $table;
			
			$table_printable = get_stu_list_attn($course, $acad_year, $acad_session);
			
			//echo $table_printable;
			echo '<br><br>'; 
			echo get_pdf_form_button_portrait($table, $css_filepath, $filename, 'download', 'Download Printer Friendly Class List');
			
			echo '<br><br>'; 
			echo get_pdf_form_button_portrait($table_printable, $css_filepath, $filename, 'download', 'Download Printer Friendly Class List for Attendance');*/
			
		?>
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    