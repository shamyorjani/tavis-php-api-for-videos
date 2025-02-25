
<?php

$callback_url = " https://76c6-182-184-175-83.ngrok-free.app/webhook.php";
$data = [
    "replica_id" => "r084238898",
    "script" => "create a video on topic of AI explaining",
    "video_name" => "AI video",
    "callback_url" => $callback_url
];

$curl = curl_init();


curl_setopt_array($curl, [
  CURLOPT_URL => "https://tavusapi.com/v2/videos",
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