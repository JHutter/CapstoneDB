<?php
//$session = $_SESSION['session'];
include("../11/database.php");
$pdo = Database::connect();
$order = $_GET['order'];

$sql_stus = "SELECT studentinfo.student_id,
					CONCAT(stu_first, ' ', stu_last) AS 'SName',
					stu_nick,
					stu_email1
			FROM STUDENTINFO
			left join studentcontact on studentinfo.student_id = studentcontact.student_id
			ORDER BY {$order}";

echo "Student list, sorted by first name<br><br>";
echo '<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#FFFFFF">';
echo '<td width="5%">&nbsp;</td><td width=10%>Student ID</td><td width=23%>Student Name (First, Last)</td><td>Email</td></tr>';

foreach ($pdo->query($sql_stus) as $row) {
		$stu_name = "{$row['SName']}";
		$stu_id = "{$row['student_id']}";
		$stu_email = $row['stu_email1'];
		echo '<tr bgcolor="#FFFFFF">';
		echo '<td></td>';
		echo "<td>{$stu_id}</td>";
		echo "<td>{$stu_name}</td>";
		echo "<td>{$stu_email}</td>";
		echo "</tr>";
}
echo "</table>";


Database::disconnect();

?>

<br><br>
Sort by:
<br><br>
<b id="sort_last_name"><a href="../99/admit_02_02.php?order=stu_last">Last name</a></b>

<br><br>
<b id="sort_first_name"><a href="../99/admit_02_02.php?order=stu_first">First name</a></b>

<br><br>
<b id="sort_id"><a href="../99/admit_02_02.php?order=student_id">Student ID</a></b>