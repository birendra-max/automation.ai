<?php
require 'Third-party/vendor/autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['emailfile'])) {
        $file = $_FILES['emailfile'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadPath = $uploadDir . basename($file['name']);

            // Move the uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                try {
                    // Attempt to load the spreadsheet
                    $spreadsheet = IOFactory::load($uploadPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = $sheet->toArray();
                    $highestColumn = $sheet->getHighestColumn();
                    $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                    // Ensure no more than 4 columns are present
                    if ($columnIndex <= 4) {
                        $data = [];

                        // Loop through rows (skipping the header row)
                        for ($i = 1; $i < count($rows); $i++) {
                            // Trim values to avoid extra spaces causing issues
                            $email = trim($rows[$i][0] ?? '');
                            $name = trim($rows[$i][1] ?? '');
                            $subject = trim($rows[$i][2] ?? '');
                            $prompt = trim($rows[$i][3] ?? '');

                            // Only add to data if all fields are present
                            if ($email && $name && $subject && $prompt) {
                                $data[] = [
                                    'email' => $email,
                                    'name' => $name,
                                    'subject' => $subject,
                                    'prompt' => $prompt
                                ];
                            }
                        }

                        // Return data as JSON response
                        echo json_encode($data);
                    } else {
                        echo json_encode(['message' => 'The uploaded sheet must not have more than 4 columns (email, name, subject, prompt).']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['message' => 'Error parsing the spreadsheet file.', 'error' => $e->getMessage()]);
                }

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
