<?php

if(
    !isset($_POST['number']) || $_POST['number'] === '' ||
    !isset($_POST['date']) || $_POST['date'] === '' ||
    !isset($_POST['merchandise']) || $_POST['merchandise'] === '' ||
    !isset($_POST['amount']) || $_POST['amount'] === ''
){
    exit('記入漏れがあります');
}

// データベース接続情報
$dbn ='mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

try {
    $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
    exit('DB接続エラー:' . $e->getMessage());
}

// POSTデータ取得
$number = $_POST['number'];
$date = $_POST['date'];
$merchandise = $_POST['merchandise'];
$amount = $_POST['amount'];
$detail = $_POST['detail'];

// SQL作成
$sql = 'INSERT INTO bill_table (number, date, merchandise, amount, detail)
        VALUES (:number, :date, :merchandise, :amount, :detail)';

// SQL準備
$stmt = $pdo->prepare($sql);

// バインド変数
$stmt->bindValue(':number', $number, PDO::PARAM_INT);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':merchandise', $merchandise, PDO::PARAM_STR);
$stmt->bindValue(':amount', $amount, PDO::PARAM_INT);
$stmt->bindValue(':detail', $detail, PDO::PARAM_STR);

// SQL実行
$status = $stmt->execute();

// 結果判定
if ($status == false) {
    $error = $stmt->errorInfo();
    exit('SQLエラー:' . print_r($error, true));
} else {
    // 登録後に遷移
    header('Location: invoice.php');
    exit();
}
?>
