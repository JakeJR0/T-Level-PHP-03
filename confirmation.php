<?php
define("PAGE_NAME", "Home");
include 'includes/storage.php';
session_start();

function save_user() {
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $selected_course = $_SESSION['course_selected'];
    $feedback = $_SESSION['feedback'];

    [$course, $course_level] = explode(" - ", $selected_course);
    
    $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $course_selected = filter_var($course, FILTER_SANITIZE_STRING);
    $course_level = filter_var($course_level, FILTER_SANITIZE_STRING);
    $feedback = filter_var($feedback, FILTER_SANITIZE_STRING);

    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $course_query = "
        SELECT
            c.ID AS 'Course',
            cl.ID AS 'Level'
        FROM
            courses c
        INNER JOIN
            course_levels cl ON c.ID = cl.course_id
        WHERE c.course_name = '".$course_selected."' AND cl.course_level = '". $course_level ."';
    ";
    
    $result = $connection -> query($course_query);
    $course_info = $result -> fetch_row();
    $course = $course_info[0];
    $level = $course_info[1];
    
    $student_query = "
        INSERT INTO students(first_name, last_name, email, course_id, course_level)
        VALUES(
            '{$first_name}',
            '{$last_name}',
            '{$email}',
            '{$course}',
            '{$level}'
        );
    ";

    $result = $connection -> query($student_query);

    $feedback_query = "
        INSERT INTO feedback(student_id, feedback)
        VALUES(
            '{$connection -> insert_id}',
            '{$feedback}'
        );
    ";

    $result = $connection -> query($feedback_query);

    $connection -> close();
    session_unset();
}

if ($_SESSION['first_name'] == "" || $_SESSION['last_name'] == "" || $_SESSION['email'] == "" || $_SESSION['phone'] == "" || $_SESSION['course_selected'] == "") {
    header("Location: index.php");
    exit();
} else {
    DisplayConfirmation();
    
    #save_user();
}

function DisplayConfirmation() {
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="display-4" style="margin-top: 30px;">Enrolment Details</h1>
                    <?php 
                        $timestamp = $_SESSION['timestamp'];
                        $timestamp = filter_var($timestamp, FILTER_SANITIZE_STRING);
                        $timestamp = str_replace("-", "/", $timestamp);
                    ?>
                    <p class="lead">Thank you for registering for the <?php echo $_SESSION['course_selected']; ?> course on <?php echo $timestamp ?></lead>
                </div>
                <div class="col-md-12 text-center" style="margin-top: 50px;">
                    <h2 class="display-5" style="margin-top:25px; margin-bottom: 50px;">Your Details</h2>
                   
                    <p>Name: <strong><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></strong></p>
                    <p>Email: <strong><?php echo $_SESSION['email']; ?></strong></p>
                    <p>Phone: <strong><?php echo $_SESSION['phone']; ?></strong></p>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
}
?>