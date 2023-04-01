<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Establish a connection to the MySQL database
$conn = mysqli_connect("localhost", "root", "iitp@123", "user");

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user data from the database
$email = $_SESSION['email'];
$sql = "SELECT * FROM user WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve user data from the update form
    $new_first_name = $_POST['first_name'];
    $new_last_name = $_POST['last_name'];
    $new_password = $_POST['password'];
    $confirm_new_password = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($new_password != $confirm_new_password) {
        echo "New passwords do not match";
        exit();
    }
    // Validate password strength
    $uppercase = preg_match('/[A-Z]/', $new_password);
    $lowercase = preg_match('/[a-z]/', $new_password);
    $number    = preg_match('/\d/', $new_password);
    $special   = preg_match('/[^a-zA-Z\d]/', $new_password);
    $flag = 1;
    if (!$uppercase || !$lowercase || !$number || !$special || strlen($new_password) < 8) {
        $flag = 0;
    }
    if ($flag == 1) {
        // Encrypt the new password
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user data in the database
        $sql = "UPDATE user SET first_name='$new_first_name', last_name='$new_last_name', password='$new_password' WHERE email='$email'";

        if (mysqli_query($conn, $sql)) {
            echo "User data updated successfully";
            header("Location: welcome_page.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    else{
        echo "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Data</title>
</head>

<body>
    <h2>Update User Data</h2>
    <form method="post">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo $first_name; ?>" required><br><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $last_name; ?>" required><br><br>

        <label>New Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required><br><br>

        <input type="submit" name="submit" value="Update">
    </form>
    <br>
    <a href="welcome_page.php"><button>Home</button></a>
</body>

</html>