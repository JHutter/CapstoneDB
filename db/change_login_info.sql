#instructions: 

use cemc;

update login
set pass = '[password here, keep the single quotes but not the brackets]'
where teaacher_id = '[teacherfirst.last here, keep the single quotes but not the brackets]'
;

#this will grab the info from login so you can see what the logins are
select * from login;