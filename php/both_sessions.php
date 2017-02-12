<?php

function get_session_array($acad_term) {
	switch ($acad_term) {
		case 1:
			$sessions = array(1, 2);
			break;
		case 2:
			$sessions = array(3, 4);
			break;
		case 3:
			$sessions = array(5, 6);
			break;
		case 4:
			$sessions = array(7, 8);
			break;
		default:
			$sessions = array(7, 8);
			// based on db design, control shouldn't get here
	}
	return $sessions;
			
}
?>