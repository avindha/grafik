<?php
// Memanggil Library
require 'phpexcel/autoload.php';

// Koneksi Ke Database
$koneksi = mysqli_connect("localhost","root","","belajarwithib");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

if(isset($_FILES['import']['name']) && in_array($_FILES['import']['type'], $file_mimes)) {
    var_dump($_FILES['import']['name']);
    // Memisahkan nama file dengan formatnya
    $arr_file = explode('.', $_FILES['import']['name']);
    $extension = end($arr_file);

    // Mengecek format file
    if('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }

    // Membaca isi file
    $spreadsheet = $reader->load($_FILES['import']['tmp_name']);

    // Mengubah isi file kebentuk array
    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    var_dump($sheetData);
    
    for($i = 1;$i < count($sheetData);$i++){
        $no_kwitansi = $sheetData[$i]['0'];
        $tanggal = $sheetData[$i]['1'];
        $tanggal_alokasi = $sheetData[$i]['2'];
        $customer = $sheetData[$i]['3'];
        $metode_pembayaran = $sheetData[$i]['4'];
        $total = $sheetData[$i]['5'];
        $biaya_admin = $sheetData[$i]['6'];
        mysqli_query($koneksi, "INSERT INTO siswa(no_kwitansi, tanggal, tanggal_alokasi, customer, metode_pembayaran, total, biaya_admin) VALUES ('$no_kwitansi','$tanggal', '$tanggal_alokasi','$customer', '$metode_pembayaran', '$total', '$biaya_admin')");
    }
    
}
?>