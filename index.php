<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>発注管理</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="create.php" method="POST">
    <fieldset>
      <legend>明細記入</legend>
      <div class="content">
        顧客番号: <input type="text" name="number" id="number">
      </div>
      <div class="content">
        顧客名: <input type="text" name="customer" id="customer"readonly>
      </div>
      <div class="content">
        納品日: <input type="date" name="date">
      </div>
      <div class="content">
        商品名: <input type="text" name="merchandise">
      </div>
      <div class="content">
        金額: <input type="text" name="amount">
      </div>
      <div class="content">
        詳細: <input type="text" name="detail">
      </div>
      <div class="content">
        <button type="submit">明細保存</button>
      </div>
    </fieldset>
    <a href="invoice.php">請求書発行にすすむ</a><br>
    <a href="read.php">明細編集</a>

    <script>
  // 顧客番号と顧客名の対応表（サンプル）
  const customerMap = {
    "1001": "株式会社A",
    "1002": "株式会社B",
    "1003": "株式会社C"
  };

  const numberInput = document.getElementById("number");
  const customerInput = document.getElementById("customer");

  numberInput.addEventListener("input", () => {
    const number = numberInput.value.trim();
    customerInput.value = customerMap[number] || ""; // 該当がなければ空にする
  });
</script>
    
</body>
</html>