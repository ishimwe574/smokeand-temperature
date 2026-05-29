
<?php
session_start();

/* ===== DATABASE CONNECTION (FIXED NAME) ===== */
$conn = new mysqli("localhost", "root", "", "smoke_moniter");

if ($conn->connect_error) {
    die("Database Error: " . $conn->connect_error);
}
/* ===== LOGOUT ===== */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: dashny.php");
    exit();
}

/* ===== LOGIN HANDLER ===== */
$error = "";

if (isset($_POST['login'])) {

    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user == "admin" && $pass == "1234") {

        $_SESSION['user'] = $user;

        header("Location: index.php");
        exit();

    } else {
        $error = "Wrong username or password!";
    }
}

/* =========================
   DASHBOARD (AFTER LOGIN)
========================= */
if (isset($_SESSION['user'])) {

    $sql = "SELECT * FROM sensor_data ORDER BY created_at DESC";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
body{
    font-family:Arial;
    margin:0;
    background:#f4f4f4;
}

.top{
    background:#111827;
    color:white;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

button{
    background:red;
    color:white;
    border:none;
    padding:8px 12px;
    cursor:pointer;
}

table{
    width:100%;
    background:white;
    border-collapse:collapse;
}

th,td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#111827;
    color:white;
}

.danger{color:red;font-weight:bold;}
.safe{color:green;font-weight:bold;}
</style>

</head>

<body>

<div class="top">
    <h2>Smart Security Dashboard</h2>
    <a href="index.php?logout=1"><button>Logout</button></a>
</div>

<h3 style="padding:10px;">Sensor Data</h3>

<table>

<tr>
    <th>ID</th>
    <th>Smoke</th>
    <th>Temperature</th>
    <th>Humidity</th>
    <th>Status</th>
    <th>Date</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {

    $status = ($row['smoke_level'] > 400)
        ? "<span class='danger'>DANGER</span>"
        : "<span class='safe'>SAFE</span>";

    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['smoke_level']}</td>
        <td>{$row['temperature']}</td>
        <td>{$row['humidity']}</td>
        <td>$status</td>
        <td>{$row['created_at']}</td>
    </tr>";
}
?>

</table>

</body>
</html>

<?php
exit();
}
?>

<!-- =========================
     LOGIN PAGE (DEFAULT)
========================= -->

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
body{
    font-family:Arial;
    background:#e5e7eb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:20px;
    border-radius:10px;
    width:300px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#111827;
    color:white;
    border:none;
    cursor:pointer;
}

.error{
    color:red;
}
</style>

</head>

<body>

<div class="box">

<h2>Login</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>

</form>

<p class="error"><?php echo $error; ?></p>

</div>

</body>
</html>