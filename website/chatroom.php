<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
  ob_clean();
  header("Location: login.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>線上聊天室</title>
  <!-- 引入 Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    #chat-container {
      width: 2000px;
      /* 固定寬度 */
      height: 2000px;
      /* 固定高度 */
      margin: auto;
      /* 讓聊天室置中 */
      margin-top: 200px;
      /* 上方間距 */
    }

    #chat-box {
      height: 300px;
      border: 1px solid #ccc;
      overflow-y: scroll;
      margin-bottom: 10px;
    }

    #user-list {
      height: 300px;
      border: 1px solid #ccc;
      overflow-y: scroll;
      padding: 10px;
    }

    #message-input {
      width: 70%;
    }

    #logout-btn {
      position: fixed;
      top: 20px;
      right: 20px;
    }
  </style>
</head>

<body>

  <div id="chat-container" class="container-xl">
    <div class="row">
      <div class="col-md-8">
        <div id="chat-box" class="bg-light p-3"></div>

        <div class="input-group mb-3">
          <input type="text" id="message-input" class="form-control" placeholder="輸入訊息..." onkeydown="handleKeyPress(event)">
          <div class="input-group-append">
            <button class="btn btn-primary" onclick="sendMessage()">送出訊息</button>
            <button id="logout-btn" class="btn btn-danger" onclick="logout()">登出</button>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div id="user-list" class="bg-light">
          <!-- 顯示使用者資料的區域 -->
        </div>
      </div>
    </div>
  </div>

  <!-- 引入 Bootstrap JS 和 Popper.js -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- include jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script>
    setInterval(fetchMessages, 1000);

    function fetchMessages() {
      $.ajax({
        url: 'fetch_messages.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          // Check if data is not empty
          if (data.length > 0) {
            // Update the chat box with fetched messages
            var chatBox = $('#chat-box');
            chatBox.empty(); // Clear existing content
            data.forEach(function(message) {
              // Append each message to the chat box
              chatBox.append("<p><strong>" + message.timestamp + "/ " + message.username + ":</strong> " + message.message + "</p>");

            });
            // Scroll to the bottom of the chat box
            chatBox.scrollTop(chatBox[0].scrollHeight);
          } else {
            // If no messages found, display a message
            $('#chat-box').html("<p>No messages found.</p>");
          }
        },
        error: function(xhr, status, error) {
          console.error('Error fetching messages:', error);
        }
      });
    }


    function sendMessage() {
      var messageInput = document.getElementById('message-input');
      var message = messageInput.value;

      if (message.trim() !== '') {
        // 使用 fetch 來發送 POST 請求
        fetch('process_message.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'message=' + encodeURIComponent(message),
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            console.log('Message sent successfully:', data);

            // 發送訊息後，重新取得使用者資料
          })
          .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
          });

        var chatBox = document.getElementById('chat-box');
        var newMessage = document.createElement('p');
        newMessage.textContent = message;
        chatBox.appendChild(newMessage);

        // 清空輸入框
        messageInput.value = '';

        // 捲動到最底部
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    }

    function handleKeyPress(event) {
      if (event.key === 'Enter') {
        // 按下Enter時同時觸發sendMessage
        sendMessage();
      }
    }

    function logout() {
      // Use fetch to send a request to the server to clear the session
      fetch('logout.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.text();
        })
        .then(data => {
          console.log('Logout successful:', data);

          // After logging out, you may want to redirect the user to a login page
          window.location.href = 'login.html'; // Replace with the actual login page URL
        })
        .catch(error => {
          console.error('There was a problem with the fetch operation:', error);
        });
    }
  </script>

</body>

</html>