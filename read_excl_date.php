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

            $spreadsheet = IOFactory::load($uploadPath);
            $sheet = $spreadsheet->getActiveSheet();

            $row = [];

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

            foreach($row as $r){
                $que="insert into call_schudle(phno , lab_name) values()";
            }


            echo 'File uploaded successfully!';
        } else {
            echo 'There was an error uploading the file.';
        }
    } else {
        echo 'No file uploaded or there was an error with the file.';
    }
    echo 'Username: ' . htmlspecialchars($username);
}
