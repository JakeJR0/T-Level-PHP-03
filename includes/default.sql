CREATE TABLE IF NOT EXISTS courses(
    ID INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS course_levels(
    ID INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    course_level VARCHAR(255) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(ID)
);

CREATE TABLE feedback(
    ID INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    feedback VARCHAR(255) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(ID)
);

INSERT INTO courses(course_name) VALUES('DPDD'), ('DBS'), ('DSS');
INSERT INTO course_levels(course_id, course_level) 
VALUES
(1, 'Beginner'),
(2, 'Beginner'),
(3, 'Beginner'),
(1, 'Intermediate'),
(2, 'Intermediate'),
(3, 'Intermediate'),
(1, 'Advanced'),
(2, 'Advanced'),
(3, 'Advanced');

