<?php
// Praktik 3 – Menampilkan data produk dari database dengan format JSON
// coding ini membutuhkan koneksi ke mysql database northwind

// Cek method harus menggunakan GET, jika tidak GET kirim code dan pesan error
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    $data = [
        "code" => 405,
        "message" => "Method not allowed",
    ];
    header('Content-type: application/json,charset=UTF-8');
    $json = json_encode($data);
    print_r($json);
    die();
}

$starttime = microtime(true);
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "northwind";

try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}

$sql = "SELECT * FROM products AS p JOIN categories AS c on p.CategoryID=c.CategoryID";
$data = $conn->prepare($sql);
$data->execute();
while ($row = $data->fetch(PDO::FETCH_OBJ)) {
    $products[] = [
        "ProductID" => $row->ProductID,
        "ProductName" => $row->ProductName,
        "CategoryName" => $row->CategoryName,
        "UnitsInStock" => $row->UnitsInStock,
    ];
}
$stoptime = microtime(true);
$responTime = floor(($stoptime - $starttime) * 1000);

$data = [
    "took" => $responTime,
    "code" => 200,
    "message" => 'Response successfully',
    "data" => $products,
];

header('Content-type: application/json,charset=UTF-8');
$json = json_encode($data);
print_r($json);
