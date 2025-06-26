<?php
$number = $_POST['number'];
$customer = $_POST['customer'];
$date = $_POST['date'];
$merchandise = $_POST['merchandise'];
$amount = $_POST['amount'];
$detail = $_POST['detail'];

$write_data = [$number, $customer, $date, $merchandise, $amount, $detail];

$file = fopen('data/meisai.csv','a');
flock($file, LOCK_EX);
fputcsv($file, $write_data);
flock($file, LOCK_UN);
fclose($file);
header("Location:index.php");

