<?php
session_start();
include 'includes/db.php';

// Check if the user is logged in and is a car rental agency
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new cars code for car rental agency
    // Retrieve and validate form data
    $model = $_POST["model"];
    $vehicle_number = $_POST["vehicle_number"];
    $seating_capacity = $_POST["seating_capacity"];
    $rent_per_day = $_POST["rent_per_day"];

    // Perform database insert
    $agency_username = $_SESSION["username"];
    $sql = "INSERT INTO cars (agency_username, model, vehicle_number, seating_capacity, rent_per_day) 
            VALUES ('$agency_username', '$model', '$vehicle_number', '$seating_capacity', '$rent_per_day')";

    if ($conn->query($sql) === TRUE) {
        echo "Car added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Add New Cars</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="" method="post">
                    <h2>Add New Cars</h2>
                    <div class="form-group">
                        <label for="model">Vehicle Model:</label>
                        <input type="text" class="form-control" name="model" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_number">Vehicle Number:</label>
                        <input type="text" class="form-control" name="vehicle_number" required>
                    </div>
                    <div class="form-group">
                        <label for="seating_capacity">Seating Capacity:</label>
                        <input type="number" class="form-control" name="seating_capacity" required>
                    </div>
                    <div class="form-group">
                        <label for="rent_per_day">Rent per Day:</label>
                        <input type="number" class="form-control" name="rent_per_day" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Car</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
