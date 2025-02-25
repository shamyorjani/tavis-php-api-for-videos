<?php
// Get the raw POST request body
$data = file_get_contents("php://input");
$decoded_data = json_decode($data, true);

if ($decoded_data) {
    file_put_contents("webhook.json", json_encode($decoded_data));
    // Respond to the sender (Postman, Tavus API, etc.)
    header("Content-Type: application/json");
    echo json_encode(["message" => "Webhook received"]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request"]);
}
?>
