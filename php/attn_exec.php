<?php
	include_once('database.php');
	include_once('write_wrapper.php');
	
	function exec_attn_class($course, $acad_year, $acad_session, $user_name, $current_date) {
		//$current_date = date('Ymd');
		$friendly_date = substr($current_date, 4,2)."/".substr($current_date, 6, 2)."/".substr($current_date, 0, 4);
		$message = "<br><br>Attendance taken for {$course}. Redirecting to previous page...";
		$pdo = Database::connect();
		
		$sql_get_stus = "SELECT COURSEENROLLMENT.student_id AS 'sID',
						CONCAT(stu_first, ' ', stu_last) AS 'SName'
						FROM COURSEENROLLMENT
						left join STUDENTINFO on COURSEENROLLMENT.student_id = STUDENTINFO.student_id
						where acad_year = {$acad_year}
						and acad_session = {$acad_session}
						and course_id = '{$course}'
						order by stu_last";

		foreach ($pdo->query($sql_get_stus) as $row_stus) {
			$name = $row_stus['SName'];
			$id = $row_stus['sID'].'';
			$filename = "../logs/attendance/{$id}-attendance.sql";
			$attn = $_GET[$id];
			
			if ($attn == 'blank' or $attn == '') {
				continue;
			}
			else {
				$sql_update_attn = "Update Attendance
									set attendance_yn = '{$attn}'
									where student_id = '{$id}'
									and acad_year = {$acad_year}
									and acad_session = {$acad_session}
									and course_id = '{$course}'
									and class_date = {$current_date}";
				
				//write to backup
				$pdo->exec($sql_update_attn);
							
				//write to backup folder
				$student_backup_file = fopen($filename, "a");
				$txt = "\n#{$user_name} entered '{$attn}' on {$friendly_date}\n{$sql_update_attn}\n\n";
				fwrite($student_backup_file, $txt);
				fclose($student_backup_file);
			}
		}
		Database::disconnect();	
		return $message;
	}
	
	function check_none($course, $class_date) {
		$pdo = Database::connect();
		$nones = 0;
		
		$sql_get_nones = "select attendance_yn
						from attendance
						where course_id = '{$course}'
						and class_date = {$class_date}";
					
		foreach ($pdo->query($sql_get_nones) as $row_nones) {
			$attendance = $row_nones['attendance_yn'];
			if ($attendance == 'none') {
				$nones++;
			}
		}
		
		Database::disconnect();
		return $nones;
	}
	
	function exec_attn_student($course, $student_id, $start, $end, $attendance) {
		$pdo = Database::connect();
		
		$sql_change_attn = "UPDATE attendance
					set attendance_yn = '{$attendance}'
					where student_id = '{$student_id}'
					and course_id = '{$course}'
					and class_date between '{$start}' and '{$end}'";

		$pdo->exec($sql_change_attn);
		
		$filename = "../logs/attendance/{$student_id}-attendance.sql";
		$option = 'a';
		$date = date('m/d/Y');
		$content = "\n#attendance changed by admissions on {$date}\n{$sql_change_attn}\n";
		write_to_file($filename, $option, $content);
		
		Database::disconnect();
		return;
	}
	
	function exec_attn_student_date($student_id, $start, $end, $attendance) {
		$pdo = Database::connect();
		
		$sql_change_attn = "UPDATE attendance
					set attendance_yn = '{$attendance}'
					where student_id = '{$student_id}'
					and class_date between '{$start}' and '{$end}'";

		$pdo->exec($sql_change_attn);
		
		$filename = "../logs/attendance/{$student_id}-attendance.sql";
		$option = 'a';
		$date = date('m/d/Y');
		$content = "\n#attendance changed by admissions on {$date}\n{$sql_change_attn}\n";
		write_to_file($filename, $option, $content);
		
		Database::disconnect();
		return;
	}


?>