<?php

$callback_url = " https://e676-182-184-175-83.ngrok-free.app/webhook.php";

$data = [
    "replica_id" => "r084238898",
    "script" => "create a video on topic of AI explaining the concepts of machine learning and deep learning in detail with examples",
    "video_name" => "AI video",
    "callback_url" => $callback_url
];

$headers = [
    "Content-Type: application/json",
    "x-api-key: ac84a67627354c2e849730f97181fd03"
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://tavusapi.com/v2/videos",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => $headers,
]);

$response = curl_exec($ch);
$error = curl_error($ch);

curl_close($ch);

if ($error) {
    echo "cURL Error: " . $error;
} else {
    echo "Response: " . $response;
}
?>
