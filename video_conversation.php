<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Communication Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script crossorigin src="https://unpkg.com/@daily-co/daily-js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            light: '#4F46E5',
                            DEFAULT: '#4338CA',
                            dark: '#3730A3'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter bg-gradient-to-br from-indigo-900 to-blue-700 text-white min-h-screen">
    <div class="container mx-auto px-4 py-8 flex flex-col items-center">
        <h1 class="text-4xl font-bold mb-8 text-center bg-clip-text text-transparent bg-gradient-to-r from-white to-indigo-200">
            Video Communication Hub
        </h1>

        <div class="w-full max-w-4xl bg-white/10 backdrop-blur-lg rounded-xl overflow-hidden shadow-2xl p-6 mb-10">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Video Creation Section -->
                <div class="bg-indigo-800/50 rounded-lg p-6 flex flex-col">
                    <h2 class="text-2xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Create Video
                    </h2>
                    <p class="text-indigo-200 mb-4">Generate a new video and get it processed in the queue.</p>
                    <button onclick="createVideo()" class="mt-auto py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg text-white font-medium hover:from-indigo-600 hover:to-purple-600 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create New Video
                    </button>
                    <p id="response" class="mt-4 text-sm text-center text-indigo-200">Click the button to create a video.</p>
                </div>

                <!-- Status Check Section -->
                <div class="bg-blue-800/50 rounded-lg p-6 flex flex-col">
                    <h2 class="text-2xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Video Status
                    </h2>
                    <p class="text-blue-200 mb-4">Check the status of your video processing request.</p>
                    <button onclick="checkStatus()" class="mt-auto py-3 px-6 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg text-white font-medium hover:from-blue-600 hover:to-cyan-600 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Check Status
                    </button>
                    <div id="video-details" class="mt-4 text-sm text-blue-200 break-words bg-blue-900/30 p-3 rounded-lg h-20 overflow-auto">No video created yet.</div>
                </div>
            </div>
        </div>

        <!-- Live Conversation Section -->
        <div class="w-full max-w-4xl">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl overflow-hidden shadow-2xl p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Live Conversation
                </h2>
                <div class="flex flex-col items-center">
                    <p id="massage-conversation" class="text-center text-indigo-200 mb-4">Start a new video conversation session.</p>
                    <button onclick="createConversation()" class="mb-6 py-3 px-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg text-white font-medium hover:from-green-600 hover:to-emerald-600 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Start Conversation
                    </button>
                </div>
            </div>
            <div id="video-call-container" class="w-full aspect-video bg-gradient-to-r from-gray-800 to-gray-900 rounded-xl overflow-hidden shadow-2xl flex items-center justify-center">
                <div class="text-center p-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-indigo-400 mb-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-400 text-lg">Your video call will appear here</p>
                    <p class="text-gray-500 text-sm mt-2">Click "Start Conversation" to begin</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let videoId = null;

        function createVideo() {
            document.getElementById("response").innerText = "Creating video...";
            document.getElementById("response").className = "mt-4 text-sm text-center text-yellow-200 animate-pulse";

            fetch("/lib/video.php", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("response").innerText = 'Your video is in the queue, please wait';
                document.getElementById("response").className = "mt-4 text-sm text-center text-green-200";
                videoId = data.video_id;
            })
            .catch(error => {
                document.getElementById("response").innerText = "Error: " + error;
                document.getElementById("response").className = "mt-4 text-sm text-center text-red-300";
            });
        }

        function createConversation() {
            document.getElementById("massage-conversation").innerText = "Creating conversation...";
            document.getElementById("massage-conversation").className = "text-center text-yellow-200 mb-4 animate-pulse";

            fetch("create-con.php", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                const container = document.getElementById('video-call-container');
                // Clear placeholder content
                container.innerHTML = '';

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
                document.getElementById("massage-conversation").className = "text-center text-green-200 mb-4";
                videoId = data.video_id;
            })
            .catch(error => {
                document.getElementById("massage-conversation").innerText = "Error: " + error;
                document.getElementById("massage-conversation").className = "text-center text-red-300 mb-4";
            });
        }

        function checkStatus() {
            if (videoId === null) {
                document.getElementById("video-details").innerText = "No video created yet.";
                return;
            }
            
            document.getElementById("video-details").innerText = "Checking status...";
            document.getElementById("video-details").className = "mt-4 text-sm text-blue-200 break-words bg-blue-900/30 p-3 rounded-lg h-20 overflow-auto animate-pulse";
            
            fetch("webhooks/video.json")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("video-details").className = "mt-4 text-sm text-blue-200 break-words bg-blue-900/30 p-3 rounded-lg h-20 overflow-auto";
                    
                    if (data.video_id === videoId) {
                        console.log("Video Details: ", data);
                        document.getElementById("video-details").innerHTML =
                            `<span class="font-semibold">Status:</span> <span class="text-green-300">${data.status}</span><br>` +
                            `<span class="font-semibold">Video Name:</span> ${data.video_name}<br>` +
                            `<span class="font-semibold">Hosted URL:</span> <a href="${data.hosted_url}" target="_blank" class="text-blue-300 hover:text-blue-100 underline">${data.hosted_url}</a>`;
                    } else {
                        document.getElementById("video-details").innerText = "Video is in the queue, please wait";
                    }
                })
                .catch(error => {
                    document.getElementById("video-details").innerText = "Error fetching video details: " + error;
                    document.getElementById("video-details").className = "mt-4 text-sm text-red-300 break-words bg-red-900/30 p-3 rounded-lg h-20 overflow-auto";
                });
        }
    </script>
</body>
</html>