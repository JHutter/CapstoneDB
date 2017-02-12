<?php
// function get_login_widget($user_name) {
	// $widget_text = "<strong>Term info: </strong>";
	// $widget_text .= "Session ".$_SESSION['session'].", Term ";
	// $widget_text .= $_SESSION['term'];
	// $widget_text .= "<strong>Logged in as: </strong>".$user_name;
	
	// return $widget_text;
// }

function get_login_widget_div($user_name) {
	$date = Date('l, F d');
	
	$widget_text = "<div id='widget'><strong>Term: </strong>";
	$widget_text .= "Term {$_SESSION['term']}, Session {$_SESSION['session']}";
	$widget_text .= "<br><strong>User: </strong>{$user_name}";
	$widget_text .= "<br><strong>Date: </strong>{$date}";
	
	$widget_text .= "<br><Br><div style='text-align: center; margin-left:0px; margin-right:15px;'><a href=../php/logout.php class=button style=min-width:100%;>    Logout     </a></div> ";
	$widget_text .= "</div>";
	
	return $widget_text;
}

?>