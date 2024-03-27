<div class="open-chatbot ">
    <div class="chatbot hidden" id="chatbox">
        <header>
            <h2>ChatBot</h2>
        </header>

        <ul class="chatbox" id="chatlog">
            <li class="chat coming">
                <p>Xin chào ! Nếu có câu hỏi nào liên quan đến trường, tuyển sinh vui lòng hỏi tại đâu nhé</p>
            </li>

           
        </ul>

        <div class="chat-input">
            <input  onkeypress="handleKeyPress(event)"  id="userInput"  type="text" placeholder="Gửi tin nhắn. . ." required>

            <!-- <div onclick="sendMessage()" id="sendButton" class="material-symbols-outlined" style="display: flex; align-items: center;"> -->
            <img src="/assets/chat.svg" style="height:30px"></div>

            <button onclick="sendMessage()" id="sendButton" class="material-symbols-outlined">send</button>

        </div>
    </div>

    <button onclick="hide()" class="chatbot-icon">
        <span class="material-symbols-outlined">mode_comment</span>
        <span class="material-symbols-outlined">close</span>

    </button>


    <script>
        function hide() {
            var element = document.querySelector('.chatbot');
            element.classList.toggle('hidden');


        }
    </script>
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
                    chatlog.innerHTML += ' <li class="chat user"><p>' + userInput + '</p></li>';
                    chatlog.innerHTML += ' <li class="chat coming"><p>' + response + '</p></li>';
                    document.getElementById("userInput").value = "";
                    // Enable the send button after receiving the response
                    sendButton.disabled = false;
                    chatlog.scrollTop = chatlog.scrollHeight;
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
</div>