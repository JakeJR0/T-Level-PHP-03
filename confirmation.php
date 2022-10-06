<?php
# Sets the page name
define("PAGE_NAME", "Confirmation");

# Includes the storage file
include 'includes/storage.php';

# Starts the session
session_start();

# This function adds the data to the database
function save_user() {
    # Gets first name from session
    $first_name = $_SESSION['first_name'];
    # Gets last name from session
    $last_name = $_SESSION['last_name'];
    # Gets email from session
    $email = $_SESSION['email'];
    # Gets course from session
    $phone = $_SESSION['phone'];
    # Gets course from session
    $selected_course = $_SESSION['course_selected'];
    # Gets course level from session
    $feedback = $_SESSION['feedback'];

    # Seperates the course level from the course
    [$course, $course_level] = explode(" - ", $selected_course);
    
    # Filters the first name
    $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
    # Filters the last name
    $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
    # Filters the email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    # Filters the phone number
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    # Filters the feedback
    $course_selected = filter_var($course, FILTER_SANITIZE_STRING);
    # Filters the feedback
    $course_level = filter_var($course_level, FILTER_SANITIZE_STRING);
    # Filters the feedback
    $feedback = filter_var($feedback, FILTER_SANITIZE_STRING);

    # Starts the connection to the database
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    # Gets the course id and course level id using the course and course level from the form.

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
    
    # Executes the query
    $result = $connection -> query($course_query);
    # Gets the result into an array
    $course_info = $result -> fetch_row();
    # Gets the course id
    $course = $course_info[0];
    # Gets the course level id
    $level = $course_info[1];
    
    # Inserts the student data into the database
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

    # Executes the query
    $result = $connection -> query($student_query);
    
    # Inserts the feedback into the database
    $feedback_query = "
        INSERT INTO feedback(student_id, feedback)
        VALUES(
            '{$connection -> insert_id}',
            '{$feedback}'
        );
    ";

    # Executes the query
    $result = $connection -> query($feedback_query);

    # Closes the connection
    $connection -> close();

    # Cleans the session
    session_unset();
}

# Checks if the form is missing required fields
if ($_SESSION['first_name'] == "" || $_SESSION['last_name'] == "" || $_SESSION['email'] == "" || $_SESSION['phone'] == "" || $_SESSION['course_selected'] == "") {
    # Redirects to the form page
    header("Location: index.php");
    # Stops the script
    exit();
} else {
    # Displays the confirmation page
    DisplayConfirmation();
    # Saves the user to the database
    save_user();
}

function DisplayConfirmation() {
# Used to store the HTML for the page
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <div class="container">
            <div class="row">
                <!-- Header -->
                <div class="col-md-12 text-center">
                    <h1 class="display-4" style="margin-top: 30px;">Enrolment Details</h1>
                    <?php 
                        # Gets the timestamp from the session
                        $timestamp = $_SESSION['timestamp'];
                        # Filters the timestamp
                        $timestamp = filter_var($timestamp, FILTER_SANITIZE_STRING);
                        # Replaces the underscore with a '/'
                        $timestamp = str_replace("-", "/", $timestamp);
                    ?>
                    <p class="lead">Thank you for registering for the <?php echo $_SESSION['course_selected']; ?> course on <?php echo $timestamp ?></lead>
                </div>
                <!-- Student Details -->
                <div class="col-md-12 text-center" style="margin-top: 50px;">
                    <h2 class="display-5" style="margin-top:25px; margin-bottom: 50px;">Your Details</h2>
                    <!-- Name -->
                    <p>Name: <strong><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></strong></p>
                    <!-- Email -->
                    <p>Email: <strong><?php echo $_SESSION['email']; ?></strong></p>
                    <!-- Phone -->
                    <p>Phone: <strong><?php echo $_SESSION['phone']; ?></strong></p>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
}
?>