<?php
// DB接続設定
$dbn = 'mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続処理
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// id パラメータチェック
if (!isset($_GET['id']) || $_GET['id'] === '') {
  exit('paramError');
}

$id = $_GET['id'];

// 削除SQL作成
$sql = 'DELETE FROM bill_table WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location: read.php");
exit();