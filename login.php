<?php
session_start();

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION["username"]) ) {
    // Login code for both customer and car rental agency
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    if ($user_type == "customers") {
        $table = "customers";
        $redirect_url = "bookings.php";
    } elseif ($user_type == "agencies") {
        $table = "agencies";
        $redirect_url = "addcar.php";
    } else {
        echo "Invalid user type";
        exit();
    }

    $sql = "SELECT * FROM $table WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Redirect to respective dashboard or home page
        $_SESSION["username"] = $username;
        $_SESSION["user_type"] = $user_type;
        header("Location: $redirect_url");
        exit();
    } else {
        echo "Invalid username or password";
    }
}
else{
    if(isset($_SESSION["username"])&&isset($_SESSION["user_type"])){
        $username = $_SESSION["username"];
    $user_type = $_SESSION["user_type"];

    if ($user_type == "customers") {
        $table = "customers";
        $redirect_url = "bookings.php";
    } elseif ($user_type == "agencies") {
        $table = "agencies";
        $redirect_url = "addcar.php"; 
    } else {
        echo "Invalid user type";
        exit();
    }
    header("Location: $redirect_url");
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="post">
                    <h2>Login</h2>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>User Type:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="user_type" id="customer" value="customers" checked>
                            <label class="form-check-label" for="customer">Customer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="user_type" id="agency" value="agencies">
                            <label class="form-check-label" for="agency">Car Rental Agency</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="button" class="btn btn-primary" onclick="location.href='registration.php';">Register</button>
                </form>
                
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
