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
		
		
		$acad_year = $_POST['year'];
		//$acad_session = $_POST['session'];
		$acad_term = $_POST['term'];
		$acad_session = 1;
		$student_id = trim($_POST['student_id']);
		$term_session_option = $_POST['term_option'];
		
		if ($term_session_option == 'mid') {
			$report_card = get_report_card_mid($acad_year, $acad_term, $student_id);
		}
		else {
			$report_card = get_report_card_term($acad_year, $acad_term, $student_id);
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
			echo $report_card;
		?>
		
	




	
</body>   
</html>    
