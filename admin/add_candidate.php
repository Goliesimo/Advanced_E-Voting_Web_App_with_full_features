<?php
include '../conn.php'; // Adjust the path to your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $party = $_POST['party'];
    $position = $_POST['position'];
    $age = $_POST['age'];

    // Handle candidate photo upload
    $photoPath = uploadFile('photo', '../uploads/'); // Upload to the 'uploads' directory

    // Handle party logo upload
    $partyLogoPath = uploadFile('party_logo', '../party_logos/'); // Upload to the 'party_logos' directory

    if ($photoPath !== false && $partyLogoPath !== false) {
        // Insert candidate data into the database
        $query = "INSERT INTO candidates (name, party, position, photo, party_logo, age) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $party, $position, $photoPath, $partyLogoPath, $age]);

        if ($stmt) {
            // Candidate added successfully
            header('Location: candidates.php?success=1'); // Redirect to candidates page with success parameter
            exit();
        } else {
            // Candidate insertion failed
            echo "Candidate insertion failed. Please try again.";
        }
    } else {
        // File upload failed
        echo "File upload failed. Please check the file format and size.";
    }
}

function uploadFile($fileInputName, $uploadDirectory) {
    // Check if the file was uploaded without errors
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $tempFile = $_FILES[$fileInputName]['tmp_name'];
        $targetFile = $uploadDirectory . $_FILES[$fileInputName]['name'];

        // Check the file format (e.g., allow only images)
        $allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES[$fileInputName]['type'], $allowedFormats)) {
            if (move_uploaded_file($tempFile, $targetFile)) {
                // File uploaded successfully
                return $targetFile;
            }
        }
    }

    return false; // File upload failed
}
?>
