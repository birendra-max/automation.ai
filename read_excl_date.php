<?php

require __DIR__ . "/Third-party/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

require 'inclu/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['fileInput'];
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            try {
                $spreadsheet = IOFactory::load($uploadPath);
                $sheet = $spreadsheet->getActiveSheet();

                $rows = [];
                foreach ($sheet->getRowIterator(2) as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $data = [];
                    foreach ($cellIterator as $cell) {
                        $data[] = $cell->getValue();
                    }

                    if (!empty($data[0]) && !empty($data[1])) {
                        $rows[] = [
                            'phone' => $data[0],
                            'company' => $data[1]
                        ];
                    }
                }

                $dt = date('Y-m-d H:i:s');
                $status = 'Pending';

                $stmt = $conn->prepare("INSERT INTO call_schudle (phno, lab_name, entry_date, status) VALUES (?, ?, ?, ?)");

                if ($stmt) {
                    foreach ($rows as $r) {
                        $rawPhno = $r['phone'];
                        $labName = $r['company'];
                        $plusPos = strpos($rawPhno, '+');
                        if ($plusPos !== false) {
                            $phno = substr($rawPhno, $plusPos);
                        } else {
                            $phno = $rawPhno;
                        }

                        $stmt->bind_param('ssss', $phno, $labName, $dt, $status);
                        $stmt->execute();
                    }

                    $stmt->close();
                } else {
                    echo 'Database error: ' . $conn->error;
                    unlink($uploadPath);
                    exit;
                }

                if (unlink($uploadPath)) {
                    echo 'File processed and deleted successfully!';
                } else {
                    echo 'File processed, but failed to delete the uploaded file.';
                }
            } catch (Exception $e) {
                echo 'Error reading the Excel file: ' . $e->getMessage();
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
            }
        } else {
            echo 'Error moving the uploaded file.';
        }
    } else {
        echo 'No file uploaded or there was an upload error.';
    }
}
