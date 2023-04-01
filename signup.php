<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>User Sign Up</h2>
    <form method="post">
        <label>First Name:</label>
        <input type="text" name="first_name" required><br><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br><br>

        <input type="submit" name="submit" value="Sign Up">
    </form>
    <br>
    Already have an Account
    <a href="login.php"><button>Sign In</button></a>
    <br>

    <?php
    // Check if the submit button was clicked
    if (isset($_POST['submit'])) {
        // Establish a connection to the MySQL database
        $conn = mysqli_connect("localhost", "root", "iitp@123", "user");

        // Check if the connection was successful
        if (!$conn) {
            echo "failed";
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve user data from the registration form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate password strength
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number    = preg_match('/\d/', $password);
        $special   = preg_match('/[^a-zA-Z\d]/', $password);
        $flag = 1;
        if (!$uppercase || !$lowercase || !$number || !$special || strlen($password) < 8) {
            echo "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
            $flag = 0;
        }
        // Check if the passwords match
        if ($flag == 1) {
            if ($password != $confirm_password) {
                echo "Passwords do not match";
                exit();
            }
            // Encrypt the password
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO user (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
            if (mysqli_query($conn, $sql)) {
                echo "\nUser registered successfully. You can now Sign In";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
        }

        // Close the database connection
        mysqli_close($conn);
    }
    ?>
</body>

</html>