<?php

// Start the session to access the stored API key
if (!isset($_SESSION)) {
    session_start();
}

// Get the conversation ID from the POST request
$conversation_id = $_POST['conversation_id'] ?? '';

if (!$conversation_id) {
    echo json_encode(['error' => 'Conversation ID not provided.']);
    exit();
}

// Get the API key from the session
$api_key = $_SESSION['api_key'] ?? '';

if (!$api_key) {
    echo json_encode(['error' => 'API key not found in session.']);
    exit();
}

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://tavusapi.com/v2/conversations/$conversation_id/end",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => ["x-api-key: $api_key"],
    CURLOPT_VERBOSE => true, // Enable verbose output
]);

// Open a file to write the verbose output
$verbose = fopen('php://temp', 'w+');
curl_setopt($curl, CURLOPT_STDERR, $verbose);

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$err = curl_error($curl);

curl_close($curl);

// Rewind the verbose output file and output its contents
rewind($verbose);
$verbose_log = stream_get_contents($verbose);
fclose($verbose);

if ($err) {
    echo json_encode(['error' => 'cURL Error #:' . $err]);
} else {
    echo 'HTTP Status Code: ' . $http_code . "\n";
    echo 'Response from API: ' . $response . "\n";
    echo "Verbose information:\n" . $verbose_log . "\n";

    if ($http_code == 200) {
        echo json_encode(['message' => 'Conversation ended successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to end conversation. HTTP Status Code: ' . $http_code]);
    }
}
