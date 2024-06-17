<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    $filename = basename($_FILES['file']['name']);
    $uploadFile = $uploadDir . $filename;

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $image = imagecreatefrompng($uploadFile);
        if ($image === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to create image from uploaded file']);
            exit;
        }

        $outputFilename = $uploadDir . pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
        if (imagejpeg($image, $outputFilename, 100)) {
            imagedestroy($image);
            echo json_encode(['success' => true, 'filepath' => $outputFilename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to convert image to JPG']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
