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
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php"; ?>
    <body>
        <div class="container form-container">
            <form action="confirmation.php" method="POST">
                <div class="form-group mb-3">
                    <label for="first_name">First Name</label>
                    
                    <input pattern="[A-z]{4,20}" class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $_SESSION['first_name']; ?>>
                </div>
                <div class="form-group mb-3">
                    <label for="last_name">Last Name</label>
                    <input pattern="[A-z\-]{6,30}" class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $_SESSION['last_name']; ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="<?php echo $_SESSION['email']; ?>>
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input pattern="[0-9]{11}" class="form-control" type="tel" name="phone" id="phone" value="<?php echo $_SESSION['phone']; ?>">
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
                <button class="btn btn-primary" style="width:100%;" type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>