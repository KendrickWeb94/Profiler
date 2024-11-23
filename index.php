<?php
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

session_start();
include './connection.php'; // Include your database connection

// Redirect to profile.php if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

    // Handling the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $imageUploadPath = './uploads/' . $imageName;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($imageTmpPath, $imageUploadPath)) {
            $imagePath = $imageUploadPath; // Save the path to the database
        } else {
            $error = "Failed to upload the image.";
        }
    } else {
        $error = "Image is required.";
    }

    if (!isset($error)) {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email is already registered
        $sql = "SELECT * FROM users_profile WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "Email is already registered. Please login.";
        } else {
            // Insert the new user into the database
            $sql = "INSERT INTO users_profile (name, email, password, age, image, bio) 
                    VALUES ('$name', '$email', '$hashedPassword', '$age', '$imagePath', '$bio')";

            if ($conn->query($sql) === TRUE) {
                // Store user info in session and redirect to the profile page
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['age'] = $age;
                $_SESSION['image'] = $imagePath;
                $_SESSION['bio'] = $bio;
                header("Location: profile.php");
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiler - Your Top Profile Creator</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
  
    <div class="wrapper">
        <?php if (isset($error)): ?>
            <div class='error'><?php echo $error; ?></div>
        <?php endif; ?>
        <br>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <div class="form_box">
                <input type="text" required name="name" id="name" placeholder="Enter your name">
            </div>
            <div class="form_box">
                <input type="email" required name="email" id="email" placeholder="Enter your email">
            </div>
            <div class="form_box">
                <input type="password" required name="password" id="password" placeholder="Create a password">
            </div>
            <div class="form_box">
                <input type="number" required name="age" id="age" placeholder="Enter your age">
            </div>
            <div class="form_box">
                <textarea required name="bio" id="bio" placeholder="Write about yourself, showcase who you are"
                    style="height: 120px;"></textarea>
            </div>
            <div class="form_box">
                <input type="file" required accept=".jpg,.png,.svg" name="image" id="image">
            </div>
            <input type="submit" name="submit" value="Create your Profile">
            <br>
            <p>Already have an account?  <a href="index.php">Login here</a>.</p>
        </form>
    </div>
</body>

</html>