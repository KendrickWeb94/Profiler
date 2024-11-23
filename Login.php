<?php
session_start();
include './connection.php'; // Include your database connection

// Redirect to profile.php if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the email exists in the database
    $sql = "SELECT * FROM users_profile WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user info in session and redirect to the profile page
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['age'] = $user['age'];
            $_SESSION['image'] = $user['image'];
            $_SESSION['bio'] = $user['bio'];

            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Profiler</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <h1 class="head-text">Login to Profiler</h1>
    <div class="wrapper">
        <?php if (isset($error)): ?>
            <div class='error'><?php echo $error; ?></div>
        <?php endif; ?>
        <br>
        <form action="Login.php" method="POST">
            <div class="form_box">
                <input type="email" required name="email" id="email" placeholder="Enter your email">
            </div>
            <div class="form_box">
                <input type="password" required name="password" id="password" placeholder="Enter your password">
            </div>
            <input type="submit" name="submit" value="Login">
            <br>
            <br>
            <p>Don't have an account? <a href="index.php">Sign up here</a>.</p>
        </form>
    </div>
</body>

</html>
