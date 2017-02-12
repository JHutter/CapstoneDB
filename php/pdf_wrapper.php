<?php
	include('../mpdf/mpdf.php');
	
	function user_download_pdf($html, $css_file, $filename) {
		$mpdf = new mPDF();
		$stylesheet = file_get_contents($css_file);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);
		//$mpdf->WriteHTML($html);
		ob_clean();
		$mpdf->Output($filename, "D");

	}
	
	function user_download_pdf_landscape($html, $css_file, $filename) {
		$mpdf = new mPDF();
		$mpdf->AddPage('L');
		$stylesheet = file_get_contents($css_file);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);
		//$mpdf->WriteHTML($html);
		ob_clean();
		$mpdf->Output($filename, "D");

	}
	
function server_download_pdf($html, $css_file, $filename) {
	$mpdf = new mPDF();
	// $mpdf->cacheTables = true;
	// $mpdf->simpleTables=true;
	// $mpdf->packTableData=true;
	$stylesheet = file_get_contents($css_file);
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($html,2);
	//$mpdf->WriteHTML($html);
	ob_clean();
	$mpdf->Output($filename, "F");

}

?>