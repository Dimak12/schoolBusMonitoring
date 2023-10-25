<?php
require'connectDB.php';

$busId = $_POST['bus_id'];

// Query to fetch latitude and longitude based on the bus ID
$sql = "SELECT latitude, longitude FROM bus_location WHERE bus_id = $busId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $coordinates = array('lat' => $row['latitude'], 'lng' => $row['longitude']);
    echo json_encode($coordinates);
} else {
    echo json_encode(array('lat' => 0, 'lng' => 0)); // Return default coordinates or an error message
}

$conn->close();
?>