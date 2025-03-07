<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $file = $_POST['file'] ?? '';
    $conversation_url = $_POST['conversation_url'] ?? '';
    $textContent = $_POST['textContent'] ?? '';

    // Store data in session or database if needed
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['file'] = $file;
    $_SESSION['conversation_url'] = $conversation_url;
    $_SESSION['textContent'] = $textContent;

    echo json_encode(['status' => 'success']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@daily-co/daily-js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        medical: {
                            50: '#e1f5fe',
                            100: '#b3e5fc',
                            200: '#81d4fa',
                            300: '#4fc3f7',
                            400: '#29b6f6',
                            500: '#039be5',
                            600: '#0288d1',
                            700: '#0277bd',
                            800: '#01579b',
                            900: '#014377',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 9999 !important;
            border-radius: 0 !important;
        }

        .normal-controls {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            gap: 0.5rem;
            z-index: 10000;
            transition: all 0.3s ease;
        }

        .fullscreen-controls {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            gap: 0.75rem;
            z-index: 10002;
            transition: all 0.3s ease;
        }
        @media (max-width: 992px) {
            #video-call-container {
            min-height: 420px;
            }
        }
    </style>
</head>

<body class="font-['Inter'] bg-gradient-to-br from-medical-50 to-white text-gray-800 min-h-screen">
    <div class="container mx-auto px-2 md:px-4 py-6">
        <!-- Header -->
        <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden mb-8 border border-gray-100">
            <div class="bg-gradient-to-r from-medical-700 to-medical-600 p-6 relative">
                <div class="absolute inset-0 bg-white/10 opacity-20 transform -skew-x-12"></div>
                <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center relative z-10">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Lab Report Analysis</h1>
                        <p class="text-medical-100 text-sm mt-1">Interactive consultation and results visualization</p>
                    </div>
                    <div class="mt-4 md:mt-0 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 border border-white/30">
                        <div class="flex items-center text-white text-sm">
                            <span class="font-semibold">Patient:</span>
                            <span class="ml-2"><?php echo htmlspecialchars($_SESSION['name'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white">
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-medical-600 mr-2"></i>
                        <span class="font-medium text-gray-700">Name:</span>
                        <span class="ml-2 text-gray-600"><?php echo htmlspecialchars($_SESSION['name'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-medical-600 mr-2"></i>
                        <span class="font-medium text-gray-700">Email:</span>
                        <span class="ml-2 text-gray-600"><?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-medical-600 mr-2"></i>
                        <span class="font-medium text-gray-700">Report:</span>
                        <?php if (!empty($_SESSION['file'])): ?>
                        <a href="pdf/<?php echo htmlspecialchars($_SESSION['file']); ?>" target="_blank"
                            class="ml-2 text-medical-600 hover:text-medical-700 hover:underline transition-colors flex items-center">
                            View Original PDF <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                        <?php else: ?>
                        <span class="ml-2 text-red-500">No file available</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <!-- Video Call Section -->
            <div class="lg:w-1/2 flex flex-col">
                <div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-200 mb-4">
                    <div class="bg-gradient-to-r from-medical-600 to-medical-500 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-video mr-2"></i>
                            Personalized Consultation
                        </h2>
                    </div>

                    <div class="p-4 relative">
                        <div id="massage-conversation"
                            class="text-center text-gray-600 mb-4 py-2 px-4 rounded-lg bg-gray-50 border border-gray-100">
                            Initializing video consultation...
                        </div>

                        <div class="relative">
                            <!-- Video container without the loader -->
                            <div id="video-call-container"
                                class="w-full aspect-[1] sm:aspect-[1] xl:aspect-[16/9] bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg overflow-hidden shadow-md border border-gray-300">
                            </div>

                            <!-- No loader here -->
                        </div>

                        <!-- Video controls - position will change with fullscreen toggle -->
                        <div id="video-controls" class="normal-controls">
                            <button id="fullscreenBtn"
                                class="bg-black/40 hover:bg-black/60 text-white p-2 rounded-full transition-all duration-200 backdrop-blur-sm">
                                <i class="fas fa-expand"></i>
                            </button>

                            <button id="endConversationBtn"
                                class="bg-red-500/80 hover:bg-red-600 text-white px-3 py-2 rounded-full transition-all duration-200 backdrop-blur-sm flex items-center">
                                <i class="fas fa-phone-slash mr-1"></i>
                                <span>End</span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-500 bg-medical-50 rounded-lg p-3">
                        <p class="flex items-start">
                            <i class="fas fa-info-circle text-medical-600 mt-1 mr-2"></i>
                            <span>Speak with a healthcare professional about your lab results and get personalized
                                insights.</span>
                        </p>
                    </div>
                </div>
            </div>
             <!-- Results Section -->
        <div class="lg:w-1/2">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-medical-600 to-medical-500 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Visualized Results
                    </h2>
                </div>

                <div class="p-2 md:px-6 overflow-y-auto max-h-[70vh]">
                    <div id="results-container" class="space-y-8">
                        <div class="flex items-center justify-center py-12">
                            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-medical-600">
                            </div>
                            <span class="ml-3 text-gray-600">Processing results...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        
    </div>

    <!-- Footer -->
    <div class="max-w-7xl mx-auto mt-8 text-center text-sm text-gray-500">
        <p>Analysis powered by AI technology | &copy; <?php echo date('Y'); ?> Lab Report Analysis</p>
    </div>
    </div>

    <script>
        // Track fullscreen state
        let isFullscreen = false;

        function toggleFullscreen() {
            const videoContainer = document.getElementById('video-call-container');
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            const videoControls = document.getElementById('video-controls');

            if (isFullscreen) {
                // Exit fullscreen mode
                videoContainer.classList.remove('fullscreen');
                fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';

                // Switch to normal absolute positioning
                videoControls.className = 'normal-controls';
            } else {
                // Enter fullscreen mode
                videoContainer.classList.add('fullscreen');
                fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';

                // Switch to fixed positioning for fullscreen
                videoControls.className = 'fullscreen-controls';
            }

            isFullscreen = !isFullscreen;
        }

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
                    console.log(data);
                    if (data.error) {
                        const messageEl = document.getElementById("massage-conversation");
                        messageEl.innerText = "Error: " + data.error;
                        messageEl.className =
                            "text-center text-red-600 mb-4 py-2 px-4 rounded-lg bg-red-50 border border-red-200";

                        setTimeout(() => {
                            window.location.href = "index.php";
                        }, 3000);
                    } else {
                        const messageEl = document.getElementById("massage-conversation");
                        messageEl.innerText = "Consultation ended successfully";
                        messageEl.className =
                            "text-center text-green-600 mb-4 py-2 px-4 rounded-lg bg-green-50 border border-green-200";

                        setTimeout(() => {
                            window.location.href = "index.php";
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    const messageEl = document.getElementById("massage-conversation");
                    messageEl.innerText = "Failed to end consultation";
                    messageEl.className =
                        "text-center text-red-600 mb-4 py-2 px-4 rounded-lg bg-red-50 border border-red-200";
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Add event listener for fullscreen button
            document.getElementById("fullscreenBtn").addEventListener("click", toggleFullscreen);

            // Add event listener for ESC key to exit fullscreen
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isFullscreen) {
                    toggleFullscreen();
                }
            });

            // Add event listener for end conversation button
            document.getElementById("endConversationBtn").addEventListener("click", function() {
                const conversationUrlEnd = <?php echo json_encode($_SESSION['conversation_url'] ?? ''); ?>;
                if (conversationUrlEnd) {
                    let conversationId = conversationUrlEnd.split("/").pop();
                    endConversation(conversationId);
                } else {
                    alert("No active consultation to end");
                    window.location.href = "index.php";
                }
            });

            const fileName = <?php echo json_encode($_SESSION['file'] ?? ''); ?>;
            const name = <?php echo json_encode($_SESSION['name'] ?? ''); ?>;
            const email = <?php echo json_encode($_SESSION['email'] ?? ''); ?>;
            const conversationUrl = <?php echo json_encode($_SESSION['conversation_url'] ?? ''); ?>;
            const textContent = <?php echo json_encode($_SESSION['textContent'] ?? ''); ?>;

            // Start video consultation
            if (conversationUrl) {
                const container = document.getElementById('video-call-container');
                const callFrame = window.DailyIframe.createFrame(container, {
                    iframeStyle: {
                        width: '100%',
                        height: '100%',
                        border: 'none',
                        background: 'transparent'
                    }
                });

                callFrame.join({
                    url: conversationUrl
                });

                const messageEl = document.getElementById("massage-conversation");
                messageEl.innerText = "Consultation active - Speak with your healthcare professional";
                messageEl.className =
                    "text-center text-green-600 mb-4 py-2 px-4 rounded-lg bg-green-50 border border-green-200";
            } else {
                const messageEl = document.getElementById("massage-conversation");
                messageEl.innerText = "Error: Unable to start consultation";
                messageEl.className =
                    "text-center text-red-600 mb-4 py-2 px-4 rounded-lg bg-red-50 border border-red-200";
            }

            // Display the fetched text content
            const resultsContainer = document.getElementById("results-container");
            if (textContent) {
                resultsContainer.innerHTML = textContent;
            } else {
                resultsContainer.innerHTML =
                    "<div class='flex flex-col items-center justify-center py-12 text-gray-500'>" +
                    "<i class='fas fa-exclamation-circle text-red-500 text-4xl mb-4'></i>" +
                    "<p class='text-center'>No results data available for this report.</p>" +
                    "</div>";
            }
        });
    </script>
</body>

</html>
