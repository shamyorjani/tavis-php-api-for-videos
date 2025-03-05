<?php
$name = $_GET['name'] ?? 'N/A';
$email = $_GET['email'] ?? 'N/A';
$file = $_GET['file'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hormone Test Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@daily-co/daily-js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            light: '#f9fafb',
                            DEFAULT: '#f3f4f6',
                            dark: '#e5e7eb'
                        }
                    }
                }
            }
        }
    </script>



</head>

<body class="font-['Inter'] bg-gray-50 text-gray-800 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900">Hormone Panel Results</h1>
        </div>

        <div class="mb-6">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>File:</strong>
                <?php if ($file): ?>
                <a href="pdf/<?php echo htmlspecialchars($file); ?>" target="_blank" class="text-blue-500">View File</a>
                <?php else: ?>
                <span class="text-red-500">No file available</span>
                <?php endif; ?>
            </p>
        </div>

        <div id="results-container" class="space-y-8">
            <p class="text-center text-gray-500 py-12">Loading results...</p>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8 flex flex-col items-center">
        <h1
            class="text-3xl font-bold mb-4 text-center bg-clip-text text-transparent bg-gradient-to-r from-gray-700 to-gray-500">
            Video Communication
        </h1>
        <!-- Live Conversation Section -->
        <div class="w-full max-w-4xl">
            <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Live Conversation
                </h2>
                <div class="flex flex-col items-center">
                    <p id="massage-conversation" class="text-center text-gray-600 mb-4">Start a new video conversation
                        session.</p>
                </div>
            </div>
            <div id="video-call-container"
                class="w-full aspect-video bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl overflow-hidden shadow-lg border border-gray-200 flex items-center justify-center">
                <div class="text-center p-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4 opacity-70"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-600 text-lg">Your video call will appear here</p>
                    <p class="text-gray-500 text-sm mt-2">Click "Start Conversation" to begin</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileName = <?php echo json_encode($file); ?>; // Get the file from PHP
        const name = <?php echo json_encode($name); ?>; // Get the name from PHP
        const email = <?php echo json_encode($email); ?>; // Get the email from PHP

        // Process the file if it exists
        if (fileName) {
            fetchTestResults(fileName, name, email);
        } else {
            document.getElementById("results-container").innerHTML =
                "<p class='text-center text-red-600 py-8'>No file provided.</p>";
        }
    });

    // Function to create a conversation
    function createConversation(name, email, textContent) {
        document.getElementById("massage-conversation").innerText = "Creating conversation...";
        document.getElementById("massage-conversation").className = "text-center text-amber-600 mb-4 animate-pulse";

        fetch("create_conversation.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    textContent: textContent
                })
            })
            .then(response => response.text()) // Get response as text first
            .then(text => {
                console.log("Raw response:", text);
                try {
                    return JSON.parse(text); // Try parsing as JSON
                } catch (error) {
                    throw new Error("Invalid JSON response: " + text);
                }
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                console.log(data);
                const container = document.getElementById('video-call-container');
                container.innerHTML = ''; // Clear previous content

                const callFrame = window.DailyIframe.createFrame(container, {
                    iframeStyle: {
                        width: '100%',
                        height: '100%',
                        border: 'none',
                    }
                });

                callFrame.join({
                    url: data.conversation_url
                });

                document.getElementById("massage-conversation").innerText = "Conversation started successfully";
                document.getElementById("massage-conversation").className = "text-center text-green-600 mb-4";
            })
            .catch(error => {
                document.getElementById("massage-conversation").innerText = "Error: " + error.message;
                document.getElementById("massage-conversation").className = "text-center text-red-600 mb-4";
            });
    }

    // Function to fetch test results
    function fetchTestResults(fileName, name, email) {
        const resultsContainer = document.getElementById("results-container");
        resultsContainer.innerHTML =
            "<p class='text-center py-8'><span class='inline-block animate-spin mr-2'>⟳</span> Processing...</p>";

        fetch("cloude-api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    fileName: fileName
                })
            })
            .then(response => response.text())
            .then(text => {
                try {
                    console.log("Raw response:", text);

                    // Remove "API response: " prefix if present
                    const jsonText = text.replace(/^API response: /, '');
                    const data = JSON.parse(jsonText);

                    if (data.content && Array.isArray(data.content)) {
                        const textContent = data.content.find(item => item.type === "text");

                        if (textContent && textContent.text) {
                            console.log("Extracted text:", textContent.text); // Only log the extracted text
                            resultsContainer.innerHTML = textContent.text;
                            // createConversation(name, email, textContent.text); // Pass textContent to createConversation
                        } else {
                            resultsContainer.innerHTML =
                                "<p class='text-center text-red-600 py-8'>No valid content received.</p>";
                        }
                    } else {
                        resultsContainer.innerHTML =
                            "<p class='text-center text-red-600 py-8'>Invalid response format.</p>";
                    }
                } catch (error) {
                    console.error("Error parsing response:", error);
                    resultsContainer.innerHTML =
                        "<p class='text-center text-red-600 py-8'>An error occurred while processing the API response.</p>";
                }
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                resultsContainer.innerHTML =
                    "<p class='text-center text-red-600 py-8'>An error occurred while connecting to the server.</p>";
            });
    }
</script>
</body>

</html>
