<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Data</title>
</head>

<body>
    <h2>Account Details</h2>
<?php
// Establish a connection to the MySQL database
$conn = mysqli_connect("localhost", "root", "iitp@123", "user");

// Check if the connection was successful
if (!$conn) {
    echo "failed";
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $_SESSION['email']=$email;
} else {
    header("Location: login.php");
    exit();
}

// Check if the "Delete Account" button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Delete the user's account from the database
    $sql = "DELETE FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect the user to the login page
        header("Location: login.php");
        exit();
    } else {
        echo "Failed to delete account: " . mysqli_error($conn);
    }
}

// Retrieve the user's account details from the database
$sql = "SELECT * FROM user WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    echo "<p>First Name: " . $row['first_name'] . "</p>";
    echo "<p>Last Name: " . $row['last_name'] . "</p>";
   echo "<p>Email: " . $row['email'] . "</p>";
}
// Close the database connection
mysqli_close($conn);
?>
<br>
<a href="welcome_page.php"><button>Home</button></a>
</body>
</html>