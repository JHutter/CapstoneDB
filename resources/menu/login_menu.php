<?php

function get_login_menu($location) {
	$admit = '<a href="../admit/admit_login.php" class="button">     Admissions Login     </a>';
	$teach = '<a href="../teach/teach_login.php" class="button">     Teacher Login     </a>';
	$admin = '<a href="../admin/admin_login.php" class="button">     Administration Login     </a>';
	
	$index = '<a href="admit/admit_login.php" class="button">     Admissions Login     </a>
				<a href="teach/teach_login.php" class="button">     Teacher Login     </a>
				<a href="admin/admin_login.php" class="button">     Administration Login     </a>';
	
	
	
	switch ($location) {
		case 'teach':
			$echo_string = $admit.$admin;
			break;
		case 'admit':
			$echo_string = $teach.$admin;
			break;
		case 'admin':
			$echo_string = $teach.$admit;
			break;
		default:
			$echo_string = $index;
			break;
	}
	
	return $echo_string;
}
?>