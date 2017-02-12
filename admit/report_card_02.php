<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<link rel=stylesheet href=../css/pdf_style.css>
	<style>
		* {
			font-size: 24px;
		}
		h4 {
			font-weight: bold;
			font-size: 24px;
			margin-bottom: 5px;
		}
		ul, li {
			margin-left: 30px;
			margin-right: 30px;
		}
		
		.info-table {
			border: 0 white;
		}
		
		h2 {
			font-size: 36px;
			margin-top: 0px;
			margin-bottom: 5px;
		}
	</style>
	
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/teach_schedule.php");
		include_once("../php/report_cards.php");
		include_once("../php/classlist.php");
		include_once("../php/get_name.php");
		include_once("../php/course_info.php");
		include_once("../php/pdf_wrapper.php");
		
		
		$acad_year = $_POST['year'];
		//$acad_session = $_POST['session'];
		$acad_term = $_POST['term'];
		$acad_session = get_session1($acad_year, $acad_term);
		// $student_id = trim($_POST['student_id']);
		$term_session_option = $_POST['term_option'];
		$students = get_stu_by_status_term('active', $acad_term, $acad_year);
		$path = "../records/report_cards/";
		$css_file = "../css/pdf_style.css";
		
		if ($term_session_option == 'mid') {
			foreach ($students as $student_id) {
				$student_name = str_replace(" ", "", get_student_name($student_id));
				$report_card = get_report_card_mid($acad_year, $acad_term, $student_id);
				// $report_card = "hello";
				//{$acad_year}_Term{$acad_term}_Session{$acad_session}";
				$filename = "{$path}{$student_id}-{$student_name}-reportcard.pdf";
				server_download_pdf($report_card, $css_file, $filename);
			}
		}
		else {
			foreach ($students as $student_id) {
				$student_name = str_replace(" ", "", get_student_name($student_id));
				$report_card = get_report_card_term($acad_year, $acad_term, $student_id);
				// $report_card = "hello";
				$filename = "{$path}{$student_id}-{$student_name}-reportcard.pdf";
				server_download_pdf($report_card, $css_file, $filename);
			}
		}
		// $pdf_option = $_POST['pdf_option'];
		
		
		// $report_card = get_report_card_term($acad_year, $acad_term, $student_id);
		
		// if ($pdf_option == 'pdf') {
			// $filename = "Report-Card-{student_id}.pdf";
			// $css_filepath = "../css/pdf_style.css";
			// user_download_pdf($report_card, $css_filepath, $filename);
		// }
		
		
		// $target_url = "report_card_01.php";
		// $form = get_grade_report_form($target_url);
		
	?>
</head>
<body>
	

	
	
	
	
		
		
		<?php
			echo "Success. check the server for reports";
		?>
		
	




	
</body>   
</html>    
