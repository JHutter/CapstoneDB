
 <!-- Begin Body -->  
<Form action="../99/admit_11_01.php" action="GET" target="_blank">
<table width="700" bgcolor="#aaaaff" cellspacing=1 cellpadding="2">

<tr bgcolor="#efefFF">
<td align=center colspan=2>Attendance Report, Step 1</td></tr>

<tr bgcolor="#ffffff">
<td align=right width=15%>Attendance Benchmark</td><td><input required type="number" name="benchmark" size=70 step="1" min="0" max="100">%</td></tr>

<tr bgcolor="#ffffff">
<td align=right width=15%>Year/Session</td>
<td>
<select required name="acad_year">
<option value="">Select...</option>
<option value="2016">2016</option>
<option value="2017">2017</option>
<option value="2017">2018</option></select>

<select required name="acad_session">
<option value="">Select...</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option></select>

</td></tr>


</table> 
<input value="Generate attendance report" type="submit"></input>
</form><br><br>
Instructions: Enter the attendance percentage to search for in the<br>
attendance benchmark box. This number is the absence rate. For example,<br>
to find all students with greater than 20% absence in the session so far,<br>
enter 20 in the box (in other words, if you enter 20, 80% attendance is ok, <br>
and the report will identify 79% attendance and below.)
<!-- End Body --> 