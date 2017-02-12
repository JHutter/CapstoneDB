<!DOCTYPE html>
<head>
	<title>
		CapstoneDB
	</title>
	<?php
		include('mpdf/mpdf.php');
		include('../php/pdf_wrapper.php');
		$option = $_POST['option'];
		$table = $_POST['table'];
		$css_filepath = $_POST['css_filepath'];
		$filename = $_POST['filename'];
		$page = $_POST['page'];
		
		if ($option == 'download') {
			if ($page == 'landscape') {
				user_download_pdf_landscape($table, $css_filepath, $filename);
			}
			else {
				user_download_pdf($table, $css_filepath, $filename);
			}
			
		}
		else if ($option == 'server') {
			// do another thing
			// todo add server dl option (someday add in zip ability as another option)
		}
	?>
	<script>
	window.close();
	</script>
</head>

<body>
</body>
</html>