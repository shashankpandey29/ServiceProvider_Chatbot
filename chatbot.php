 <?php
header('Content-Type: application/json');
include('config.php');

// Read user input
$data = json_decode(file_get_contents('php://input'), true);
$userMessage = $data['message'] ?? '';

if (!$userMessage) {
    echo json_encode(["error" => "No message received"]);
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$YOUR API KEY ";

$payload = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => "You are SpitiCare Assistant, a chatbot that helps users with home cleaning, plumbing, electrical, and pest control queries. Be friendly and helpful. User asked: $userMessage"]
            ]
        ]
    ]
];

// Send request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

// If cURL fails
if ($curlError) {
    echo json_encode(["error" => $curlError]);
    exit;
}

// Save API response for debugging
file_put_contents("gemini_debug.json", $response);

// Decode the response
$responseData = json_decode($response, true);

// Try to extract reply text safely
$botReply = "Sorry, I didn’t get a valid reply.";
if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $botReply = $responseData['candidates'][0]['content']['parts'][0]['text'];
} elseif (isset($responseData['candidates'][0]['output'][0]['content'])) {
    $botReply = $responseData['candidates'][0]['output'][0]['content'];
}

echo json_encode(["reply" => $botReply]);
?>
