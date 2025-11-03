document.getElementById("send-btn").addEventListener("click", sendMessage);
document.getElementById("user-input").addEventListener("keypress", function(e) {
  if (e.key === "Enter") sendMessage();
});

async function sendMessage() {
  const userInput = document.getElementById("user-input");
  const chatBox = document.getElementById("chat-box");

  const message = userInput.value.trim();
  if (!message) return;

  // Display user message
  chatBox.innerHTML += `<div class="chat-message user"><b>You:</b> ${message}</div>`;
  userInput.value = "";

  // Scroll to bottom
  chatBox.scrollTop = chatBox.scrollHeight;

  // Send to PHP backend
  const response = await fetch("chatbot.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "message=" + encodeURIComponent(message)
  });

  const data = await response.json();

  // Display bot reply
  chatBox.innerHTML += `<div class="chat-message bot"><b>Bot:</b> ${data.reply}</div>`;
  chatBox.scrollTop = chatBox.scrollHeight;
}
