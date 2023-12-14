<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST["user_type"];
    if (empty($_POST["username"])) {
        $errorMessage = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    
        // Check if the username already exists
        $existingUserQuery = "SELECT * FROM {$user_type} WHERE username = '$username'";
        $existingUserResult = mysqli_query($conn, $existingUserQuery);
        $existingUserCount = mysqli_num_rows($existingUserResult);
    
        if ($existingUserCount >= 1) {
            echo "<script>alert('Username Already exists');</script>";
            $errorMessage = "Username already exists";
        } else {
            $username = test_input($_POST["username"]);
        }
    }
    // Validate password
    if (empty($_POST["password"])) {
        $errorMessage = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }
    // Registration code for both customer and car rental agency
    if (empty($errorMessage)) {
    if ($user_type == "customers") {
        // Registration for customer
        $sql = "INSERT INTO customers (username, password) VALUES ('$username', '$password')";
    } elseif ($user_type == "agencies") {
        // Registration for car rental agency
        $sql = "INSERT INTO agencies (username, password) VALUES ('$username', '$password')";
    } else {
        echo "Invalid user type";
        exit();
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION["sc"]=1;
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Registration</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="post">
                    <h2>Registration</h2>
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
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
