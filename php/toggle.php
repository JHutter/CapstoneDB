<?php

// no longer in use, may delete

$session_select = "Change session:";
$session_select .= "<select id='session_select' onchange='toggle_session()'>";
$session_select .= "<option selected value=''>...</option>";				
$session_select .= "<option value='1'>{$sessions[0]}</option>";
$session_select .= "<option value='2'>{$sessions[1]}</option>";
$session_select .= "</select><br><br>";

$session_select_div = "<div id=change_session>{$sessions_text[$acad_session-1]}</div>";




$category_select = "Change grading category:";
$category_select .= "<select id='session_select' onchange='toggle_session()'>";
$category_select .= "<option selected value=''>...</option>";				
$category_select .= "<option value='1'>{$cat_names[0]}</option>";
$category_select .= "<option value='2'>{$cat_names[1]}</option>";
$category_select .= "<option value='3'>{$cat_names[2]}</option>";
$category_select .= "</select><br><br>";
			
?>