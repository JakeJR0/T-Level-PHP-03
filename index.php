<?php
define("PAGE_NAME", "Home");
include 'includes/storage.php';

session_start();

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$query = "
    SELECT 
        course.ID, 
        course.course_name,
        level.course_level,
        CONCAT(course.course_name, ' - ', level.course_level) AS course_name_level
    FROM 
        courses AS course
    INNER JOIN course_levels AS level
        ON course.ID = level.course_id
    ORDER BY course_name_level ASC;
";

$result = $connection -> query($query);
$courses = $result -> fetch_all(MYSQLI_ASSOC);
$connection -> close();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    if (isset($_POST['feedback'])) {
        $_SESSION['feedback'] = $_POST['feedback'];
    }

    if (isset($_POST['first_name'])) {
        $_SESSION['first_name'] = $_POST['first_name'] ?? '';
    }
    
    if (isset($_POST['last_name'])) {
        $_SESSION['last_name'] = $_POST['last_name'] ?? '';
    }
    
    if (isset($_POST['email'])) {
        $_SESSION['email'] = $_POST['email'] ?? '';
    }
    
    if (isset($_POST['phone'])) {
        $_SESSION['phone'] = $_POST['phone'] ?? '';
    }
    
    if (isset($_POST['course_select'])) {
        $_SESSION['course_selected'] = $_POST['course_select'] ?? '';
    }

    if (isset($_POST['timestamp'])) {
        $_SESSION['timestamp'] = $_POST['timestamp'] ?? '';
    }
    
    if ($_SESSION['first_name'] == "" || $_SESSION['last_name'] == "" || $_SESSION['email'] == "" || $_SESSION['phone'] == "" || $_SESSION['course_selected'] == "") {
        DisplayCourseForm($courses);
    } else {
        DisplayCourseFormConfirmation();
    }

} else {
    DisplayCourseForm($courses);
}

if (!isset($_SESSION['first_name'])) {
    $_SESSION["first_name"] = "";
}

if (!isset($_SESSION['last_name'])) {
    $_SESSION["last_name"] = "";
}

if (!isset($_SESSION['email'])) {
    $_SESSION["email"] = "";
}

if (!isset($_SESSION['phone'])) {
    $_SESSION["phone"] = "";
}

if (!isset($_SESSION['course_selected'])) {
    $_SESSION["course_selected"] = "";
}

?>
<?php
function DisplayCourseForm($courses) {
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <div class="text-center" style="margin-top: 50px;">
            <h1 class="display-4">Course Enrolment Form</h1>
            <p class="lead">Please fill out the form below to enrol in a course.</p>
        </div>
        <div class="container form-container">
            <form action="" method="POST">
                <div class="form-group mb-3">
                    <label for="first_name">First Name</label>
                    
                    <input pattern="[A-z]{4,20}" class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $_SESSION['first_name'] ?? ''; ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="last_name">Last Name</label>
                    <input pattern="[A-z\-]{6,30}" class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $_SESSION['last_name'] ?? ''; ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $_SESSION['email'] ?? ''; ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input pattern="[0-9]{11}" class="form-control" type="tel" name="phone" id="phone" value="<?php echo $_SESSION['phone'] ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="course_select">Course</label>
                    <select class="form-select" name="course_select" id="course_select">
                        <?php
                            foreach ($courses as $course) {
                                echo "<option value='". $course['course_name_level']."'>{$course['course_name_level']}</option>";
                            }
                        ?>  
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="feedback">Form Feedback</label>
                    <input pattern="[A-z]{30,255}" class="form-control" type="text" name="feedback" id="feedback" value="<?php echo $_SESSION['feedback'] ?? ''; ?>">
                </div>
                <input id="form-time" style="display: none;" type="text" name="timestamp" value="1">
                <script>
                    function GetTime() {
                        // Get the current date object
                        var today = new Date();
                        // Get the current day
                        var day = today.getDay()
                        // Get the current month (Adds 1 to the month because it starts at 0)
                        var month = today.getMonth() + 1;
                        // Get the current year (4 digits)
                        var year = today.getFullYear();
                        // Format the date to be dd-mm-yyyy
                        var date = day + '-' + month + '-' + year;
                        
                        // Gets the input element with the id of form-time
                        var timestamp = document.getElementById('form-time');

                        // Sets the value of the input element to the current date
                        timestamp.setAttribute('value', date);
                    }

                    setInterval(GetTime, 3600000);
                    GetTime();
                </script>

                <button class="btn btn-primary" style="width:100%; margin-top:30px;" type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>

<?php
}

function DisplayCourseFormConfirmation() {
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <div class="text-center">
            <h1 class="display-4">Course Confirmation</h1>
            <lead class="lead">For <?php echo $_SESSION['course_selected'] ?></lead>
        </div>
        <div class="container text-center" style="margin-top: 25px;">
            <p>First Name: <?php echo $_SESSION['first_name']; ?></p>
            <p>Last Name: <?php echo $_SESSION['last_name']; ?></p>
            <p>Email: <?php echo $_SESSION['email']; ?></p>
            <p>Phone: <?php echo $_SESSION['phone']; ?></p>
            <p>Course: <?php echo $_SESSION['course_selected']; ?></p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <a href="index.php" alt="Cancel Button">
                        <button class="btn btn-danger" href="index.php" style="width:100%;">Cancel</button>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="confirmation.php">
                        <button class="btn btn-primary" style="width:100%;">Confirm</button>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
}
?>