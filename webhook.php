<?php
// Get the raw POST request body
$data = file_get_contents("php://input");
$decoded_data = json_decode($data, true);

if ($decoded_data) {
    $video_id = $decoded_data["video_id"] ?? "unknown";
    $status = $decoded_data["status"] ?? "unknown";

    // Log received data
    echo("Received Webhook: Video ID: $video_id, Status: $status");

    // Handle different statuses
    if ($status === "ready") {
        echo("✅ Video $video_id is ready!");
    } elseif ($status === "queued") {
        echo("⚙️ Video $video_id is processing.");
    } elseif ($status === "failed") {
        $error_message = $decoded_data["error_message"] ?? "No reason provided";
        echo("❌ Video $video_id failed. Reason: $error_message");
    }

    // Respond to Tavus
    header("Content-Type: application/json");
    echo json_encode(["message" => "Webhook received"]);
} else {
    echo("Invalid webhook data received");
    http_response_code(400);
    echo json_encode(["error" => "Invalid request"]);
}

?>
