
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat_app";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception) {
    echo "Could Not Connect With Database";
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
