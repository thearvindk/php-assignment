<?php
session_start();
require_once "db.php";

// Check if the user is logged in and is a customer
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "customers") {
    header("Location: login.php");
    exit();
}

// Process booking if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_car"])) {
    // Retrieve and sanitize form data
    $car_id = $_POST["car_id"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    // Perform the booking
    $customer_username = $_SESSION["username"];
    $status = 1; // Set car status to rented

    // Insert booking information into the database
    $bookingSql = "INSERT INTO bookings (customer_username, car_id, start_date, end_date) 
                   VALUES ('$customer_username', $car_id, '$start_date', '$end_date')";
    
    // Update car status to rented
    $updateCarSql = "UPDATE cars SET status = $status WHERE car_id = $car_id";

    if ($conn->query($bookingSql) === TRUE && $conn->query($updateCarSql) === TRUE) {
        echo "<script>alert('Car booked successfully');</script>";
    } else {
        echo "Error: " . $bookingSql . "<br>" . $conn->error;
    }
}

// Get available cars with status 0
$sql = "SELECT * FROM cars WHERE status = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Rent a Car</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Available Cars for Booking</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle Model</th>
                            <th>Vehicle Number</th>
                            <th>Seating Capacity</th>
                            <th>Rent per Day</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row["model"]}</td>";
                                echo "<td>{$row["vehicle_number"]}</td>";
                                echo "<td>{$row["seating_capacity"]}</td>";
                                echo "<td>{$row["rent_per_day"]}</td>";
                                echo "<td>";
                                echo "<form method='post'>";
                                echo "<input type='hidden' name='car_id' value='{$row["car_id"]}'>";
                                echo "From: <input type='date' name='start_date' required>&nbsp;";
                                echo "To: <input type='date' name='end_date' required>&nbsp;";
                                echo "<button type='submit' class='btn btn-primary' name='book_car'>Book Car</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No available cars for booking</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
