<?php
// filepath: /d:/tavus-api-php/cloude-api.php

// Include the Composer autoload file
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$api_key = $_ENV['ANTHROPIC_API_KEY'];

if (!$api_key) {
    die("Error: API Key not found. Make sure the .env file is loaded correctly.");
}

$api_endpoint = 'https://api.anthropic.com/v1/messages';

// Get the file name from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$file_name = $data['fileName'] ?? '';

if (!$file_name) {
    die("Error: File name not provided.");
}

// Fetch the PDF file and encode it in base64
$pdf_url = './pdf/' . $file_name;
$pdf_content = file_get_contents($pdf_url);
$pdf_base64 = base64_encode($pdf_content);

if ($pdf_base64 === false) {
    echo 'Failed to read the PDF file';
    exit();
}

// Create the JSON request
$request_data = [
    'model' => 'claude-3-5-haiku-20241022',
    'max_tokens' => 8192,
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'document',
                    'source' => [
                        'type' => 'base64',
                        'media_type' => 'application/pdf',
                        'data' => $pdf_base64,
                    ],
                ],
                [
                    'type' => 'text',
                    'text' => "Extract all test names, values, and reference ranges from this lab report. For each test result, create a visualization matching exactly this format:

<div class='max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md'>
    <h2 class='text-xl font-bold mb-4'>[TEST NAME]</h2>
    <div class='mt-4'>
        <div class='flex justify-between text-sm mb-1 relative'>
            <!-- Never repeat the same value, show only distinct values -->
            <span>0</span>
            <span class='absolute' style='left: calc(([RED_TO_YELLOW_VALUE] / [MAX_VALUE]) * 100%)'>[RED_TO_YELLOW_VALUE]</span>
            <span class='absolute' style='left: calc(([YELLOW_TO_GREEN_VALUE] / [MAX_VALUE]) * 100%)'>[YELLOW_TO_GREEN_VALUE]</span>
            <span class='absolute' style='left: calc(([GREEN_TO_YELLOW_VALUE] / [MAX_VALUE]) * 100%)'>[GREEN_TO_YELLOW_VALUE]</span>
            <span class='absolute' style='left: calc(([YELLOW_TO_RED_VALUE] / [MAX_VALUE]) * 100%)'>[YELLOW_TO_RED_VALUE]</span>
            <span class='absolute' style='left: calc(([RED_TO_RED_VALUE] / [MAX_VALUE]) * 100%)'>[YELLOW_TO_RED_VALUE]</span>
            <span>[MAX_VALUE]</span>
        </div>

        <!-- Gradient Bar -->
        <div class='w-full h-8 rounded-lg overflow-hidden flex'>
            <!-- Red zone (deficient) -->
            <div class='bg-red-500' style='width: calc(([RED_TO_YELLOW_VALUE] / [MAX_VALUE]) * 100%)'></div>
            <!-- Yellow zone (insufficient) -->
            <div class='bg-yellow-400' style='width: calc((([YELLOW_TO_GREEN_VALUE] - [RED_TO_YELLOW_VALUE]) / [MAX_VALUE]) * 100%)'></div>
            <!-- Green zone (optimal) - must span from low ref to high ref -->
            <div class='bg-green-500' style='width: calc((([GREEN_TO_YELLOW_VALUE] - [YELLOW_TO_GREEN_VALUE]) / [MAX_VALUE]) * 100%)'></div>
            <!-- Yellow zone (high) -->
            <div class='bg-yellow-400' style='width: calc((([YELLOW_TO_RED_VALUE] - [GREEN_TO_YELLOW_VALUE]) / [MAX_VALUE]) * 100%)'></div>
            <!-- Red zone (toxic) -->
            <div class='bg-red-500' style='width: calc(([MAX_VALUE] - [YELLOW_TO_RED_VALUE]) / [MAX_VALUE]) * 100%'></div>
        </div>

        <!-- Floating Indicator -->
        <div class='relative mt-1 h-10'>
            <div class='absolute transform -translate-x-1/2' style='left: calc(([RESULT_VALUE] / [MAX_VALUE]) * 100%)'>
                <div class='border-8 border-transparent border-t-[STATUS_COLOR]-600 w-0 h-0 mx-auto'></div>
                <div class='bg-[STATUS_COLOR]-600 text-white px-2 py-1 rounded text-center'>
                    <span class='font-bold'>[RESULT_VALUE]</span> [UNIT]
                </div>
            </div>
        </div>
    </div>
    
    <div class='mt-6 p-4 bg-gray-100 rounded-lg'>
        <h3 class='font-bold mb-2'>Result Interpretation:</h3>
        <p>The result of <span class='font-bold'>[RESULT_VALUE] [UNIT]</span> is [INTERPRETATION] ([REFERENCE_RANGE]).</p>
    </div>
</div>

Follow these specific rules for visualization:
1. Use precise calc() expressions to position ALL elements exactly (use format: calc(([VALUE] / [MAX_VALUE]) * 100%))
2. Never repeat the same value on the scale - ensure all scale markers show distinct values
3. Ensure the green zone exactly corresponds to the reference range (e.g., 30-100)
4. For ALL color zones, calculate widths using the formula: calc(([END_VALUE] - [START_VALUE]) / [MAX_VALUE] * 100%)
5. For the result indicator position, use: calc(([RESULT_VALUE] / [MAX_VALUE]) * 100%)
6. For result indicator color:
   - Use green if result is within reference range
   - Use yellow if result is in yellow zones (slightly outside reference range)
   - Use red if result is in red zones (significantly outside reference range)
7. Create separate visualizations for each test result found in the report
8. Only output the HTML code without any explanations or markdown
9. do not reapeate the last value in the scale
10. duration if yellow should not be more than 20% of the scale ex total in 150 yellow should be 10 to 30 and 100 to 120.
11. last value should divide by 96 ex: left: calc((150 / 150)* 96%);
12. add if yellow start form 100 to end it to 120 calc(((150 - 130) / 150) * 100%)
13. start red form 120 to 150 width: calc((128 - 98) / 150* 100%);



Make sure to always use the exact calc() expressions shown above to ensure precise positioning of all elements based on the actual values. The result indicator must be positioned at the exact percentage position based on its value relative to the max scale value.",
                ]
            ],
        ],
    ],
];

// Convert the request data to JSON
$request_json = json_encode($request_data);

// Initialize cURL
$ch = curl_init($api_endpoint);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $api_key,
    'anthropic-version: 2023-06-01'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);

// Execute the cURL request and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Print the response
    echo 'API response: ' . $response;
}

// Close the cURL session
curl_close($ch);
?>