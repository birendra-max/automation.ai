<?php
require 'Third-party/vendor/autoload.php'; // Autoload PhpSpreadsheet
require 'inclu/config.php'; // Include database configuration
require 'inclu/Mailer.php'; // Include Mailer class if needed

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['emailfile'])) {
        $file = $_FILES['emailfile'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadPath = $uploadDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                try {
                    // Load the Excel file
                    $spreadsheet = IOFactory::load($uploadPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = $sheet->toArray();
                    $highestColumn = $sheet->getHighestColumn();
                    $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                    if ($columnIndex <= 4) {
                        // Prepare the data array for inserting into the database
                        $data = [];

                        for ($i = 1; $i < count($rows); $i++) {
                            $email = trim($rows[$i][0] ?? '');
                            $name = trim($rows[$i][1] ?? '');
                            $subject = trim($rows[$i][2] ?? '');
                            $prompt = trim($rows[$i][3] ?? '');

                            if ($email && $name && $subject && $prompt) {
                                // Add valid data to the array
                                $data[] = [
                                    'email' => $email,
                                    'name' => $name,
                                    'subject' => $subject,
                                    'prompt' => $prompt
                                ];

                                // Insert the data into the database (assuming MySQL)
                                $stmt = $conn->prepare("INSERT INTO mail_save (email, name, subject, prompt) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("ssss", $email, $name, $subject, $prompt);
                                $stmt->execute();
                                $stmt->close();
                            }
                        }

                        // Now fetch the updated data from the database and return it
                        $result = $conn->query("SELECT * FROM mail_save ORDER BY id DESC");

                        // Create an array to store the fetched data
                        $fetchedData = [];
                        while ($row = $result->fetch_assoc()) {
                            $fetchedData[] = $row;
                        }

                        // Return the fetched data as JSON
                        echo json_encode($fetchedData);
                    } else {
                        echo json_encode(['message' => 'The uploaded sheet must not have more than 4 columns (email, name, subject, prompt).']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['message' => 'Error parsing the spreadsheet file.', 'error' => $e->getMessage()]);
                }

                // Delete the uploaded file after processing
                unlink($uploadPath);
            } else {
                echo json_encode(['message' => 'Failed to move the uploaded file.']);
            }
        } else {
            echo json_encode(['message' => 'Error uploading file.', 'error' => $file['error']]);
        }
    } else {
        echo json_encode(['message' => 'No file uploaded.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
