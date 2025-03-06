<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Upload Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
            <h2 class="text-2xl font-bold mb-1">PDF Upload Portal</h2>
            <p class="text-blue-100">Complete the form to upload your document</p>
        </div>

        <div class="p-6">
            <div id="message-container" class="mb-4 p-3 rounded-lg text-sm hidden"></div>

            <form id="uploadForm" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" placeholder="John Doe" required
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" placeholder="your@email.com" required
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload PDF
                        Document</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition duration-200">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-file-pdf text-gray-400 text-4xl mb-2"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Click to select a file</span>
                                    <input id="file" name="file" type="file" accept=".pdf" class="sr-only"
                                        required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF files only (Max 10MB)</p>
                            <div id="file-name" class="text-sm text-gray-500 mt-2 hidden">
                                <span class="font-medium"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitButton"
                    class="w-full flex justify-center items-center space-x-2 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Upload Document</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#uploadForm").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let submitButton = $("#submitButton");
                let conversationalUrlNew;

                // Disable the button and show loader
                submitButton.prop("disabled", true).off("click");
                submitButton.html('<i class="fas fa-spinner fa-spin"></i> Uploading...');

                $.ajax({
                    url: "upload.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        let messageDiv = $("#message-container");
                        messageDiv.html("").removeClass("hidden");

                        if (response.status === "success") {
                            messageDiv.html(
                                '<div class="text-green-600 font-medium"><i class="fas fa-check-circle"></i> ' +
                                response.message + '</div>'
                            );

                            fetch("lib/tavus.php", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        name: response.name,
                                        email: response.email,
                                        textContent: response.textContent
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    conversationalUrlNew = data.conversation_url;
                                    if (data.status === "active") {
                                       
                                        fetch("lib/claude.php", {
                                                method: "POST",
                                                headers: {
                                                    "Content-Type": "application/json"
                                                },
                                                body: JSON.stringify({
                                                    fileName: response.file
                                                })
                                            })
                                            .then(response => response.text())
                                            .then(text => {
                                                try {
                                                    console.log("Raw response:", text);
                                                    const jsonText = text.replace(/^API response: /, '');
                                                    const data = JSON.parse(jsonText);
                                                      
                                                    if (data.content && Array.isArray(data.content)) {
                                                        const textContent = data.content.find(item => item.type === "text");
                                                        console.log("Response: 3", data.conversation_url);
                                                        if (textContent && textContent.text) {
                                                            console.log("Extracted text:", textContent.text); // Only log the extracted text                                                            
                                                            console.log("Response:4", conversationalUrlNew);
                                                            window.location.href =
                                                                "result.php?name=" + encodeURIComponent(response.name) +
                                                                "&email=" + encodeURIComponent(response.email) +
                                                                "&file=" + encodeURIComponent(response.file) +
                                                                "&conversation_url=" + encodeURIComponent(conversationalUrlNew) +
                                                                "&textContent=" + encodeURIComponent(textContent.text);
                                                        } else {
                                                            messageDiv.html(
                                                                '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> No valid content received.</div>'
                                                            );
                                                        }
                                                    } else {
                                                        messageDiv.html(
                                                            '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> Invalid response format.</div>'
                                                        );
                                                    }
                                                } catch (error) {
                                                    console.error("Error parsing response:", error);
                                                    messageDiv.html(
                                                        '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> Error processing response.</div>'
                                                    );
                                                }
                                            })
                                            .catch(error => {
                                                console.error("Error fetching data:", error);
                                                messageDiv.html(
                                                    '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> Error fetching data.</div>'
                                                );
                                            });
                                    } else {
                                        messageDiv.html(
                                            '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> ' +
                                            data.message + '</div>'
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error("Error Fetching Tavus API:", error);
                                    messageDiv.html(
                                        '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> Error processing request.</div>'
                                    );
                                });
                        } else {
                            messageDiv.html(
                                '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> ' +
                                response.message + '</div>');
                        }
                    },
                    error: function() {
                        $("#message-container").html(
                            '<div class="text-red-600 font-medium"><i class="fas fa-exclamation-circle"></i> Error uploading file.</div>'
                        );

                        // Re-enable the button after a delay
                        setTimeout(() => {
                            submitButton.prop("disabled", false);
                            submitButton.html(
                                '<i class="fas fa-cloud-upload-alt"></i> Upload Document'
                            );
                        }, 2000);
                    }
                });
            });
        });
    </script>
</body>

</html>