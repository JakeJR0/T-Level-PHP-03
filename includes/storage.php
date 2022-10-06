<?php
# Starts the connection to the database
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "T-Level-Lab-03");

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    echo "Connection failed";
}

$table = "
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

    CREATE TABLE IF NOT EXISTS students(
        ID INT PRIMARY KEY AUTO_INCREMENT,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        course_id INT NOT NULL,
        course_level INT NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(ID),
        FOREIGN KEY (course_level) REFERENCES course_levels(ID)
    );

    CREATE TABLE feedback(
        ID INT PRIMARY KEY AUTO_INCREMENT,
        student_id INT NOT NULL,
        feedback VARCHAR(255) NOT NULL,
        FOREIGN KEY (student_id) REFERENCES students(ID)
    );
";

$create_table = mysqli_multi_query($connection, $table);

if (!$create_table) {
    echo "Table creation failed";
}

$connection -> close();