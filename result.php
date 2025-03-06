<?php
$name = $_GET['name'] ?? 'N/A';
$email = $_GET['email'] ?? 'N/A';
$file = $_GET['file'] ?? '';
$conversation_url = $_GET['conversation_url'] ?? '';
$textContent = $_GET['textContent'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hormone Test Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@daily-co/daily-js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
    <div class="container mx-auto px-4 py-8 flex flex-col items-center">
        <h1 class="text-3xl font-bold mb-4 text-center bg-clip-text text-transparent bg-gradient-to-r from-gray-700 to-gray-500">
            Video Communication
        </h1>
        <!-- Live Conversation Section -->
        <div class="w-full max-w-4xl">
            <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Live Conversation
                </h2>
                <div class="flex flex-col items-center">
                    <p id="massage-conversation" class="text-center text-gray-600 mb-4">Start a new video conversation session.</p>
                </div>
            </div>
            <div id="video-call-container" class="w-full aspect-video bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl overflow-hidden shadow-lg border border-gray-200 flex items-center justify-center">
            </div>
        </div>

        <button id="endConversationBtn" class="bg-red-500 text-white px-4 py-2 rounded">
            End Conversation
        </button>
    </div>
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

    <script>
        function endConversation(conversationId) {
            fetch("lib/end_conversation.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "conversation_id=" + encodeURIComponent(conversationId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("Error: " + data.error);
                    } else {
                        alert("Conversation ended successfully!");
                        window.location.href = "index.php"; // Redirect after ending conversation
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Failed to end conversation.");
                });
        }

        document.getElementById("endConversationBtn").addEventListener("click", function() {
            const conversationUrlEnd = <?php echo json_encode($conversation_url); ?>;
            let conversationId = conversationUrlEnd.split("/").pop();
            endConversation(conversationId);
        });

        document.addEventListener("DOMContentLoaded", function() {
            const fileName = <?php echo json_encode($file); ?>; // Get the file from PHP
            const name = <?php echo json_encode($name); ?>; // Get the name from PHP
            const email = <?php echo json_encode($email); ?>; // Get the email from PHP
            const conversationUrl = <?php echo json_encode($conversation_url); ?>; // Get the conversation URL from PHP
            const textContent = <?php echo json_encode($textContent); ?>;
            console.log(conversationUrl);

            let conversationId = conversationUrl.split("/").pop();
            console.log(conversationId);
            // Start the conversation
            if (conversationUrl) {
                const container = document.getElementById('video-call-container');
                const callFrame = window.DailyIframe.createFrame(container, {
                    iframeStyle: {
                        width: '100%',
                        height: '100%',
                        border: 'none',
                    }
                });

                callFrame.join({
                    url: conversationUrl
                });

                document.getElementById("massage-conversation").innerText = "Conversation started successfully";
                document.getElementById("massage-conversation").className = "text-center text-green-600 mb-4";
            } else {
                document.getElementById("massage-conversation").innerText = "Error: Conversation URL not provided.";
                document.getElementById("massage-conversation").className = "text-center text-red-600 mb-4";
            }

            // Display the fetched text content
            const resultsContainer = document.getElementById("results-container");
            if (textContent) {
                resultsContainer.innerHTML = textContent;
            } else {
                resultsContainer.innerHTML =
                    "<p class='text-center text-red-600 py-8'>No valid content received.</p>";
            }
        });
    </script>
</body>

</html>