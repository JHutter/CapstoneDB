<?php
include_once('database.php');
include_once('write_wrapper.php');
include_once('get_name.php');
include_once('forms.php');

function get_contact_form($target_url, $students) {
	$form = "<Form action={$target_url} method=post>";
	$form .= "<table><thead><tr><th colspan=2>Change Email</th></tr>";
	$form .= "<tr><td>Student:</td><td><input list=student name=student><datalist id=student>";
	$form .= get_stu_datalist($students);
	$form .= "</datalist></td></tr>";
	
	$form .= "<tr><td align=left>New Email:</td>";
	$form .= "<td>";
	$form .= "<input type=text name=email>";
	$form .= "</td></tr>";
	
	$form .= "</table><input value='Update Email' type=submit class=button></form>";
	
	return $form;
}

function change_stu_email($student, $email) {
	$pdo = Database::connect();
	$name = get_student_name($student);
	$date = date('m/d/y');
	
	$sql_alter_email = "update studentcontact set stu_email1 = '{$email}' where student_id = '{$student}'";
	$pdo->exec($sql_alter_email);
	$message = "Email address changed for {$name}, ID {$student} on {$date}";
	
	
	$path = "../logs/contact/";
	$filename = $path."{$student}_email.sql";
	$content = "#".$message."\n".$sql_alter_email.";\n\n";
	$option = 'a';
	write_to_file($filename, $option, $content);
	
	Database::disconnect();
	$pdo = null;
	return $message;
}


?>