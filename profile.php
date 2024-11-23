<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];
$email = $_SESSION['email'];
$age = $_SESSION['age'];
$image = $_SESSION['image'];
$bio = $_SESSION['bio'];

if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: ./index.php"); // Redirect to the login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <div class="wrapper profile">
        <div class="profile_box">
            <div class="profile_content_right">
                <img src="<?php echo htmlspecialchars($image); ?>" width="80" height="80" alt="Your profile image">
                <div>
                    <h3><?php echo htmlspecialchars($name); ?></h3>
                    <p><?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>
            <div class="profile_content_right">
                <h5><?php echo htmlspecialchars($age); ?> years old</h5>
                <a href="./logout.php" class="btn">Logout</a>
            </div>
        </div>
        <div class="bio">
            <?php echo nl2br(htmlspecialchars($bio)); ?>
        </div>
    </div>
</body>

</html>
