<?php
define("PAGE_NAME", "Home");

$_SESSION['first_name'] = "";
$_SESSION['last_name'] = "";
$_SESSION['email'] = "";
$_SESSION['phone'] = "";
$_SESSION['course_select'] = "";
            
if (isset($_POST['first_name'])) {
    $_SESSION['first_name'] = $_POST['first_name'];
}

if (isset($_POST['last_name'])) {
    $_SESSION['last_name'] = $_POST['last_name'];
}

if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
}

if (isset($_POST['phone'])) {
    $_SESSION['phone'] = $_POST['phone'];
}

if (isset($_POST['course_select'])) {
    $_SESSION['course_selected'] = $_POST['course_select'];
}

if ($_SESSION['first_name'] == "" || $_SESSION['last_name'] == "" || $_SESSION['email'] == "" || $_SESSION['phone'] == "" || $_SESSION['course_selected'] == "") {
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
} 

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
    </body>
</html>