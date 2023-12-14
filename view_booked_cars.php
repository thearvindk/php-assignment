<?php
session_start();
require_once "db.php";


// Check if the user is logged in and is a car rental agency
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "agencies") {
    header("Location: login.php");
    exit();
}

// Retrieve agency's username from session
$agency_username = $_SESSION["username"];

// Get all cars for the agency
$sql = "SELECT * FROM cars WHERE agency_username = '$agency_username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>View Booked Cars</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>View Booked Cars</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle Model</th>
                            <th>Vehicle Number</th>
                            <th>Seating Capacity</th>
                            <th>Rent per Day</th>
                            <th>Booking Status</th>
                            <th>Customer Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $car_id = $row["car_id"];

                                // Get booking status and customer details if booked
                                $bookingSql = "SELECT bookings.*, customers.username
                                               FROM bookings
                                               JOIN customers ON bookings.customer_username = customers.username
                                               WHERE bookings.car_id = $car_id";
                                $bookingResult = $conn->query($bookingSql);

                                if ($bookingResult->num_rows > 0) {
                                    $booking = $bookingResult->fetch_assoc();
                                    echo "<tr>";
                                    echo "<td>{$row["model"]}</td>";
                                    echo "<td>{$row["vehicle_number"]}</td>";
                                    echo "<td>{$row["seating_capacity"]}</td>";
                                    echo "<td>{$row["rent_per_day"]}</td>";
                                    echo "<td>Booked</td>";
                                    echo "<td>{$booking["username"]}</td>";
                                    echo "</tr>";
                                } else {
                                    echo "<tr>";
                                    echo "<td>{$row["model"]}</td>";
                                    echo "<td>{$row["vehicle_number"]}</td>";
                                    echo "<td>{$row["seating_capacity"]}</td>";
                                    echo "<td>{$row["rent_per_day"]}</td>";
                                    echo "<td>Not Booked</td>";
                                    echo "<td></td>";
                                    echo "<td></td>";
                                    echo "</tr>";
                                }
                            }
                        } else {
                            echo "<tr><td colspan='7'>No cars available</td></tr>";
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