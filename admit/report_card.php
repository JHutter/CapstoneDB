<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include_once("../php/admit_session.php");
		include_once("../php/teach_schedule.php");
		include_once("../php/report_cards.php");
		
		$target_url = "report_card_01.php";
		$form = get_report_card_form($target_url);
		
		$target_url = "report_card_02.php";
		$form2 = get_report_card_form_batch($target_url)
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/admit_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<H3>Student Report</H3>
		
		<?php
			echo $form;
			echo "<br>";
			echo $form2;
		?>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
