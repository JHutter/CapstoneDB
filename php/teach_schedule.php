<?php
	include_once('database.php');
	include_once('get_name.php');


	function get_teacher_schedule($teacher, $acad_year, $acad_session) {
		$pdo = Database::connect();
		$sql = "SELECT 	CONCAT(COURSEASSIGNMENT.course_id, ':  ',  COURSE.skill, ' ', COURSE.sk_level) AS 'Course',
				COURSE_TYPE.course_time AS 'Course Time',
				room
				FROM COURSEASSIGNMENT 
				left join COURSE on COURSEASSIGNMENT.course_id = COURSE.course_id
				left join COURSE_TYPE on COURSE.course_type = COURSE_TYPE.course_type
				WHERE teacher_id = '{$teacher}' 
				AND acad_year = {$acad_year} 
				AND acad_session = {$acad_session}
				ORDER BY COURSE_TYPE.course_order";

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
				FROM COURSEASSIGNMENT 
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