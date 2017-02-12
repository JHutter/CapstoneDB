 <?php
 
function get_course_name_singular($course, $category) {
	$first = substr($course, 0,1);
	if ($category == '40a') {
		switch ($first) {
			case 'T':
				$cat_name = "Pre and Post Test";
				break;
			case 'P':
				$cat_name = "Formal Presentation";
				break;
			case 'W':
				$cat_name = "Formal Essay";
				break;
			default:
				$cat_name = "Assignment";
		}
	}
	else if ($category == '40b') {
		switch ($first) {
			case 'G':
				$cat_name = "Exam";
				break;
			case 'V':
				$cat_name = "Unit Project";
				break;
			case 'T':
			case 'N':
			case 'C':
			case 'W':
				$cat_name = "Quiz";
				break;
			case 'P':
				$cat_name = "Assignment";
				break;
			default:
				$cat_name = "Unit Assessment";
		}
	}
	else { //category is 20
		switch ($first) {
			case 'G':
				$cat_name = "Quiz";
				break;
			case 'V':
				$cat_name = "Exam";
				break;
			case 'N':
				$cat_name = "Final Project";
				break;
			case 'P':
				$cat_name = "Impromptu Speech";
				break;
			case 'W':
			case 'T':
				$cat_name = "Assignment";
				break;
			case 'C':
				$cat_name = "Final Presentation";
				break;
			default:
				$cat_name = "Final Exam";
		}
	}	
	return $cat_name;
}

function get_course_name_plural($course, $category) {
	$first = substr($course, 0,1);
	if ($category == '40a') {
		switch ($first) {
			case 'T':
				$cat_name = "Pre and Post Tests";
				break;
			case 'P':
				$cat_name = "Formal Presentations";
				break;
			case 'W':
				$cat_name = "Formal Essays";
				break;
			default:
				$cat_name = "Assignments";
		}
	}
	else if ($category == '40b') {
		switch ($first) {
			case 'G':
				$cat_name = "Exams";
				break;
			case 'V':
				$cat_name = "Unit Projects";
				break;
			case 'T':
			case 'N':
			case 'C':
			case 'W':
				$cat_name = "Quizzes";
				break;
			case 'P':
				$cat_name = "Assignments";
				break;
			default:
				$cat_name = "Unit Assessments";
		}
	}
	else { //category is 20
		switch ($first) {
			case 'G':
				$cat_name = "Quizzes";
				break;
			case 'V':
				$cat_name = "Exams";
				break;
			case 'N':
				$cat_name = "Final Projects";
				break;
			case 'P':
				$cat_name = "Impromptu Speeches";
				break;
			case 'W':
			case 'T':
				$cat_name = "Assignments";
				break;
			case 'C':
				$cat_name = "Final Presentations";
				break;
			default:
				$cat_name = "Final Exams";
		}
	}	
	return $cat_name;
}

 ?>