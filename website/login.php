
<?php
ob_start();
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: chatroom.php");
            ob_end_clean();
            exit();
        } else {
            $error = "無效的認證信息";
            header("Location: login.html");
        }
    } else {
        $error = "無效的認證信息";
        header("Location: register.html");
        $_SESSION['error'] = $error;
        exit();
    }
}

// 在 POST 請求區塊外顯示錯誤信息
if (isset($_SESSION['error'])) {
    echo '<div style="color: red;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
