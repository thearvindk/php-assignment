<?php
session_start();
include 'includes/db.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION["username"]) || $_SESSION["user_type"] !== "customer") {
    header("Location: login.php");
    exit();
}

// Retrieve customer's username from session
$customer_username = $_SESSION["username"];

// Get current bookings for the customer
$sql = "SELECT * FROM bookings
        JOIN cars ON bookings.car_id = cars.car_id
        WHERE customer_username = '$customer_username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Current Bookings</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Current Bookings</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle Model</th>
                            <th>Vehicle Number</th>
                            <th>Seating Capacity</th>
                            <th>Rent per Day</th>
                            <th>Start Date</th>
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
                                echo "<td>{$row["start_date"]}</td>";
                                echo "<td><a href='rentcar.php?booking_id={$row["booking_id"]}' class='btn btn-primary'>Rent Car</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No current bookings</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <a href="rentcar.php" class="btn btn-primary">Rent a Car</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
