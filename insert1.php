<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smoke_monitor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$smoke = $_GET['smoke'];
$temperature = $_GET['temperature'];
$humidity = $_GET['humidity'];

$sql = "INSERT INTO sensor_data
(smoke_level, temperature, humidity)
VALUES
('$smoke', '$temperature', '$humidity')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

?>