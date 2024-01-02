
<?php
require 'db.php';
ob_start();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        ob_end_clean();
        header("Location: login.html");
        exit;
    }

    // 將訊息存入資料庫
    $insertQuery = "INSERT INTO messages (username, message) VALUES ('$username', '$message')";
    if ($conn->query($insertQuery) === TRUE) {
        // 發送訊息給所有連線的客戶端
        echo "<script>socket.emit('chat message', { username: '$username', message: '$message' });</script>";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}

// 關閉資料庫連線
$conn->close();
