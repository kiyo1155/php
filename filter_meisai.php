<?php
header('Content-Type: application/json');

$number = $_GET['number'] ?? '';
$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

$result = [];

if ($file = fopen('data/meisai.csv', 'r')) {
    while ($line = fgetcsv($file)) {
        if (count($line) < 6) continue;

        list($num, $company, $date, $item, $amount, $note) = $line;

        if ($num === $number && $date >= $start && $date <= $end) {
            $result[] = [
                'item'   => $item,
                'amount' => $amount,
                'note'   => $note,
                'company' => $company,
            ];
        }
    }
    fclose($file);
}

echo json_encode($result);