 <?php
 //todo: make array of info from studentcontact to put in default text boxes
 //todo: write sql exec statement to alter table where student_id = stu_id
 include('../11/database.php');
 $stu_id = trim($_GET['stu_id']);
 $sql_stu_name = "SELECT CONCAT(stu_first, ' ', stu_last) AS 'SName' FROM STUDENTINFO WHERE student_id = '{$stu_id}'";
$pdo = Database::connect();
foreach ($pdo->query($sql_stu_name) as $row) {
	$stu_name = $row['SName'];
}

$sql_contact_id = "select stu_email1 from STUDENTCONTACT where student_id = '{$stu_id}'";
foreach ($pdo->query($sql_contact) as $row) {
	$stu_email = $row['stu_email1'];
}
//if (empty($stu_email)){
//	$stu_email = "no email currently set";
//}
 ?>
<Form action="../99/admit_08_alter_email_02.php" method="GET">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Edit Student Contact Form</td></tr><tr bgcolor="#ffffff">
<td align=right width=15%>Student Name</td><td><input type="Text" name="student_name" size=70 value="<?php echo $stu_name; ?>"></td></tr><tr bgcolor="#ffffff">
<td align=right width=15%>Student ID</td><td><input type="Text" name="stu_id" size=70 value="<?php echo $stu_id; ?>"></td></tr><tr bgcolor="#ffffff">
<td align=right>Email Address</td><td><input type="Text" name="email" size=70 value="<?php echo $stu_email; ?>"></td></tr><tr bgcolor="#ffffff">
<td></td></tr></table> 
<input value="Submit Changes" type="submit"></input>
</form><br>