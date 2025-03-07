    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lab Report Upload</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            medical: {
                                100: '#e1f5fe',
                                200: '#b3e5fc',
                                300: '#81d4fa',
                                400: '#4fc3f7',
                                500: '#29b6f6',
                                600: '#039be5',
                                700: '#0288d1',
                                800: '#0277bd',
                                900: '#01579b',
                            }
                        }
                    }
                }
            }
        </script>
    </head>

    <body class="bg-gradient-to-br from-medical-100 to-white min-h-screen flex items-center justify-center p-2 sm:p-6">
        <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Header Section with medical themed gradient -->
            <div class="bg-gradient-to-r from-medical-700 to-medical-600 p-5 sm:p-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 opacity-20 transform -skew-x-12"></div>
                <h2 class="text-3xl font-bold mb-2 text-white relative z-10">Lab Report Analysis</h2>
                <p class="text-blue-50 text-sm relative z-10">Upload your lab report for instant analysis and interpretation</p>
            </div>

            <div class="p-5 sm:p-8">
                <!-- Message Container -->
                <div id="message-container" class="mb-6 rounded-lg text-sm hidden"></div>

                <form id="uploadForm" enctype="multipart/form-data" class="space-y-6">
                    <!-- Name Input -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">Patient Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user-circle text-medical-500"></i>
                            </div>
                            <input type="text" name="name" id="name" placeholder="Enter patient name" required
                                class="pl-11 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-medical-300 focus:border-medical-500 transition duration-200 outline-none">
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-medical-500"></i>
                            </div>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required
                                class="pl-11 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-medical-300 focus:border-medical-500 transition duration-200 outline-none">
                        </div>
                    </div>

                    <!-- File Upload with lab report specific styling -->
                    <div class="space-y-2">
                        <label for="file" class="block text-sm font-semibold text-gray-700">Upload Lab Report (PDF)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-medical-200 border-dashed rounded-xl bg-medical-50 hover:bg-medical-100 transition duration-300">
                            <div class="space-y-2 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-file-medical text-medical-500 text-4xl mb-3"></i>
                                    <div class="flex flex-col items-center text-sm text-gray-600">
                                        <label for="file"
                                            class="relative cursor-pointer bg-white px-4 py-2 rounded-lg shadow-sm border border-medical-200 font-medium text-medical-700 hover:bg-medical-50 hover:text-medical-800 hover:border-medical-300 transition duration-200">
                                            <span>Select lab report</span>
                                            <input id="file" name="file" type="file" accept=".pdf" class="sr-only" required>
                                        </label>
                                        <p class="mt-2 text-gray-500">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Supported format: PDF (Max 10MB)</p>
                                    <div id="file-name" class="text-sm text-gray-600 mt-2 hidden">
                                        <span class="font-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button with medical themed styling -->
                    <button type="submit" id="submitButton"
                        class="w-full flex justify-center items-center space-x-2 py-3 px-4 bg-gradient-to-r from-medical-600 to-medical-700 text-white rounded-xl hover:from-medical-700 hover:to-medical-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 transition duration-300 shadow-lg">
                        <i class="fas fa-microscope mr-2"></i>
                        <span class="font-medium">Analyze Lab Report</span>
                    </button>
                </form>
            </div>
            
            <!-- Footer with helpful info -->
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                <p class="text-xs text-gray-500 text-center">Your lab report will be analyzed instantly with AI technology to provide clear visualizations of all test results</p>
            </div>
        </div>

        <!-- Keep your existing JavaScript code unchanged -->
        <script>
            // File input preview functionality
            document.getElementById('file').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                const fileNameContainer = document.getElementById('file-name');
                
                if (fileName) {
                    fileNameContainer.classList.remove('hidden');
                    fileNameContainer.querySelector('span').textContent = fileName;
                } else {
                    fileNameContainer.classList.add('hidden');
                }
            });
            
            function sendDataToResult(name, email, file, conversationUrl, textContent) {
                $.ajax({
                    url: "result.php",
                    type: "POST",
                    data: {
                        name: name,
                        email: email,
                        file: file,
                        conversation_url: conversationUrl,
                        textContent: textContent
                    },
                    success: function(response) {
                        console.log("Response from result.php:", response);
                        window.location.href = "result.php"; // Redirect without query params
                    },
                    error: function(error) {
                        console.error("Error sending data to result.php:", error);
                    }
                });
            }

            $(document).ready(function() {
                $("#uploadForm").on("submit", function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);
                    let submitButton = $("#submitButton");
                    let conversationalUrlNew;

                    // Disable the button and show loader
                    submitButton.prop("disabled", true).off("click");
                    submitButton.html('<i class="fas fa-spinner fa-spin mr-2"></i> Analyzing...');

                    $.ajax({
                        url: "lib/upload.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            let messageDiv = $("#message-container");

                            if (response.status === "success") {
                            

                                // Process the PDF to get text content
                                $.ajax({
                                    url: "lib/process_pdf.php",
                                    type: "GET",
                                    data: {
                                        file: response.file
                                    },
                                    success: function(pdfResponse) {
                                        if (pdfResponse.success) {
                                            response.textContent = pdfResponse.text;
                                            console.log("Response: 1", response.textContent);

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
                                                                            console.log("Extracted text:", textContent.text); 
                                                                            console.log("Response:4", conversationalUrlNew);
                                                                            sendDataToResult(
                                                                                response.name,
                                                                                response.email,
                                                                                response.file,
                                                                                conversationalUrlNew,
                                                                                textContent.text
                                                                            );

                                                                        } else {
                                                                            
                                                                            messageDiv.html("").removeClass("hidden");
                                                                            messageDiv.html(
                                                                                '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> No valid content received.</div>'
                                                                            );
                                                                        }
                                                                    } else {
                                                                        
                                                                        messageDiv.html("").removeClass("hidden");
                                                                        messageDiv.html(
                                                                            '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> Invalid response format.</div>'
                                                                        );
                                                                    }
                                                                } catch (error) {
                                                                    
                                                                    messageDiv.html("").removeClass("hidden");
                                                                    console.error("Error parsing response:", error);
                                                                    messageDiv.html(
                                                                        '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> Error processing response.</div>'
                                                                    );
                                                                }
                                                            })
                                                            .catch(error => {
                                                                
                                                                messageDiv.html("").removeClass("hidden");
                                                                console.error("Error fetching data:", error);
                                                                messageDiv.html(
                                                                    '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> Error fetching data.</div>'
                                                                );
                                                            });
                                                    } else {
                                                        
                                                        messageDiv.html("").removeClass("hidden");
                                                        messageDiv.html(
                                                            '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> ' +
                                                            data.message + '</div>'
                                                        );
                                                    }
                                                })
                                                .catch(error => {
                                                    
                                                    messageDiv.html("").removeClass("hidden");
                                                    console.error("Error Fetching Tavus API:", error);
                                                    messageDiv.html(
                                                        '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> Error processing request.</div>'
                                                    );
                                                });
                                        } else {
                                            
                                            messageDiv.html("").removeClass("hidden");
                                            messageDiv.html(
                                                '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> ' +
                                                pdfResponse.error + '</div>'
                                            );
                                        }
                                    },
                                    error: function() {
                                        
                                        messageDiv.html("").removeClass("hidden");
                                        messageDiv.html(
                                            '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> Error processing PDF.</div>'
                                        );
                                    }
                                });
                            } else {
                                
                                messageDiv.html("").removeClass("hidden");
                                messageDiv.html(
                                    '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> ' +
                                    response.message + '</div>');
                            }
                        },
                        error: function() {
                            
                            messageDiv.html("").removeClass("hidden");
                            $("#message-container").html(
                                '<div class="text-red-600 font-medium bg-red-50 p-3 border border-red-200 rounded-lg"><i class="fas fa-exclamation-circle"></i> Error uploading file.</div>'
                            );

                            // Re-enable the button after a delay
                            setTimeout(() => {
                                submitButton.prop("disabled", false);
                                submitButton.html(
                                    '<i class="fas fa-microscope mr-2"></i> Analyze Lab Report'
                                );
                            }, 2000);
                        }
                    });
                });
            });
        </script>
    </body>

    </html>