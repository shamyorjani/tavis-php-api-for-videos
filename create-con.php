<?php

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
        "x-api-key: 247429342d8a4927b757b41f374878a5"
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