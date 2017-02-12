use cemc;

select attendance.course_id, teacher_id, class_date, attendance.student_id, concat(stu_first, ' ', stu_last) as 'Name'
from attendance
left join studentinfo on attendance.student_id = studentinfo.student_id
left join courseassignment on attendance.course_id = courseassignment.course_id
where attendance.acad_session = 2 
and courseassignment.acad_session = 2
and class_Date < 20160226
and attendance_yn = 'none'
order by  teacher_id, course_id, class_Date;