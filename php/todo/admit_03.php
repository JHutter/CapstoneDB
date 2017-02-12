 
 <!-- Begin Body -->  

<Form action="../11/admit_new_student.php" method = "get">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2"><tr bgcolor="#efefFF">
<td align=center colspan=2>Form: Add new student</td></tr><tr bgcolor="#ffffff">
<td align=right width=25%>Student ID</td><td><input required type="Text" name="student_id" size=70></td></tr><tr bgcolor="#ffffff">
<td align=right>First Name</td><td><input required type="Text" name="stu_first" size=70></td></tr><tr bgcolor="#ffffff">
<td align=right>Last Name</td><td><input required type="Text" name="stu_last" size=70></td></tr><tr bgcolor="#ffffff">

<td align=right>Course 1 (10am)</td><td><p><select name="10am">
	<option value="">Select...</option>
	<option value="SL1A-1">1A Speaking / Listening</option>
	<option value="SL1B-1">1B Speaking / Listening</option>
	<option value="SL2A-1">2A Speaking / Listening</option>
	<option value="SL2B-1">2B Speaking / Listening</option>
	<option value="SL3A-1">3A Speaking / Listening</option>
	<option value="SL3B-1">3B Speaking / Listening</option>
	<option value="none">none</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Course 2 (11am)</td><td><p><select name="11am">
	<option value="">Select...</option>
	<option value="RW1A-1">1A Reading / Writing</option>
	<option value="RW1B-1">1B Reading / Writing</option>
	<option value="RW2A-1">2A Reading / Writing</option>
	<option value="RW2B-1">2B Reading / Writing</option>
	<option value="RW3A-1">3A Reading / Writing</option>
	<option value="RW3B-1">3B Reading / Writing</option>
	<option value="none">none</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Course 3 (mwf 1pm)</td><td><p><select name="mwfpm">
	<option value="">Select...</option>
	<option value="GR1A-1">1A Grammar</option>
	<option value="GR1B-1">1B Grammar</option>
	<option value="GR2A-1">2A Grammar</option>
	<option value="GR2B-1">2B Grammar</option>
	<option value="GR3A-1">3A Grammar</option>
	<option value="GR3B-1">3B Grammar</option>
	<option value="none">none</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Course 4 (tth 1pm)</td><td><p><select name="tthpm">
	<option value="">Select...</option>
	<option value="LF1A-1">1A Life Skills</option>
	<option value="LF1B-1">1B Life Skills</option>
	<option value="VC2A-1">2A Vocabulary</option>
	<option value="VC2B-1">2B Vocabulary</option>
	<option value="VC3A-1">3A Vocabulary</option>
	<option value="VC3B-1">3B Vocabulary</option>
	<option value="TOEFL-1">TOEFL</option>
	<option value="none">none</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Start Year</td><td><p><select required name="year">
	<option value="">Select...</option>
	<option value="2016">2016</option>
	<option value="2017">2017</option>
	</select></p></td></tr><tr bgcolor="#ffffff">

<td align=right>Start Session</td><td><p><select required name="session">
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



<!-- End Body --> 