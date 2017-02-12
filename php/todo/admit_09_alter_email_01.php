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

// Class Student_info{
	// public $stu_name;
	// public $stu_address;
	// public $stu_phone1;
	// public $stu_phone2;
	// public $stu_email1;
	// public $stu_email2;
// }

// stu_info = new student
// $sql_contact = "SELECT address, phone1, phone2, email1, email2 FROM STUDENTCONTACT WHERE student_id = {$stu_id}";
// foreach ($pdo->query($sql_stu_name) as $row) {
	// $stu_name = $row['SName'];
// }
 ?>
<Form action="admit_04_alter_contact_02.php">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Edit Student Contact Form</td></tr><tr bgcolor="#ffffff">
<td align=right width=15%>Student Name</td><td><input type="Text" name="student_name" size=70 value="<?php echo $stu_name; ?>"></td></tr><tr bgcolor="#ffffff">
<td align=right>US Address</td><td><input type="Text" name="us_address" size=70 value="1234 5th Ave"></td></tr><tr bgcolor="#ffffff">
<td align=right>US City State Zip</td><td><input type="Text" name="us_city_state_zip" size=70 value="Portland Oregon 97201 USA"></td></tr><tr bgcolor="#ffffff">
<td align=right>US Cellphone</td><td><input type="Text" name="us_cell" size=70 value="555-444-7777"></td></tr><tr bgcolor="#ffffff">
<td align=right>US Landline</td><td><input type="Text" name="us_phone" size=70 value="777-888-9999"></td></tr><tr bgcolor="#ffffff">
<td align=right>US Contact 1</td><td><input type="Text" name="us_contact_1" size=70 value="James T. Kirk 222-333-4444"></td></tr><tr bgcolor="#ffffff">
<td align=right>US Contact 2</td><td><input type="Text" name="us_contact_2" size=70 value="Wilfred Brimley 999-888-7777"></td></tr><tr bgcolor="#ffffff">
<td></td></tr></table> 
<input value="Submit Changes" type="submit"></input>
</form><br>