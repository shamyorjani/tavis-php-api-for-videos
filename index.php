<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["file"])) {
    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $target_dir = "pdf/";

    // Ensure the directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate unique filename
    $file_name = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file type (only PDFs allowed)
    if ($fileType !== "pdf") {
        $message = "Only PDF files are allowed.";
        $messageType = "error";
    } else {
        // Move the uploaded file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                header("Location: conversation.php?name=" . urlencode($name) . "&email=" . urlencode($email) . "&file=" . urlencode($file_name));
                exit();
            // } else {
            //     $message = "Database insertion failed.";
            //     $messageType = "error";
            // }
            $stmt->close();
        } else {
            $message = "File upload failed.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Upload Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
            <h2 class="text-2xl font-bold mb-1">PDF Upload Portal</h2>
            <p class="text-blue-100">Complete the form to upload your document</p>
        </div>
        
        <div class="p-6">
            <?php if (isset($message)): ?>
                <div class="mb-4 p-3 rounded-lg text-sm <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'; ?>">
                    <div class="flex items-center">
                        <i class="<?php echo $messageType === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500'; ?> mr-2"></i>
                        <?php echo $message; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-5">
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
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload PDF Document</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition duration-200">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-file-pdf text-gray-400 text-4xl mb-2"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Click to select a file</span>
                                    <input id="file" name="file" type="file" accept=".pdf" class="sr-only" required>
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

                <button type="submit" class="w-full flex justify-center items-center space-x-2 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Upload Document</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Display selected filename
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const fileNameContainer = document.getElementById('file-name');
            
            if (fileName) {
                fileNameContainer.querySelector('span').textContent = fileName;
                fileNameContainer.classList.remove('hidden');
            } else {
                fileNameContainer.classList.add('hidden');
            }
        });

        // Simple drag and drop functionality
        const dropArea = document.querySelector('.border-dashed');
        const fileInput = document.getElementById('file');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-blue-400', 'bg-blue-50');
        }

        function unhighlight() {
            dropArea.classList.remove('border-blue-400', 'bg-blue-50');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            
            // Trigger change event to update the file name display
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    </script>
</body>
</html>