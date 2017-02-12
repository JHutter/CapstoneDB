 <?php
 include('../11/database.php');
 $stu_id = trim($_GET['stu_id']);
 $new_email = trim($_GET['email']);
$pdo = Database::connect();

$sql_check_contact = "SELECT student_id from STUDENTCONTACT where student_id = {$stu_id}";
foreach ($pdo->query($sql_check_contact) as $row) {
	$stu_id2 = $row['student_id'];
}
if ($stu_id2 != $stu_id){ //ie, it's null or empty
	//add
	$sql_add_stu_to_contact = "INSERT INTO STUDENTCONTACT (student_id, stu_email1)
							VALUES ('{$stu_id}', '{$new_email}')";
	$pdo->exec($sql_add_stu_to_contact);
    echo "<br>New student contact record created successfully for {$stu_id}, email {$new_email}<br>";
	
	//write to backup folder
	$student_backup_file = fopen("..\logs\studentcontact.sql", "a") or die("Unable to write to backup file t add student!");
	$txt = "\n".$sql_add_stu_to_contact.";\n\n";
	fwrite($student_backup_file, $txt);
	fclose($student_backup_file);
}
else {
	//update
	$sql_alter_email = "update STUDENTCONTACT set stu_email1 = '{$stu_email}' where student_id = '{$stu_id}'";
	$pdo->exec($sql_alter_email);
    echo "<br>Email updated successfully for {$stu_id}, email {$new_email}<br>";
	
	//write to backup folder
	$student_backup_file = fopen("..\logs\studentcontact.sql", "a") or die("Unable to write to backup file t add student!");
	$txt = "\n".$sql_alter_email.";\n\n";
	fwrite($student_backup_file, $txt);
	fclose($student_backup_file);
}

Database::disconnect();
 ?>