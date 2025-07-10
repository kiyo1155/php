<?php
// 入力チェック
if (
  !isset($_POST['number']) || $_POST['number'] === '' ||
  !isset($_POST['date']) || $_POST['date'] === '' ||
  !isset($_POST['merchandise']) || $_POST['merchandise'] === '' ||
  !isset($_POST['amount']) || $_POST['amount'] === '' ||
  !isset($_POST['detail']) || $_POST['detail'] === '' ||
  !isset($_POST['id']) || $_POST['id'] === ''
) {
  exit('paramError');
}

// 変数化
$id = $_POST['id'];
$number = $_POST['number'];
$date = $_POST['date'];
$merchandise = $_POST['merchandise'];
$amount = $_POST['amount'];
$detail = $_POST['detail'];

// DB接続
$dbn = 'mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// SQL更新実行
$sql = 'UPDATE bill_table SET number=:number, date=:date, merchandise=:merchandise, amount=:amount, detail=:detail WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':number', $number, PDO::PARAM_STR);
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':merchandise', $merchandise, PDO::PARAM_STR);
$stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
$stmt->bindValue(':detail', $detail, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// 一覧に戻る
header('Location: read.php');
exit();