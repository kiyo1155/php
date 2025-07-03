<?php
header('Content-Type: application/json');

// パラメータ取得
$number = $_GET['number'] ?? '';
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

if ($number === '' || $start === '' || $end === '') {
    echo json_encode([]);
    exit();
}

// データベース接続
$dbn ='mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

try {
    $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
    echo json_encode(['error' => 'DB接続エラー:' . $e->getMessage()]);
    exit();
}

// SQL作成
$sql = 'SELECT * FROM bill_table
        WHERE number = :number
          AND date BETWEEN :start AND :end
        ORDER BY date ASC';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':number', $number, PDO::PARAM_INT);
$stmt->bindValue(':start', $start, PDO::PARAM_STR);
$stmt->bindValue(':end', $end, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(['error' => 'SQLエラー:' . print_r($error, true)]);
    exit();
}

// データ取得
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// フロントに合わせてキー名を変換
$response = array_map(function($row) {
    return [
        'item' => $row['merchandise'],
        'amount' => $row['amount'],
        'note' => $row['detail'],
        'company' => '' // 会社名はJSで顧客番号から補完
    ];
}, $result);

echo json_encode($response);
?>