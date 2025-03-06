<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["status" => "error", "message" => "Something went wrong!"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["file"])) {
    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $target_dir = "../pdf/";

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
        $response["message"] = "Only PDF files are allowed.";
    } else {
        // Move the uploaded file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $response = [
                "status" => "success",
                "message" => "File uploaded successfully!",
                "file" => $file_name,
                "name" => $name,
                "email" => $email
            ];
        } else {
            $response["message"] = "File upload failed.";
        }
    }
}

header("Content-Type: application/json");
echo json_encode($response);
exit();
?>
