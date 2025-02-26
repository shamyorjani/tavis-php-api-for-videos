<?php
// filepath: /d:/tavus-api-php/cloude-api.php

// Define the API key and endpoint

$api_endpoint = 'https://api.anthropic.com/v1/messages';

// Fetch the PDF file and encode it in base64
$pdf_url = './pdf/Constantine Lab results august 2023.pdf';
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
                    'text' => "Process this lab report and extract test names, values, and reference ranges. Identify results that are out of the normal range and display them using a visually appealing layout with Tailwind CSS.
                
                Design requirements:
                - Use a responsive, card-based layout with clear typography.
                - Include a horizontal bar with color-coded segments:
                  - Red for deficient
                  - Yellow for insufficient
                  - Green for optimal
                  - Yellow/Red for high/toxic
                - Place numeric markers on the bar for reference points (e.g., 0, 20, 30, 100, 150+).
                - Add a floating indicator (triangle + label) dynamically positioned based on the test result.
                - Ensure the design is clean, professional, and easy to read, suitable for medical reporting.
                - Include an interpretation section below, summarizing the test result and its significance in plain language.
                - The output must be pure HTML + Tailwind CSS (no React, JavaScript optional for dynamic positioning).
                
                Example Structure (Dummy Code):
                
                <div class='max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg'>
                    <h2 class='text-xl font-bold mb-4'>Test Name: Vitamin D, 25-OH, Total</h2>
                    
                    <div class='mt-4'>
                        <div class='flex justify-between text-sm mb-1'>
                            <span>0</span><span>20</span><span>30</span><span>100</span><span>150+</span>
                        </div>
                        
                        <!-- Gradient Bar -->
                        <div class='w-full h-8 rounded-lg overflow-hidden flex'>
                            <div class='bg-red-500 w-1/6'></div>
                            <div class='bg-yellow-400 w-1/12'></div>
                            <div class='bg-green-500 w-1/2'></div>
                            <div class='bg-yellow-400 w-1/4'></div>
                        </div>
                        
                        <!-- Floating Indicator -->
                        <div class='relative mt-4'>
                            <div class='absolute left-[40%]'>
                                <div class='w-0 h-0 border-l-8 border-r-8 border-b-8 border-l-transparent border-r-transparent border-b-blue-600 mx-auto'></div>
                                <div class='bg-blue-600 text-white px-2 py-1 rounded text-center'>
                                    <span class='font-bold'>33</span> ng/ml
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class='mt-6 p-4 bg-gray-100 rounded-lg'>
                        <h3 class='font-bold mb-2'>Result Interpretation:</h3>
                        <p>The result of <span class='font-bold'>33 ng/ml</span> is within the optimal reference range (30 - 100 ng/ml).</p>
                    </div>
                </div>
                ",
                ]
                ,
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
