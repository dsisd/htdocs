
<?php
require 'db.php';

$sql = "SELECT * FROM messages";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $messages = array(); // Create an array to store the messages

    while ($row = $result->fetch_assoc()) {
        $message = array(
            "timestamp" => $row["timestamp"],
            "username" => $row["username"],
            "message" => $row["message"]
        );

        $messages[] = $message; // Add each message to the array
    }

    echo json_encode($messages); // Encode the array into JSON
} else {
    echo json_encode(array("message" => "No messages found.")); // Encode a message in case no records are found
}

$conn->close();
?>
    