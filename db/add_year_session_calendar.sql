#instructions: when you have the calendar for the new year (2017), 
#enter the session start and end dates here in yyyymmdd format


use cemc;

INSERT INTO CALENDAR
	(acad_year, acad_session, session_start, session_end, acad_term)
VALUES
	(2017, 1, 20151201, 20160122, 1),
    (2017, 2, 20160125, 20160304, 1),
    (2017, 3, 20160307, 20160429, 2),
    (2017, 4, 20160502, 20160610, 2),
    (2017, 5, 20160613, 20160805, 3),
    (2017, 6, 20160808, 20160916, 3),
    (2017, 7, 20160919, 20161028, 4),
    (2017, 8, 20161031, 20161216, 4)
;