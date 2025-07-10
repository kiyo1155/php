<?php
// DB接続関数
function connect_to_db() {
  $dbn = 'mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
  $user = 'root';
  $pwd = '';

  try {
    return new PDO($dbn, $user, $pwd);
  } catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
  }
}

// idチェック
if (!isset($_GET['id']) || $_GET['id'] === '') {
  exit('paramError');
}

$id = $_GET['id'];
$pdo = connect_to_db();

// 対象データ取得
$sql = 'SELECT * FROM bill_table WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
  exit('対象のデータが見つかりません');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>明細編集</title>
</head>
<body>
  <form action="update.php" method="POST">
    <fieldset>
      <legend>明細編集</legend>
      <div>顧客番号: <input type="text" name="number" value="<?= $record['number'] ?>"></div>
      <div class="content">
        顧客名: <input type="text" name="customer" id="customer"readonly>
      </div>
      <div>納品日: <input type="date" name="date" value="<?= $record['date'] ?>"></div>
      <div>商品名: <input type="text" name="merchandise" value="<?= $record['merchandise'] ?>"></div>
      <div>金額: <input type="text" name="amount" value="<?= $record['amount'] ?>"></div>
      <div>詳細: <input type="text" name="detail" value="<?= $record['detail'] ?>"></div>
      <input type="hidden" name="id" value="<?= $record['id'] ?>">
      <div><button type="submit">更新する</button></div>
    </fieldset>
  </form>
  <a href="read.php">戻る</a>

 <script>
  // 顧客番号と顧客名の対応表
  const customerMap = {
    "1001": "株式会社A",
    "1002": "株式会社B",
    "1003": "株式会社C"
  };

  const numberInput = document.querySelector('input[name="number"]');
  const customerInput = document.querySelector('input[name="customer"]');

  // 初期表示時に番号に対応する顧客名を表示
  const number = numberInput.value.trim();
  customerInput.value = customerMap[number] || "";

  // 顧客番号が変更されたときに再反映する（任意）
  numberInput.addEventListener("input", () => {
    const num = numberInput.value.trim();
    customerInput.value = customerMap[num] || "";
  });
</script>
</body>
</html>
