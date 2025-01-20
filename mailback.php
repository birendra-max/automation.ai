<?php
require 'Third-party\vendor\autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['emailfile'])) {
        $file = $_FILES['emailfile'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadPath = $uploadDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $spreadsheet = IOFactory::load($uploadPath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
                $highestColumn = $sheet->getHighestColumn();
                $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                // Ensure no more than 4 columns are present (email, name, subject, prompt)
                if ($columnIndex <= 4) {
                    $totalMailSend = 0;

                    // Loop through rows (skipping the header row)
                    for ($i = 1; $i < count($rows); $i++) {
                        $email = $rows[$i][0] ?? null;
                        $name = $rows[$i][1] ?? null;
                        $subject = $rows[$i][2] ?? null;
                        $prompt = $rows[$i][3] ?? null;

                        if (filter_var($email, FILTER_VALIDATE_EMAIL) && $name && $subject && $prompt) {
                            $sql = "INSERT INTO mailautomationai(email, name, subject, prompt) VALUES (?, ?, ?, ?)";
                            if ($statement = $conn->prepare($sql)) {
                                $statement->bind_param('ssss', $email, $name, $subject, $prompt);

                                if ($statement->execute()) {
                                    $resp = sendEmail($email, $name, $subject, $prompt, '');

                                    if ($resp === 'Email sent successfully!') {
                                        $status = "Success";
                                        $sql1 = "UPDATE mailautomationai SET status = ? WHERE email = ?";

                                        if ($statement1 = $conn->prepare($sql1)) {
                                            $statement1->bind_param("ss", $status, $email);
                                            if ($statement1->execute()) {
                                                $totalMailSend++;
                                            } else {
                                                echo json_encode(['message' => "Error updating record: " . $statement1->error]);
                                            }
                                            $statement1->close();
                                        } else {
                                            echo json_encode(['message' => "Error preparing statement: " . $conn->error]);
                                        }
                                    }
                                }
                                $statement->close();
                            } else {
                                echo json_encode(['message' => "Error preparing statement: " . $conn->error]);
                            }
                        } else {
                            echo json_encode(['message' => "Invalid email or missing required fields in row " . ($i + 1)]);
                        }
                    }

                    // Final response after processing all rows
                    if ($totalMailSend == count($rows) - 1) {
                        echo json_encode(['message' => 'Emails sent successfully', 'count' => $totalMailSend . ' Emails send successfully']);
                    } else {
                        echo json_encode(['message' => 'Some emails were not sent successfully.']);
                    }
                } else {
                    echo json_encode(['message' => 'The uploaded sheet must not have more than 4 columns (email, name, subject, prompt).']);
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
