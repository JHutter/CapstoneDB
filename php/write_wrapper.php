<?php

function write_to_file($filename, $option, $content) {
	$backup_file = fopen($filename, $option);
	fwrite($backup_file, $content);
	fclose($backup_file);
	
	return;
}
?>