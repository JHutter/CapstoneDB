<!--- Begin body --->  
<?php 
include('../11/admit_session.php'); ?>

<Form action="../11/admit_07_noteacher_noroom.php" method = "get">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Student Schedule, manually add room/teacher</td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student ID</td><td><input type="Text" name="student_id" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right>Year</td><td><p><select name="year">
	<option value="">Select...</option>
	<option value="2016">2016</option>
	<option value="2017">2017</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Session</td><td><p><select name="session">
	<option value="">Select...</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	</select></p></td></tr><tr bgcolor="#ffffff">
	

</table> 
<input value="Submit Form" type="submit"> 
</form><br>
<br>

<Form action="../11/admit_07_teacher_room.php" method = "get">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Student Schedule, automatically add room/teacher</td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student ID</td><td><input type="Text" name="student_id" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right>Year</td><td><p><select name="year">
	<option value="">Select...</option>
	<option value="2016">2016</option>
	<option value="2017">2017</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Session</td><td><p><select name="session">
	<option value="">Select...</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	</select></p></td></tr><tr bgcolor="#ffffff">
	

</table> 
<input value="Submit Form" type="submit"> 
</form><br>
<br>