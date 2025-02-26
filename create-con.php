<?php
header("Content-Type: application/json"); // Ensure response is JSON

$apiKeys = [
    "cad7d2c06ce84ca8829e3f5fa943ad19",
    "c879c61142f34ac9ad9a7dd0df0914e1",
    "8eba9af217f045c487402d055472d1fd",
    "ecf298b9b49d44b6af3105fefb5b3e60",
    "631e81891e0640c9a101d34e660aac1c",
    "f2fd7d6b98334876a9aeb9c356dcdb6f",
    "b76e537aec514f7db2e2689ecf13b713",
    "0679e55011b142f791dcf3738364f740",
    "6f7c9c5fc4eb4e2cb9cee6cbe48bc566",
    "1cf96102060e404fabc995cd4202222d",
    "1be12c1378c646f8ac83ea7175f09024",
    "02faa3a215c0440d89db88fc0319dec5",
    "293b8245a8b3468898915994315be7d2",
    "30f335aa14214445bbde4852f9bdc182",
    "418cda69d65447a1bf45fed5b616b83e",
    "585b7eb588ac499ca4b581ddc7d00bee",
    "585b7eb588ac499ca4b581ddc7d00bee",
    "6322896e9b7f4142810cfe3d771dd1d5",
    "e425b92eac614fcd98202313dcbfaded",
    "b66d98e4f7f84439aa87bccbde9e6ff7",
    "15e359a8b6dd40ae8df78b1accf649a8",
    "ca46457475b3437f84b98bb1cb444535",
    "50619c2e2c1a4bf985ab0b6be5e34cb4"
];

$data = [
    "replica_id" => "r79e1c033f",
    "persona_id" => "p2fbd605",
    "callback_url" => "https://e8f9-182-184-138-168.ngrok-free.app/webhook-con.php",
    "conversation_name" => "A Meeting with Hassaan",
    "conversational_context" => "You are about to talk to Hassaan, one of the cofounders of Tavus. He loves to talk about AI, startups, and racing cars.",
    "custom_greeting" => "Hey there Hassaan, long time no see!",
    "properties" => [
        "max_call_duration" => 3600,
        "participant_left_timeout" => 60,
        "participant_absent_timeout" => 300,
        "enable_recording" => true,
        "enable_transcription" => true,
        "apply_greenscreen" => true,
        "language" => "english",
    ]
];

foreach ($apiKeys as $index => $apiKey) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tavusapi.com/v2/conversations",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "x-api-key: $apiKey"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode(["error" => "cURL Error: $err"]);
        exit;
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['message']) && $responseData['message'] == "User has reached maximum concurrent conversations") {
            unset($apiKeys[$index]);
            $apiKeys = array_values($apiKeys);
            continue; // Try with the next API key
        } else {
            echo json_encode($responseData);
            exit;
        }
    }
}

// If no valid API key worked, return an error message
echo json_encode(["error" => "All API keys exhausted. No conversation created."]);
exit;
