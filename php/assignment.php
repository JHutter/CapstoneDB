<?php
include_once('database.php');
include_once('grade_category.php');

class Assignment{
	public $desc;
	public $points;
}

$categories = array('40a', '40b', '20');

function get_category_links($course, $target_url) {
	$categories = array('40a', '40b', '20');
	$links = "";
	
	foreach ($categories as $category) {
		$cat_name = get_course_name_plural($course, $category);
		$links .= "<a class=button href={$target_url}={$category}>Jump to {$cat_name}</a><br><br>";
	}
	return $links;
}

function get_assigned_table($course, $category, $acad_year, $acad_session, $target_url_edit, $target_url_delete) {
	$pdo = Database::connect();
	$cat_name = get_course_name_plural($course, $category);
	$cat_name_singular = get_course_name_singular($course, $category);
	
	$sql_get_prev_assn = "select distinct assignment, points
						from grade{$category}
						where course_id = '{$course}'
						and acad_year = {$acad_year}
						and acad_session = {$acad_session}
						order by assignment";
						
	$table = "<table><thead><tr><th colspan=5>Previously Assigned:</th></tr></thead>";
	$table .= "<tr><td class=bold>Description</td><td class=bold>Point Value</td><td class=bold colspan=3 align=center>Change Assignment</td></tr>";
	
	$cat_total_points = 0.0;
	foreach ($pdo->query($sql_get_prev_assn) as $row){
		$desc = $row['assignment'];
		// $url_desc = urlencode($desc);
		$points = $row['points'];
		$table .= "<tr><td>{$desc}</td><td>{$points}</td>";
		$table .= "<td><form action={$target_url_edit} method=post><input type=hidden name=assignment value='{$desc}' >";
		$table .= "<input type=hidden name=session value={$acad_session}>";
		$table .= "<input type=hidden name=course value={$course}>";
		$table .= "<input type=hidden name=category value={$category}>";
		$table .= "<input type=hidden name=old_pts value={$points}>";
		$table .= "<input type=submit value='Edit Points or Name' class=button></form></td>";
		
		$table .= "<td>";
		// $table .= "<form action={$target_url_delete} method=post target=_blank><input type=hidden name=assignment value='{$desc}' >";
		// $table .= "<input type=hidden name=session value={$acad_session}>";
		// $table .= "<input type=hidden name=course value={$course}>";
		// $table .= "<input type=hidden name=category value={$category}>";
		// $table .= "<input type=hidden name=old_pts value={$points}>";
		// $table .= "<input type=submit value='Delete {$cat_name_singular}' class=button></form>";
		$table .= "</td>";
		
		// $table .= "<td><a class=button href={$target_url_delete}{$course}&session={$acad_session}&category={$category}&assignment={$desc}&option=delete>Don't Click Me</a></td>";
		$table .= "</tr>";
		$cat_total_points += $points;
	}
	$table .= "<tr><td colspan=5></td></tr><tr><td colspan=5 class=bold>Point Total: {$cat_total_points}</td></tr></table>";

	Database::disconnect();
	return $table;
}

function get_new_assignment_form($target_url, $course, $category, $acad_year, $acad_session) {
	$cat_name = get_course_name_singular($course, $category);
	$form = "<Form action={$target_url} method=post><table>";
	$form .= "<thead><tr><th colspan=2>Add New {$cat_name}</th></tr>";
	$form .= "<input type=hidden name=course value={$course}>";
	$form .= "<input type=hidden name=category value={$category}>";
	$form .= "<input type=hidden name=session value={$acad_session}>";
	$form .= "<input type=hidden name=year value={$acad_year}>";
	$form .= "<tr><td>{$cat_name} Description</td>";
	$form .= "<td><input required type=Text name=description></td></tr>";
	$form .= "<tr><td>Point Value</td>";
	$form .= "<td><input required type=number name=points size=70 value=100 min=0></td></tr></table>";
	$form .= "<input value='Add New {$cat_name}' type=submit class=button></form>";

	return $form;
}

function insert_assignment($course, $category, $acad_year, $acad_session, $description, $points) {
	$pdo = Database::connect();
	$filename = "../logs/grades/{$course}_grade{$category}_new.sql";
	$sql_get_stus = "select student_id from grade{$category}total
					where course_id = '{$course}'
					and acad_year = {$acad_year}
					and acad_session = {$acad_session}";

	foreach ($pdo->query($sql_get_stus) as $row_stus){
		$student_id = $row_stus['student_id'];
		$student_name = get_student_name($student_id);
		$sql_insert_new_assn = "insert into grade{$category} (course_id, acad_year, acad_session, student_id, assignment, points)
								VALUES ('{$course}', {$acad_year}, {$acad_session}, '{$student_id}', '{$description}', {$points})";
		
		//execute
		//write to backup
		$pdo->exec($sql_insert_new_assn) or die("Invalid entry. Please try again with a unique assignment name, without apostrophes or quote marks. Other special characters (& or +) are ok.");
					
		//write to backup folder
		$student_backup_file = fopen($filename, "a") or die("Unable to write to backup file add assignment!");
		$txt = "\n#New assignment added for {$student_name}, ID {$student_id}: {$description}, {$points} points\n".$sql_insert_new_assn.";\n\n";
		fwrite($student_backup_file, $txt);
		fclose($student_backup_file);
	}
	
	Database::disconnect();
	return;
}

function get_grade_table($course, $acad_year, $acad_session, $category) {
	$pdo = Database::connect();
	
	Database::disconnect();
	return $table;
}

function get_assignment_points($course, $acad_year, $acad_session, $category, $assignment) {
	$pdo = Database::connect();
	
	$sql_get_points = "select distinct points from grade{$category}
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						and assignment = '{$assignment}'";
						
	foreach ($pdo->query($sql_get_points) as $row_points) {
		$points = $row_points['points'];
	}
	
	Database::disconnect();
	return $points;
}

function get_change_points_desc_form($course, $acad_year, $acad_session, $category, $assignment, $target_url) {
	$cat_name = get_course_name_singular($course, $category);
	$old_points = get_assignment_points($course, $acad_year, $acad_session, $category, $assignment);
	
	$form = "<Form action={$target_url} method=post><table>";
	$form .= "<thead><tr><th colspan=2>Change {$cat_name}</th></tr></thead>";
	$form .= "<tr><td>{$cat_name} Name</td><td><input type=text required name=new_description value='{$assignment}'></td></tr>";
	$form .= "<tr><td>{$cat_name} Points</td><td><input type=number required name=new_points value={$old_points} min=0></td></tr>";
	$form .= "<input type=hidden name=course value={$course}>";
	$form .= "<input type=hidden name=year value={$acad_year}>";
	$form .= "<input type=hidden name=session value={$acad_session}>";
	$form .= "<input type=hidden name=category value={$category}>";
	$form .= "<input type=hidden name=assignment value='{$assignment}'>";
	$form .= "<input type=hidden name=option value=edit>";
	$form .= "</table><input value='Update {$cat_name}' type=submit class=button></form>";
	
	return $form;
}

function update_assignment($course, $acad_year, $acad_session, $category, $assignment, $filename, $new_description, $points) {
	$pdo = Database::connect();
	
	$sql_update_pts = "update grade{$category}
						set points = {$points}, assignment = '{$new_description}'
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						and assignment = '{$assignment}'";
	
	//execute
	$pdo->exec($sql_update_pts);
			
	//write to backup folder
	$student_backup_file = fopen($filename, "a");
	$txt = "\n".$sql_update_pts.";\n\n";
	fwrite($student_backup_file, $txt);
	fclose($student_backup_file);
	
	$message = "Success. Redirecting to previous page...";
	
	Database::disconnect();
	return $message;
}

?> 