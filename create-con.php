<?php

$data = [
    "replica_id" => "r79e1c033f",
    "persona_id" => "p2fbd605",
    "callback_url" => "https://yourwebsite.com/webhook",
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
        "x-api-key: 6a99b71394474386b407d8fe2a1cfccf"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}