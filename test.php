<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatBox Demo</title>
</head>

<body>
    <a href="http://facebook.com">Test</a>
    <h1>ChatBox Demo</h1>
    <div id="chatbox">
        <div id="chatlog"></div>
        <input type="text" id="userInput" placeholder="Nhập câu hỏi của bạn" onkeypress="handleKeyPress(event)">
        <button onclick="sendMessage()" class="btn btn-success btn-sm" id="sendButton">Gửi</button>
    </div>

    <script>
        function sendMessage() {
            var userInput = document.getElementById("userInput").value;
            var chatlog = document.getElementById("chatlog");
            var sendButton = document.getElementById("sendButton");
            sendButton.innerHTML = '<i class="far fa-spinner fa-spin"></i>';
            sendButton.disabled = true;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/chat", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    chatlog.innerHTML += "<p><strong>You:</strong> " + userInput + "</p>";
                    chatlog.innerHTML += "<p><strong>ChatBox:</strong> " + response + "</p>";
                    document.getElementById("userInput").value = "";
                    sendButton.disabled = false;
                }
            };
            xhr.send("message=" + userInput);
        }

        function handleKeyPress(event) {
            if (event.keyCode === 13) {
                sendMessage();
            }
        }
    </script>
</body>

</html>