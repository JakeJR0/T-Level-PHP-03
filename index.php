<?php
define("PAGE_NAME", "Home");
include 'includes/storage.php';

# Starts the session
session_start();

# Connects to the database
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
# Gets the course data for the form
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

# Runs the query
$result = $connection -> query($query);
# Extracts the data from the query
$courses = $result -> fetch_all(MYSQLI_ASSOC);

# Closes the connection
$connection -> close();

# Checks if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    # Checks if feedback has been provided
    if (isset($_POST['feedback'])) {
        # Assigns the feedback to the session
        $_SESSION['feedback'] = $_POST['feedback'];
    }

    # Checks if first name has been provided
    if (isset($_POST['first_name'])) {
        # Assigns the first name to the session
        $_SESSION['first_name'] = $_POST['first_name'] ?? '';
    }

    # Checks if last name has been provided
    if (isset($_POST['last_name'])) {
        # Assigns the last name to the session
        $_SESSION['last_name'] = $_POST['last_name'] ?? '';
    }
    
    # Checks if email has been provided
    if (isset($_POST['email'])) {
        # Assigns the email to the session
        $_SESSION['email'] = $_POST['email'] ?? '';
    }
    
    # Checks if phone has been provided
    if (isset($_POST['phone'])) {
        # Assigns the phone to the session
        $_SESSION['phone'] = $_POST['phone'] ?? '';
    }

    # Checks if a course has been selected
    if (isset($_POST['course_select'])) {
        # Assigns the course to the session
        $_SESSION['course_selected'] = $_POST['course_select'] ?? '';
    }

    # Checks if a timestamp has been provided
    if (isset($_POST['timestamp'])) {
        # Assigns the timestamp to the session
        $_SESSION['timestamp'] = $_POST['timestamp'] ?? '';
    }
    
    # Checks if the user has provided all the required information
    if ($_SESSION['first_name'] == "" || $_SESSION['last_name'] == "" || $_SESSION['email'] == "" || $_SESSION['phone'] == "" || $_SESSION['course_selected'] == "" || $_SESSION['timestamp'] == "" || $_SESSION['feedback'] == "") {
        # Displays the Course Form as the user has not provided all the required information
        DisplayCourseForm($courses);
    } else {
        # Displays the Confirmation Form as the user has provided all the required information
        DisplayCourseFormConfirmation();
    }

} else {
    # Displays the Course Form as the user has not submitted the form
    DisplayCourseForm($courses);
}

# Checks if first name is not in the session
if (!isset($_SESSION['first_name'])) {
    # Assigns the first name to the session
    $_SESSION["first_name"] = "";
}

# Checks if last name is not in the session
if (!isset($_SESSION['last_name'])) {
    # Assigns the last name to the session
    $_SESSION["last_name"] = "";
}

# Checks if email is not in the session
if (!isset($_SESSION['email'])) {
    # Assigns the email to the session
    $_SESSION["email"] = "";
}

# Checks if phone is not in the session
if (!isset($_SESSION['phone'])) {
    # Assigns the phone to the session
    $_SESSION["phone"] = "";
}

# Checks if course selected is not in the session
if (!isset($_SESSION['course_selected'])) {
    # Assigns the course selected to the session
    $_SESSION["course_selected"] = "";
}

?>
<?php
function DisplayCourseForm($courses) {
# Stores the HTML for the form
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <!-- Header -->
        <div class="text-center" style="margin-top: 50px;">
            <h1 class="display-4">Course Enrolment Form</h1>
            <p class="lead">Please fill out the form below to enrol in a course.</p>
        </div>
        <!-- Form Container -->
        <div class="container form-container">
            <!-- Form -->
            <form action="" method="POST">
                <!-- First Name -->
                <div class="form-group mb-3">
                    <label for="first_name">First Name</label>
                    
                    <input pattern="[A-z]{4,20}" class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $_SESSION['first_name'] ?? ''; ?>">
                </div>
                <!-- Last Name -->
                <div class="form-group mb-3">
                    <label for="last_name">Last Name</label>
                    <input pattern="[A-z\-]{6,30}" class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $_SESSION['last_name'] ?? ''; ?>">
                </div>
                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $_SESSION['email'] ?? ''; ?>">
                </div>
                <!-- Phone -->
                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input pattern="[0-9]{11}" class="form-control" type="tel" name="phone" id="phone" value="<?php echo $_SESSION['phone'] ?? ''; ?>">
                </div>
                <!-- Course -->
                <div class="mb-3">
                    <!-- Course Label -->
                    <label for="course_select">Course</label>
                    <!-- Course Select -->
                    <select class="form-select" name="course_select" id="course_select">
                        <?php
                            # Loops through the courses from the database
                            foreach ($courses as $course) {
                                # Checks if the course is the one selected
                                if ($course['course_name_level'] == $_SESSION['course_selected']) {
                                    # Displays the course as selected
                                    echo "<option selected value='" . $course['course_name_level'] . "'>" . $course['course_name_level'] . "</option>";
                                } else {
                                    # Displays the course as not selected
                                    echo "<option value='". $course['course_name_level']."'>{$course['course_name_level']}</option>";
                                }
                            }
                        ?>  
                    </select>
                </div>
                <!-- Form Feedback -->
                <div class="form-group">
                    <!-- Form Feedback Label -->
                    <label for="feedback">Form Feedback</label>
                    <!-- Form Feedback Input -->
                    <input class="form-control" type="text" maxlength="255" minlength="30" name="feedback" id="feedback" value="<?php echo $_SESSION['feedback'] ?? ''; ?>">
                </div>
                <!-- Timestamp (Hidden) -->
                <input id="form-time" style="display: none;" type="text" name="timestamp" value="1">
                <script>
                    // Gets the current date and sets it to the timestamp input
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

                    // Runs the GetTime function every hour
                    setInterval(GetTime, 3600000);
                    // Runs the function once on page load
                    GetTime();
                </script>
                <!-- Submit Button -->
                <button class="btn btn-primary" style="width:100%; margin-top:30px;" type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>

<?php
}

function DisplayCourseFormConfirmation() {
# Stores the HTML for the confirmation page
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <!-- Header -->
        <div class="text-center">
            <h1 class="display-4">Course Confirmation</h1>
            <lead class="lead">For <?php echo $_SESSION['course_selected'] ?></lead>
        </div>
        <!-- Confirmation Container -->
        <div class="container text-center" style="margin-top: 25px;">
            <!-- User Details -->
            <p>First Name: <?php echo $_SESSION['first_name']; ?></p>
            <p>Last Name: <?php echo $_SESSION['last_name']; ?></p>
            <p>Email: <?php echo $_SESSION['email']; ?></p>
            <p>Phone: <?php echo $_SESSION['phone']; ?></p>
            <p>Course: <?php echo $_SESSION['course_selected']; ?></p>
        </div>
        <!-- Controls Container -->
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <!-- Back Button -->
                    <a href="index.php" alt="Cancel Button">
                        <button class="btn btn-danger" href="index.php" style="width:100%;">Cancel</button>
                    </a>
                </div>
                <div class="col-sm-6">
                    <!-- Submit Button -->
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