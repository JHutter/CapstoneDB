<?php
	include_once('database.php');
	include_once('get_name.php');


	function get_teacher_schedule($teacher, $acad_year, $acad_session) {
		$pdo = Database::connect();
		$sql = "SELECT 	CONCAT(courseassignment.course_id, ':  ',  course.skill, ' ', course.sk_level) AS 'Course',
				course_type.course_time AS 'Course Time',
				room
				FROM courseassignment
				left join course on courseassignment.course_id = course.course_id
				left join course_type on course.course_type = course_type.course_type
				WHERE teacher_id = '{$teacher}' 
				AND acad_year = {$acad_year} 
				AND acad_session = {$acad_session}
				ORDER BY course_type.course_order";

		$table = "<table><thead><tr><th>Course</th><th>Course Time</th><th>Room</th></tr></thead><tbody>";
		
		foreach ($pdo->query($sql) as $row_course) {
			$course = $row_course['Course'];
			$time = $row_course['Course Time'];
			$room = $row_course['room'];
			
			$table .= "<tr><td>{$course}</td><td>{$time}</td><td>{$room}</td></tr>";
		}
		$table .= "</tbody></table>";
		Database::disconnect();
				
		return $table;
	}
	
	function get_active_teacher_links($acad_year, $acad_session, $base_url) {
		$pdo = Database::connect();
		$sql = "SELECT 	distinct courseassignment.teacher_id as 'teacher_id'
				FROM courseassignment 
				left join teacher on courseassignment.teacher_id = teacher.teacher_id
				WHERE acad_year = {$acad_year} 
				AND acad_session = {$acad_session}
				ORDER BY teacher_last";
		//return $sql;
		$links = "";
		foreach ($pdo->query($sql) as $row_teacher) {
			$teacher = $row_teacher['teacher_id'];
			$teacher_name = get_teacher_name($teacher);
			$links .= "<a href='{$base_url}{$teacher}' class='button'>     {$teacher_name}     </a><br><br>";
		}
		Database::disconnect();
		return $links;
	}


?>