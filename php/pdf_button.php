<?php 

	function get_pdf_form_button_portrait($table, $css_filepath, $filename, $option, $button_name) {
		//$table = addslashes($table);
		$form = "<form action=../php/pdf_and_die.php method=POST target=_blank>";
		$form .= "<input type=hidden name=table value='{$table}'>";
		$form .= "<input type=hidden name=css_filepath value={$css_filepath}>";
		$form .= "<input type=hidden name=filename value={$filename}>";
		$form .= "<input type=hidden name=option value={$option}>";
		$form .= "<input type=hidden name=page value=portrait>";
		$form .= "<input type=submit value= '    {$button_name}     ' class=button></form>";
		
		
		// $form = '<form action=../php/pdf_and_die.php method=POST target=_blank >';
		// $form .= '<input type=hidden name=table value="'.$table.'"  >';
		// $form .= '<input type=hidden name=css_filepath value="'.$css_filepath.'"  >';
		// $form .= '<input type=hidden name=filename value="'.$filename.'" >';
		// $form .= '<input type=hidden name=option value="'.$option.'" >';
		// $form .= '<input type=hidden name=page value="portrait" >';
		// $form .= '<input type=submit value="     '.$button_name.'     " class="button"  ></form >';
		
		return $form;
	}
	
	function get_pdf_form_button_landscape($table, $css_filepath, $filename, $option, $button_name) {
		// $form = "<form action=../php/pdf_and_die.php method=POST target=_blank>";
		// $form .= "<input type=hidden name=table value={$table}>";
		// $form .= "<input type=hidden name=css_filepath value={$css_filepath}>";
		// $form .= "<input type=hidden name=filename value={$filename}>";
		// $form .= "<input type=hidden name=option value={$option}>";
		// $form .= "<input type=hidden name=page value=landscape>";
		// $form .= "<input type=submit value=     {$button_name}      class=button></form>";
		
		// $form = '<form action="../php/pdf_and_die.php" method="POST" target="_blank">';
		// $form .= '<input type="hidden" name="table" value="'.$table.'">';
		// $form .= '<input type="hidden" name="css_filepath" value="'.$css_filepath.'">';
		// $form .= '<input type="hidden" name="filename" value="'.$filename.'">';
		// $form .= '<input type="hidden" name="option" value="'.$option.'">';
		// $form .= '<input type="hidden" name="page" value="landscape">';
		// $form .= '<input type="submit" value="     '.$button_name.'     " class="button"></form>';
		
		$form = "<form action=../php/pdf_and_die.php method=POST target=_blank>";
		$form .= "<input type=hidden name=table value='{$table}'>";
		$form .= "<input type=hidden name=css_filepath value={$css_filepath}>";
		$form .= "<input type=hidden name=filename value={$filename}>";
		$form .= "<input type=hidden name=option value={$option}>";
		$form .= "<input type=hidden name=page value=landscape>";
		$form .= "<input type=submit value= '    {$button_name}     ' class=button></form>";
		
		return $form;
	}
	
	/*function get_pdf_form_button_portrait_innerhtml($table, $css_filepath, $filename, $option, $button_name) {
		// $form = "<form action=&quot../php/pdf_and_die.php&quot method=&quotPOST&quot target=&quot_blank&quot>";
		// $form .= "<input type=&quothidden&quot name=&quottable&quot value=&quot{$table}&quot>";
		// $form .= "<input type=&quothidden&quot name=&quotcss_filepath&quot value=&quot{$css_filepath}&quot>";
		// $form .= "<input type=&quothidden&quot name=&quotfilename&quot value=&quot{$filename}&quot>";
		// $form .= "<input type=&quothidden&quot name=&quotoption&quot value=&quot{$option}&quot>";
		// $form .= "<input type=&quothidden&quot name=&quotpage&quot value=&quotportrait&quot>";
		// $form .= "<input type=&quothidden&quot name=&quotinnerhtml&quot value=&quotyes&quot>";
		// $form .= "<input type=&quotsubmit&quot value=&quot{$button_name}     &quot class=&quotbutton&quot></form>";
		
		
		
		$form = "<form action='../php/pdf_and_die.php' method='POST' target='_blank'>";
		$form .= "<input type='hidden' name='table' value='{$table}'>";
		$form .= "<input type='hidden' name='css_filepath' value='{$css_filepath}'>";
		$form .= "<input type='hidden' name='filename' value='{$filename}'>";
		$form .= "<input type='hidden' name='option' value='{$option}'>";
		$form .= "<input type='hidden' name='page' value='portrait'>";
		$form .= "<input type='submit' value='     {$button_name}     ' class='button'></form>";
		
		return $form;
	}
	
	// this one doesn't work yet, weird table issues
	function get_pdf_form_button_landscape_innerhtml($table, $css_filepath, $filename, $option, $button_name) {
		$form = "<form action='../php/pdf_and_die.php' method='POST' target='_blank'>";
		$form .= "<input type='hidden' name='table' value='{$table}'>";
		$form .= "<input type='hidden' name='css_filepath' value='{$css_filepath}'>";
		$form .= "<input type='hidden' name='filename' value='{$filename}'>";
		$form .= "<input type='hidden' name='option' value='{$option}'>";
		$form .= "<input type='hidden' name='page' value='landscape'>";
		$form .= "<input type='submit' value='{$button_name}     ' class='button'></form>";
		
		return $form;
	}*/
	
?>
