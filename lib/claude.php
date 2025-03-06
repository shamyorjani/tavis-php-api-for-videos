<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

header('Content-Type: application/json'); // Ensure response is JSON

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$api_key = $_ENV['ANTHROPIC_API_KEY'];

$models = ['claude-3-7-sonnet-20250219', 'claude-3-7-sonnet-latest'];

if (!$api_key) {
    echo json_encode(['success' => false, 'error' => 'API Key not found. Make sure the .env file is loaded correctly.']);
    exit();
}

$api_endpoint = 'https://api.anthropic.com/v1/messages';

// Get the file name from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$file_name = $data['fileName'] ?? '';

if (!$file_name) {
    echo json_encode(['success' => false, 'error' => 'File name not provided.']);
    exit();
}

// Fetch the PDF file and encode it in base64
$pdf_url = __DIR__ . '/../pdf/' . basename($file_name);
$pdf_content = file_get_contents($pdf_url);
$pdf_base64 = base64_encode($pdf_content);

if ($pdf_base64 === false) {
    echo json_encode(['success' => false, 'error' => 'Failed to read the PDF file.']);
    exit();
}

// Function to make API request
function callClaudeAPI($model, $api_key, $api_endpoint, $pdf_base64)
{
    $request_data = [
        'model' => 'claude-3-7-sonnet-20250219',
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
                        'text' => "Extract all test names, values, and reference ranges from this lab report. For each test result, create a visualization in the following format:

<div class='max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md'>
    <h2 class='text-xl font-bold mb-4'>[TEST NAME]</h2>
    <div class='mt-4'>
        <div class='flex justify-between text-sm mb-1 relative'>
            <span>0</span>
            <span class='absolute' style='left: calc((([RED_TO_YELLOW_VALUE] / [MAX_VALUE]) * 100%))'>[RED_TO_YELLOW_VALUE]</span>
            <span class='absolute' style='left: calc((([YELLOW_TO_GREEN_VALUE] / [MAX_VALUE]) * 100%))'>[YELLOW_TO_GREEN_VALUE]</span>
            <span class='absolute' style='left: calc((([GREEN_TO_YELLOW_VALUE] / [MAX_VALUE]) * 100%))'>[GREEN_TO_YELLOW_VALUE]</span>
            <span class='absolute' style='left: calc((([YELLOW_TO_RED_VALUE] / [MAX_VALUE]) * 100%))'>[YELLOW_TO_RED_VALUE]</span>
            <span class='absolute' style='left: calc((([RED_TO_RED_VALUE] / [MAX_VALUE]) * 100%))'>[RED_TO_RED_VALUE]</span>
            <span>[MAX_VALUE]</span>
        </div>

        <div class='w-full h-8 rounded-lg overflow-hidden flex'>
            <div class='bg-red-500' style='width: calc((([RED_TO_YELLOW_VALUE] / [MAX_VALUE]) * 100%))'></div>
            <div class='bg-yellow-400' style='width: calc((([YELLOW_TO_GREEN_VALUE] - [RED_TO_YELLOW_VALUE]) / [MAX_VALUE]) * 100%))'></div>
            <div class='bg-green-500' style='width: calc((([GREEN_TO_YELLOW_VALUE] - [YELLOW_TO_GREEN_VALUE]) / [MAX_VALUE]) * 100%))'></div>
            <div class='bg-yellow-400' style='width: calc((([YELLOW_TO_RED_VALUE] - [GREEN_TO_YELLOW_VALUE]) / [MAX_VALUE]) * 100%))'></div>
            <div class='bg-red-500' style='width: calc((([MAX_VALUE] - [YELLOW_TO_RED_VALUE]) / [MAX_VALUE]) * 100%))'></div>
        </div>

        <div class='relative mt-1 h-10'>
            <div class='absolute transform -translate-x-1/2' style='left: calc((([RESULT_VALUE] / [MAX_VALUE]) * 100%))'>
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

Please follow these specific rules for visualization:
1. Use precise calc() expressions to position all elements exactly.
2. Ensure all scale markers show distinct values.
3. The green zone must exactly correspond to the reference range.
4. Calculate widths for all color zones using the formula: calc((([END_VALUE] - [START_VALUE]) / [MAX_VALUE]) * 100%).
5. Position the result indicator using: calc((([RESULT_VALUE] / [MAX_VALUE]) * 100%)).
6. If the range value is less than 200 mg/dL, the scale should be from 0 to 200, with this entire range highlighted in green. If the range value exceeds 200 mg/dL, only the portion up to 200 should be green, while values beyond 200 should be marked as out of range.
7. For result indicator color:
   - Use green if the result is within the reference range.
   - Use yellow if the result is slightly outside the reference range.
   - Use red if the result is significantly outside the reference range.
8. Create separate visualizations for each test result found in the report.
9. Only output the HTML code without any explanations or markdown.
10. Do not repeat the last value in the scale.
11. Ensure the yellow zone does not exceed 20% of the scale.
12. Divide the last value by 96 for precise positioning.
13. Add values at the start of each color change in the bar.
14. Adjust the scale length to accommodate the result value if it exceeds the reference range.
15. Label values only where there is a color change on the scale.
16. Change the scale value when changing the color.
17. Display all test results if the document contains 1 to 5 tests, regardless of whether the values are out of range or not.
18. Only show test results that are out of range if the document contains more than 5 tests.
19. width: calc((([45 - 23.0]) / 45) * 100%).<= this is invalid property use calc((([45 - 23.0]) / 45) * 100%) instead.
20. <div class='w-full h-8 rounded-lg overflow-hidden flex'>
            <div class='bg-red-500' style='width: calc((4.1 / 45) * 100%)'></div>
            <div class='bg-green-500' style='width: calc((23.0 - 4.1) / 45) * 100%)'></div>
            <div class='bg-red-500' style='width: calc((45 - 23.0) / 45) * 100%)'></div>
        </div> this is wrong syntax use this insteadOf <div class='w-full h-8 rounded-lg overflow-hidden flex'>
            <div class='bg-red-500' style='width: calc((4.1 / 45) * 100%)'></div>
            <div class='bg-green-500' style='width: calc(((23.0 - 4.1) / 45) * 100%)'></div>
            <div class='bg-red-500' style='width: calc(((45 - 23.0) / 45) * 100%)'></div>
            </div>
21. <div class='flex justify-between text-sm mb-1 relative'>
            <span>0</span>
            <span class='absolute' style='left: calc((20 / 150) * 100%)'>20</span>
            <span class='absolute' style='left: calc((30 / 150) * 100%)'>30</span>
            <span class='absolute' style='left: calc((100 / 150) * 100%)'>100</span>
            <span class='absolute' style='left: calc((150 / 150) * 100%)'>150</span>
            <span>150</span>
        </div> this is wrong syntax use this insteadOf <div class='flex justify-between text-sm mb-1 relative'>
            <span>0</span>
            <span class='absolute' style='left: calc((20 / 150) * 100%)'>20</span>
            <span class='absolute' style='left: calc((30 / 150) * 100%)'>30</span>
            <span class='absolute' style='left: calc((100 / 150) * 100%)'>100</span>
            <span class='absolute' style='left: calc((150 / 150) * 97%)'>150</span> do not add double last value.
            21. If the result is EX: <div class='absolute transform -translate-x-1/2' style='left: calc((57 / 80) * 100%)'>, do not show the result value on the top result bar <span class='absolute' style='left: calc((57 / 80) * 97%)'>57</span>.
            22. If all test results are within the reference range and there are no out-of-range values, do not create any graphs. Instead, append a message like 'All test results are within the reference range No further action is required.' to the HTML using Tailwind CSS for styling. do say that i am using tailwind css html etc.
            23. Since this lab report includes more than five tests and contains multiple out-of-range values, I will ensure that all abnormal results are displayed without exception.
            24. make a graph of every out off range and or red value in the report. MOST IMPORTANI",
                    ],
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'x-api-key: ' . $api_key, 'anthropic-version: 2023-06-01']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);

    // Execute the cURL request and get the response
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL session
    curl_close($ch);

    return [$response, $http_code];
}

// Try both models
foreach ($models as $model) {
    [$response, $http_code] = callClaudeAPI($model, $api_key, $api_endpoint, $pdf_base64);

    // Decode response
    $decoded_response = json_decode($response, true);

    // If the API is overloaded, try the next model
    if (isset($decoded_response['error']['type']) && $decoded_response['error']['type'] === 'overloaded_error') {
        continue;
    }

    // If the response is valid, return it
    echo 'API response: ' . $response;
    exit();
}

// If all models fail, return an error
echo json_encode(['success' => false, 'error' => 'All models are overloaded. Please try again later.']);
exit();
?>
