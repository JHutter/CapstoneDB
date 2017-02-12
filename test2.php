<?php
include_once('php/grade_tables.php');
include_once('php/get_name.php');


$student_id = '25690';
$course_type = '11am';
$category = '40a';
$acad_year = 2016;
$acad_session = 1;
// $students = get_all_stus();
// $grade = get_term_grade($student_id, $course_type, $acad_year, $acad_term);

// echo $grade;
// $foobar = update_session_grades_all_stus($students, $acad_year, $acad_session);
// $foobar = update_session_grades_stu_course_total('24894', 'VC2B-1', $acad_year, $acad_session);
$foobar = update_session_grades_stu_course_cat('24894', 'VC2B-1', $category, $acad_year, $acad_session);
// $message = update_session_grades_all_stus($students, $acad_year, $acad_session);
// $table = update_term_grades_all_stus($students, $acad_year, $acad_term);

// $message = 'foobar';

echo $foobar;

?>