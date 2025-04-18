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

                $rows = []; // ✅ Properly initialize the array

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

                foreach ($rows as $r) {
                    $stmt = $conn->prepare("INSERT INTO call_schudle (phno, lab_name, entry_date) VALUES (?, ?, ?)");
                    $dt = date('Y-m-d H:i:sa');
                    $stmt->bind_param('sss', $r['phone'], $r['company'], $dt);
                    $stmt->execute();
                }

                // ✅ Attempt to delete the uploaded file
                if (unlink($uploadPath)) {
                    echo 'File processed and deleted successfully!';
                } else {
                    echo 'File processed, but failed to delete the uploaded file.';
                }
            } catch (Exception $e) {
                echo 'Error reading the Excel file: ' . $e->getMessage();
                unlink($uploadPath);
            }
        } else {
            echo 'Error moving the uploaded file.';
            unlink($uploadPath);
        }
    } else {
        echo 'No file uploaded or there was an upload error.';
        unlink($uploadPath);
    }
}
