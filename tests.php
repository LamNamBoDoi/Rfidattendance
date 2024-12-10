composer require phpoffice/phpspreadsheet
<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

// Tạo file Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Đặt tiêu đề bảng
$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Name')
      ->setCellValue('C1', 'Serial Number')
      ->setCellValue('D1', 'Card UID')
      ->setCellValue('E1', 'Device ID')
      ->setCellValue('F1', 'Device Dep')
      ->setCellValue('G1', 'Date log')
      ->setCellValue('H1', 'Time In')
      ->setCellValue('I1', 'Time Out');

// Truy vấn dữ liệu từ cơ sở dữ liệu
require 'connectDB.php';
$sql = "SELECT * FROM users_logs ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    $rowNumber = 2; // Bắt đầu từ dòng thứ 2
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A'.$rowNumber, $row['id'])
              ->setCellValue('B'.$rowNumber, $row['username'])
              ->setCellValue('C'.$rowNumber, $row['serialnumber'])
              ->setCellValue('D'.$rowNumber, $row['card_uid'])
              ->setCellValue('E'.$rowNumber, $row['device_uid'])
              ->setCellValue('F'.$rowNumber, $row['device_dep'])
              ->setCellValue('G'.$rowNumber, $row['checkindate'])
              ->setCellValue('H'.$rowNumber, $row['timein'])
              ->setCellValue('I'.$rowNumber, $row['timeout']);
        $rowNumber++;
    }
}

// Xuất file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="User_Log.xls"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
exit();
?>
