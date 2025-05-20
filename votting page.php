<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Voting System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f7fa;
      padding: 0;
      margin: 0;
      padding-top: 80px; 
    }
    .container {
      max-width: 700px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2c3e50;
      margin-bottom: 20px;
    }
    .solution-box {
      background-color: #f0f2f5;
      padding: 20px;
      border-left: 4px solid #27ae60;
      margin-bottom: 20px;
      border-radius: 8px;
    }
    .vote-buttons {
      display: flex;
      gap: 15px;
      margin-bottom: 10px;
    }
    .vote-buttons button {
      padding: 10px 20px;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn-like { background-color: #27ae60; color: white; }
    .btn-dislike { background-color: #c0392b; color: white; }
    .status {
      font-weight: bold;
      margin-top: 10px;
    }
    textarea {
      width: 100%;
      margin-top: 15px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: vertical;
    }
    .btn-comment {
      margin-top: 10px;
      background-color: #34495e;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
    }
    .comments-section {
      margin-top: 20px;
    }
    .comment {
      background-color: #ecf0f1;
      padding: 8px 12px;
      border-radius: 6px;
      margin-top: 8px;
    }
    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 20px 10px;
      font-size: 14px;
      margin-top: 60px;
    }
    footer a {
      color: #737879;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
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
          <li class="nav-item"><a class="nav-link" href="live chart.php">Live Chat</a></li>
          <li class="nav-item"><a class="nav-link" href="report.php">Monthly Report</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <h2>Complaint Solution Voting</h2>
    <div class="solution-box">
      <p><strong>Solution:</strong> We have repaired the broken fan in Hall and cleaned the area. Please verify the condition.</p>
    </div>
    <div class="vote-buttons">
      <button class="btn-like" onclick="vote('like')">👍 Agree</button>
      <button class="btn-dislike" onclick="vote('dislike')">👎 Disagree</button>
    </div>
    <div class="status" id="voteStatus">Total Votes: 0 | Likes: 0 | Dislikes: 0</div>
    <div class="status" id="resultMessage"></div>
    <textarea id="commentInput" rows="3" placeholder="Write your feedback..."></textarea>
    <button class="btn-comment" onclick="addComment()">Submit Comment</button>
    <div class="comments-section" id="comments"></div>
  </div>
  <footer>
    <p>&copy; 2025 Fixit Portal. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script>
    function vote(type) {
      fetch('vote.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'vote_type=' + encodeURIComponent(type)
      }).then(() => fetchVotes());
    }
    function fetchVotes() {
      fetch('fetch_votes.php')
        .then(res => res.json())
        .then(data => {
          const likes = data.likes;
          const dislikes = data.dislikes;
          const totalVotes = likes + dislikes;
          const likePercent = totalVotes ? (likes / totalVotes) * 100 : 0;
          document.getElementById("voteStatus").textContent =
            `Total Votes: ${totalVotes} | Likes: ${likes} | Dislikes: ${dislikes}`;
          const result = document.getElementById("resultMessage");
          if (totalVotes >= 3) {
            result.textContent = likePercent >= 60 ? "✅ Solution accepted." : "⚠️ Less than 60% approval. Complaint reopened.";
            result.style.color = likePercent >= 60 ? "#27ae60" : "#c0392b";
          } else {
            result.textContent = "";
          }
        });
    }
    function addComment() {
      const input = document.getElementById("commentInput");
      const commentText = input.value.trim();
      if (commentText !== "") {
        fetch('comment.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'comment=' + encodeURIComponent(commentText)
        }).then(() => {
          input.value = "";
          fetchComments();
        });
      }
    }
    function fetchComments() {
      fetch('fetch_comments.php')
        .then(res => res.json())
        .then(data => {
          const commentsDiv = document.getElementById("comments");
          commentsDiv.innerHTML = "<h4>Comments:</h4>";
          data.forEach((cmt) => {
            const p = document.createElement("div");
            p.className = "comment";
            p.textContent = cmt;
            commentsDiv.appendChild(p);
          });
        });
    }
    fetchVotes();
    fetchComments();
    setInterval(fetchVotes, 2000);
    setInterval(fetchComments, 2000);
  </script>
</body>
</html>