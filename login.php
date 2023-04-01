<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>

<body>
    <h2>User Login</h2>
    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="submit" value="Sign In">

    </form>
    <br>
    Don't have an account?
    <a href="signup.php"><button>Sign Up</button></a>
    <br>
    <?php
     if(isset($_POST['submit'])) {
        // Establish a connection to the MySQL database
        $conn = mysqli_connect("localhost", "root", "iitp@123", "user");
          
        // Check if the connection was successful
        if(!$conn){
            echo "failed";
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve user data from the login form
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Retrieve user data from the database
        $sql = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        // Start the session
session_start();

// Set the session variable
$_SESSION['email'] = $email;

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row['password'])){
                echo "Login successful";
                header("Location: welcome_page.php");
                exit();
            }
            else{
                echo "Incorrect password";
            }
        }
        else{
            echo "User Not Found";
        }

        // Close the database connection
        mysqli_close($conn);
    }
    ?>
</body>

</html>
