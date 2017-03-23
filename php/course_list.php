<?php
	include_once 'database.php';
	
	function get_link_courses($teacher, $acad_year, $acad_session, $base_url) {
		// connect to db
		$pdo = Database::connect();
		$link_list = "";
		
		$sql_courses = "SELECT 	courseassignment.course_id AS 'course_id',
						CONCAT(course.skill, ' ', course.sk_level) AS 'course_name'
						FROM courseassignment
						left join course on courseassignment.course_id = course.course_id
						WHERE teacher_id = '{$teacher}' 
						AND acad_year = {$acad_year} 
						AND acad_session = {$acad_session}";
		
		foreach ($pdo->query($sql_courses) as $row_course) {
			$course = $row_course['course_id'];
			$course_name = $row_course['course_name'];
			$link_list .= "<a href='{$base_url}{$course}' class='button'>     {$course_name}     </a><br><br>";
		}
		
		Database::disconnect();
		return $link_list;
	}
	
	function course_is_assigned($teacher, $course, $acad_year, $acad_session) {
		$pdo = Database::connect();
		
		$sql_get_course_yn = "select * from courseassignment
							where acad_year = {$acad_year}
							and acad_session = {$acad_session}
							and teacher_id = '{$teacher}'
							and course_id = '{$course}'";
		$result = $pdo->prepare($sql_get_course_yn); 
		$result->execute(); 
		
		if ($result->rowCount() == 0) {
			$is_assigned = false;
		}
		else {
			$is_assigned = true;
		}
		// $is_assigned = false;
		
		Database::disconnect();
		return $is_assigned;
	}


?>