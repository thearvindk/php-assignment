<?php
session_start();
include 'includes/db.php';

// Check if the user is logged in and is a car rental agency
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "agency") {
    header("Location: login.php");
    exit();
}

// Retrieve agency's username from session
$agency_username = $_SESSION["username"];

// Get cars booked by the agency
$sql = "SELECT * FROM cars WHERE agency_username = '$agency_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display car details
        echo "<h2>{$row["model"]}</h2>";
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Customer Name</th>";
        echo "<th>Contact Email</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Get booked customers for this car
        $car_id = $row["car_id"];
        $bookedCustomersSql = "SELECT customers.customer_name, customers.contact_email
                               FROM bookings
                               JOIN customers ON bookings.customer_id = customers.customer_id
                               WHERE bookings.car_id = $car_id";
        $bookedCustomersResult = $conn->query($bookedCustomersSql);

        while ($customerRow = $bookedCustomersResult->fetch_assoc()) {
            // Display booked customer details
            echo "<tr>";
            echo "<td>{$customerRow["customer_name"]}</td>";
            echo "<td>{$customerRow["contact_email"]}</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }
} else {
    echo "No cars available for rent";
}

$conn->close();
?>

<!-- Add other details like dropdowns, start date, and Rent Car button -->
