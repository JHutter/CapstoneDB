use cemc;

CREATE TABLE SESSIONGRADE(
		course_type		VARCHAR(10)			NOT NULL,
        acad_year		INT					NOT NULL,
        acad_session	INT					NOT NULL,
        student_id		VARCHAR(10)			NOT NULL,
        percent			float				NULL,
        points			float				NULL,
        
        CONSTRAINT		SESSIONG_PK			PRIMARY KEY(course_type, acad_year, acad_session, student_id),
		CONSTRAINT		SESSIONG_FK2			FOREIGN KEY(acad_year, acad_session)
											REFERENCES CALENDAR(acad_year, acad_session),
		CONSTRAINT		SESSIONG_FK3			FOREIGN KEY(course_type)
											REFERENCES COURSE_type(course_type),
		CONSTRAINT		SESSIONG_FK4			FOREIGN KEY(student_id)
											REFERENCES STUDENTINFO(student_id)
)ENGINE=InnoDB;


CREATE TABLE termGRADE(
		course_type		VARCHAR(10)			NOT NULL,
        acad_year		INT					NOT NULL,
        acad_term		int					not null,
        student_id		VARCHAR(10)			NOT NULL,
        percent			float				NULL,
        points			float				NULL,
        
        CONSTRAINT		termG_PK			PRIMARY KEY(course_type, acad_year, acad_term, student_id),
		CONSTRAINT		termG_FK2			FOREIGN KEY(acad_year, acad_term)
											REFERENCES CALENDAR(acad_year, acad_session),
		CONSTRAINT		termG_FK3			FOREIGN KEY(course_type)
											REFERENCES COURSE_type(course_type),
		CONSTRAINT		termG_FK4			FOREIGN KEY(student_id)
											REFERENCES STUDENTINFO(student_id)
)ENGINE=InnoDB;