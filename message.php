<?php
    $user_id = $_SESSION["user"]["user_id"];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesajlaşma</title>
    <style>
        .mesajlasma-container {
            font-family: Arial, sans-serif;
            display: flex;
            height: 60vh;
            margin: 0;
            background: #f5f5f5;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 3px;
        }

        .mesajlasma-container .users {
            width: 30%;
            background: #ffffff;
            border-right: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            height: 100%;
            border-radius: 10px 0 0 10px;
            box-shadow: -3px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .mesajlasma-container .users h3 {
            text-align: center;
            color: #444;
        }

        .mesajlasma-container #userList li {
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            list-style: none;
            font-size: 16px;
        }

        .mesajlasma-container #userList li:hover {
            background: #e0e0e0;
            transform: scale(1.05);
        }

        .mesajlasma-container .chat-container {
            width: 70%;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            box-shadow: -3px 0px 10px rgba(0, 0, 0, 0.1);
            height: 100%;
            border-radius: 0 10px 10px 0;
        }

        .mesajlasma-container .message-header {
            background-color:rgb(233, 249, 250);
            color: black;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }

        .mesajlasma-container .messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            min-height: 0;
            scroll-behavior: smooth; /* Kaydırma animasyonu */
        }

        .mesajlasma-container .message {
            max-width: 60%;
            padding: 8px 12px;
            margin: 5px;
            border-radius: 12px;
            font-size: 14px;
            word-wrap: break-word;
        }

        .mesajlasma-container .sent {
            background: #dcf8c6;
            align-self: flex-end;
            text-align: right;
            border: 1px solid #b2e68c;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .mesajlasma-container .received {
            background: #fff;
            align-self: flex-start;
            text-align: left;
            border: 1px solid #ddd;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .mesajlasma-container .input-container {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #f8f8f8;
            border-radius: 0 0 10px 10px;
        }

        .mesajlasma-container input {
            flex: 1;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        .mesajlasma-container button {
            padding: 8px 15px;
            margin-left: 10px;
            border-radius: 8px;
            border: none;
            background: #4caf50;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        .mesajlasma-container button:hover {
            background: #45a049;
        }

    </style>
</head>
<body>

<div class="mesajlasma-container">
    <div class="users">
        <h3>Sohbetler</h3>
        <ul id="userList"></ul>
    </div>

    <div class="chat-container">
        <div class="message-header" id="chatHeader"></div>
        <div class="messages" id="messageContainer"></div>
        <div class="input-container">
            <input type="text" id="messageInput" placeholder="Mesajınızı yazın...">
            <button onclick="sendMessage()">Gönder</button>
        </div>
    </div>
</div>

<script>
    let loggedUserId;
    let selectedUserId = null;
    let selectedUserName = ''; 
    let socket;
    let offerId = null;

    loggedUserId = <?php echo $user_id; ?>

    if (!loggedUserId) {
        alert("Kullanıcı ID bulunamadı!");
        throw new Error("Kullanıcı ID belirtilmelidir.");
    }

    fetch('/api/users?sender=' + loggedUserId)
        .then(response => response.json())
        .then(users => {
            const userList = document.getElementById("userList");
            userList.innerHTML = ''; 

            if (users.length > 0) {
                users.forEach(user => {
                    if (user.user_id != loggedUserId) {
                        let li = document.createElement("li");
                        li.textContent = user.name;
                        li.onclick = () => selectUser(user.user_id, user.name);
                        userList.appendChild(li);
                    }
                });
            } else {
                let noUsersMessage = document.createElement("li");
                noUsersMessage.textContent = "Mesajlaşmak için kimse yok.";
                userList.appendChild(noUsersMessage);
            }
        });

    function selectUser(userId, userName) {
        selectedUserId = userId;
        selectedUserName = userName; 
        document.getElementById("chatHeader").textContent = selectedUserName;
        document.getElementById("messageContainer").innerHTML = "";
        loadMessages();
    }

    function loadMessages() {
        if (!selectedUserId) return;

        fetch(`/api/load-message?sender=${loggedUserId}&receiver=${selectedUserId}`)
            .then(response => response.json())
            .then(messages => {
                const container = document.getElementById("messageContainer");
                container.innerHTML = "";

                messages.forEach(msg => {
                    let div = document.createElement("div");
                    div.textContent = msg.message;
                    div.classList.add("message", msg.sender_id == loggedUserId ? "sent" : "received");
                    container.appendChild(div);

                    if (!offerId && msg.offer_id) {
                        offerId = msg.offer_id;
                    }
                });

                container.scrollTop = container.scrollHeight;
            });
    }

    function sendMessage() {
        if (!selectedUserId) {
            alert("Bir kullanıcı seçmelisiniz!");
            return;
        }

        let message = document.getElementById("messageInput").value;
        if (!message) return;

        let msgObj = { sender_id: loggedUserId, receiver_id: selectedUserId, message: message, offer_id: offerId };
        socket.send(JSON.stringify(msgObj));

        let div = document.createElement("div");
        div.textContent = message;
        div.classList.add("message", "sent");
        document.getElementById("messageContainer").appendChild(div);

        document.getElementById("messageInput").value = "";

        const container = document.getElementById("messageContainer");
        container.scrollTop = container.scrollHeight;
    }

    socket = new WebSocket("ws://localhost:8080");

    socket.onmessage = function(event) {
        let data = JSON.parse(event.data);
        if (data.receiver_id == loggedUserId && data.sender_id == selectedUserId) {
            let div = document.createElement("div");
            div.textContent = data.message;
            div.classList.add("message", "received");
            document.getElementById("messageContainer").appendChild(div);

            const container = document.getElementById("messageContainer");
            container.scrollTop = container.scrollHeight;
        }
    };
</script>

</body>
</html>
