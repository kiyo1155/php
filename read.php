<?php
// DB接続情報
$dbn = 'mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行
$sql = 'SELECT * FROM bill_table ORDER BY date ASC';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// 結果取得
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";
foreach ($result as $record) {
  $output .= '
   <tr>
    <td class="number">' . $record["number"] . '</td>
    <td class="customer"></td>
    <td>' . $record["date"] . '</td>
    <td>' . $record["merchandise"] . '</td>
    <td>' . number_format($record["amount"]) . '</td>
    <td>' . $record["detail"] . '</td>
    <td><a href="edit.php?id=' . $record["id"] . '">編集</a></td>
    <td><a href="delete.php?id=' . $record["id"] . '">削除</a></td>
  </tr>
';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>明細一覧</title>
</head>
<body>
  <fieldset>
    <legend>明細一覧</legend>
    <a href="index.php">← 入力画面に戻る</a>
    <table>
      <thead>
        <tr>
          <th>顧客番号</th>
          <th>顧客名</th>
          <th>納品日</th>
          <th>商品名</th>
          <th>金額</th>
          <th>詳細</th>
          <th colspan="2">操作</th>
        </tr>
      </thead>
      <tbody>
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>

  <script>
  const customerMap = {
    "1001": "株式会社A",
    "1002": "株式会社B",
    "1003": "株式会社C"
  };

  document.querySelectorAll("tbody tr").forEach(row => {
    const numberCell = row.querySelector(".number");
    const customerCell = row.querySelector(".customer");

    if (numberCell && customerCell) {
      const number = numberCell.textContent.trim();
      customerCell.textContent = customerMap[number] || "";
    }
  });
</script>

</body>
</html>