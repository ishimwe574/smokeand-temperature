<?php

$conn = new mysqli("localhost", "root", "", "smoke_monitor");

$sql = "SELECT * FROM sensor_data ORDER BY created_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<title>Smoke & Temperature Dashboard</title>

<style>

body{
    font-family:Arial;
    background:#f4f4f4;
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th, td{
    border:1px solid #ccc;
    padding:12px;
    text-align:center;
}

th{
    background:#222;
    color:white;
}

.danger{
    color:red;
    font-weight:bold;
}

.safe{
    color:green;
    font-weight:bold;
}

</style>

</head>

<body>

<h1>Smoke & Temperature Monitoring System</h1>

<table>

<tr>
    <th>ID</th>
    <th>Smoke Level</th>
    <th>Temperature °C</th>
    <th>Humidity %</th>
    <th>Status</th>
    <th>Date & Time</th>
</tr>

<?php

while($row = $result->fetch_assoc()) {

    $status = "";

    if($row['smoke_level'] > 400){
        $status = "<span class='danger'>Smoke Detected</span>";
    } else {
        $status = "<span class='safe'>Normal</span>";
    }

    echo "<tr>
            <td>".$row['id']."</td>
            <td>".$row['smoke_level']."</td>
            <td>".$row['temperature']."</td>
            <td>".$row['humidity']."</td>
            <td>".$status."</td>
            <td>".$row['created_at']."</td>
          </tr>";
}

?>

</table>

</body>
</html>