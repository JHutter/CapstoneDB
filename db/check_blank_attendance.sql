#instructions: use this file to check for unentered attendance
#to find blank attendance from before the current date, you need to change only this part:
#       where attendance.acad_session = 3 
#       and courseassignment.acad_session = 3
#and put in the current session
#leave 
#         and class_Date < curdate()
# as is
#if you want to find ones on the current date, change the < to =

#To print the report, click Export and choose html filetype.
#Finally, find the report where you saved it (Default place is on the desktop)



use cemc;

select attendance.course_id, teacher_id, class_date, attendance.student_id, concat(stu_first, ' ', stu_last) as 'Name'
from attendance
left join studentinfo on attendance.student_id = studentinfo.student_id
left join courseassignment on attendance.course_id = courseassignment.course_id
where attendance.acad_session = 3 
and courseassignment.acad_session = 3
and class_Date < curdate()
and attendance_yn = 'none'
order by  teacher_id, course_id, class_Date asc;