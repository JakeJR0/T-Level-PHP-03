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
";

$create_table = mysqli_multi_query($connection, $table);

if (!$create_table) {
    echo "Table creation failed";
}

$connection -> close();