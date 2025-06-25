<?php
$str = '';

$file = fopen('data/meisai.csv','r');
flock($file, LOCK_EX);

if($file){
  while($line = fgets($file)){
    $str .= "<tr><td>{$line}</tr></td>";
  }
}

flock($file, LOCK_UN);
fclose($file);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>請求書発行</title>
    <link rel="stylesheet" href="invoicestyle.css">
</head>
<body>
  <fieldset>
      <legend>明細選択</legend>
      <div class="content">
        顧客番号: <input type="text" name="number" id="number">
      </div>
      <div class="content">
        顧客名: <input type="text" name="customer" id="customer"readonly>
      </div>
      <div class="content">
        期間: <input type="date" name="date">～<input type="date" name="date">
      </div>
  </fieldset>
  <div class="bill">
    <div class="header">
        <img src="logo.png" class="logo">
        <div class="invoice-title">請求書</div>
    </div>
    <div class="info-container">
        <div class="info-column">
            <strong>差出人:</strong><br>
            株式会社サンプル制作<br>
            〒123-4567<br>
            東京都渋谷区サンプル町1-2-3<br>
            メール: info@example.com<br>
            電話: 03-1234-5678
        </div>
        <div class="info-column">
            <strong>宛先:</strong><br>
            株式会社サンプルクライアント<br>
            〒765-4321<br>
            東京都渋谷区サンプル町3-2-1<br>
            メール: billing@example.com
        </div>
        <div class="info-column">
            <strong>請求書番号:</strong> INV-2024-001<br>
            <strong>発行日:</strong> 20XX年12月31日<br>
            <strong>支払期限:</strong> 20XX年1月31日
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>品目</th>
                <th>数量</th>
                <th>単価</th>
                <th>金額</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ロゴデザイン制作</td>
                <td>1</td>
                <td>¥50,000</td>
                <td>¥50,000</td>
            </tr>
            <tr>
                <td>ウェブサイトモックアップ</td>
                <td>3</td>
                <td>¥30,000</td>
                <td>¥90,000</td>
            </tr>
            <tr>
                <td>SNSグラフィックパッケージ</td>
                <td>1</td>
                <td>¥25,000</td>
                <td>¥25,000</td>
            </tr>
            <tr>
                <td>修正および打ち合わせ</td>
                <td>5時間</td>
                <td>¥7,500/時間</td>
                <td>¥37,500</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="3">合計金額（税込）</td>
                <td>¥202,500</td>
            </tr>
        </tfoot>
    </table>
    <p>
        <strong>支払条件:</strong> 請求書発行日より14日以内<br>
        <strong>支払方法:</strong> 銀行振込
    </p>
  </div>

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