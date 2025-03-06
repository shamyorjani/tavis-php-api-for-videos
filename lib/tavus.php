<?php
header('Content-Type: application/json'); // Ensure response is JSON

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'] ?? 'N/A';
$email = $data['email'] ?? 'N/A';
$textContent = $data['textContent'] ?? '';

$apiKeys = ['1cf96102060e404fabc995cd4202222d', '1be12c1378c646f8ac83ea7175f09024', '02faa3a215c0440d89db88fc0319dec5', '293b8245a8b3468898915994315be7d2', '30f335aa14214445bbde4852f9bdc182', '418cda69d65447a1bf45fed5b616b83e', '585b7eb588ac499ca4b581ddc7d00bee', '585b7eb588ac499ca4b581ddc7d00bee', '6322896e9b7f4142810cfe3d771dd1d5', 'e425b92eac614fcd98202313dcbfaded', 'b66d98e4f7f84439aa87bccbde9e6ff7', '15e359a8b6dd40ae8df78b1accf649a8', 'ca46457475b3437f84b98bb1cb444535', '50619c2e2c1a4bf985ab0b6be5e34cb4'];

$data = [
    'replica_id' => 'r0b262e2065e',
    'persona_id' => 'p2fbd605',
    'callback_url' => 'https://e8f9-182-184-138-168.ngrok-free.app/lib/tavus_wh.php',
    'conversation_name' => 'A Meeting with ' . $name,
    'conversational_context' => 'You are about to talk to ' . $name . '. this my reports you will explain this => ' . $textContent . ' Please be respectful and professional do not tak about html and tailwind code just talk about report details.',
    'custom_greeting' => 'Hi ' . $name . ', my name is Sanjay and I am your health concierge. I am not a doctor and I am not here to diagnose you, rather I will give you information about your lab results and provide you an opportunity to ask questions so that you can be more informed about your health. After our conversation, you can also schedule a call with a doctor via the link below. so lets get started..... and then go right into the out of range values. After reviewing the values, ask, "so, what questions do you have',
    'properties' => [
        'max_call_duration' => 3600,
        'participant_left_timeout' => 60,
        'participant_absent_timeout' => 300,
        'enable_recording' => true,
        'enable_transcription' => true,
        'apply_greenscreen' => false,
        'language' => 'english',
    ],
];

foreach ($apiKeys as $index => $apiKey) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://tavusapi.com/v2/conversations',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', "x-api-key: $apiKey"],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode(['error' => "cURL Error: $err"]);
        exit();
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['message']) && $responseData['message'] == 'User has reached maximum concurrent conversations') {
            unset($apiKeys[$index]);
            $apiKeys = array_values($apiKeys);
            continue; // Try with the next API key
        } elseif (!isset($responseData['conversation_id'])) {
            unset($apiKeys[$index]);
            $apiKeys = array_values($apiKeys);
            continue;
        } elseif (isset($responseData['message']) && $responseData['message'] == 'The user is out of conversational credits') {
            unset($apiKeys[$index]);
            $apiKeys = array_values($apiKeys);
            continue;
        } else {
            // save api key into session
            if (!isset($_SESSION)) {
                session_start();
                $_SESSION['api_key'] = $apiKey;
            } else if (isset($_SESSION)) {
                if ($_SESSION['api_key'] != $apiKey) {
                    // clear session
                    session_unset();
                    session_destroy();
                    session_start();
                    $_SESSION['api_key'] = $apiKey;
                }
            }

            echo json_encode($responseData);
            exit();
        }
    }
}

echo json_encode(['error' => 'All API keys exhausted. No conversation created.']);
?>