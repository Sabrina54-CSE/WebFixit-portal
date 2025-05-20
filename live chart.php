<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Live Chat - Fixit Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
    }
    .chat-container {
      max-width: 900px;
      margin: 80px auto 40px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      height: 80vh;
    }
    .chat-header {
      background-color: #10318c;
      color: white;
      padding: 15px;
      font-weight: bold;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
    .chat-body {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      background-color: #f4f6fb;
    }
    .message {
      margin-bottom: 12px;
      padding: 10px 15px;
      border-radius: 15px;
      max-width: 70%;
      font-size: 14px;
    }
    .from-user {
      background-color: #dee2e6;
      align-self: flex-end;
      text-align: right;
    }
    .from-admin {
      background-color: #cce5ff;
      align-self: flex-start;
      text-align: left;
    }
    .chat-footer {
      display: flex;
      padding: 10px;
      border-top: 1px solid #ccc;
    }
    .chat-footer input {
      flex: 1;
      padding: 8px;
    }
    .chat-footer button {
      background-color: #10318c;
      color: white;
      border: none;
      padding: 8px 20px;
      margin-left: 10px;
      border-radius: 5px;
    }
    footer {
      background-color: #595d60;
      text-align: center;
      padding: 15px;
      color: white;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="Homepage.php">Fixit Portal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="Emergency alertpage.php">Emergency Alert</a></li>
          <li class="nav-item"><a class="nav-link" href="votting page.php">Vote on Issues</a></li>
          <li class="nav-item"><a class="nav-link" href="report.php">Monthly Report</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>
<div class="chat-container d-flex flex-column">
  <div class="chat-header">
    Live Support Chat
  </div>
  <div class="chat-body d-flex flex-column" id="chatBody"></div>
  <div class="chat-footer">
    <input type="text" id="chatInput" placeholder="Type your message...">
    <button onclick="sendMessage()">Send</button>
  </div>
</div>
<footer>
  &copy; 2025 Fixit Portal. All rights reserved.
</footer>
<script>
  const sender = "user";
  function fetchMessages() {
    fetch('fetch_messages.php')
      .then(response => response.json())
      .then(data => {
        const chatBody = document.getElementById("chatBody");
        chatBody.innerHTML = "";
        data.forEach(msg => {
          const div = document.createElement("div");
          div.className = "message " + (msg.sender === "admin" ? "from-admin" : "from-user align-self-end");
          div.textContent = msg.message;
          chatBody.appendChild(div);
        });
        chatBody.scrollTop = chatBody.scrollHeight;
      });
  }
  function sendMessage() {
    const input = document.getElementById("chatInput");
    const text = input.value.trim();
    if (!text) return;
    fetch('send_message.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'sender=' + encodeURIComponent(sender) + '&message=' + encodeURIComponent(text)
    }).then(() => {
      input.value = '';
      fetchMessages();
    });
  }
  setInterval(fetchMessages, 2000); 
  fetchMessages();
</script>
</body>
</html>
