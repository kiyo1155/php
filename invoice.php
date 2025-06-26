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
        期間: <input type="date" name="date" id="start">～<input type="date" name="date"id="end">
      </div>
      <div class="content">
        <button type="button" id="loadData">明細読み込み</button>
       </div>
       <div class="content">
       </div>
  </fieldset>
  <a href="index.php">明細記入へ戻る</a>

  <div class="bill">
    <div class="header">
        <img src="logo.png" class="logo">
        <div class="invoice-title">請求書</div>
    </div>
    <div class="info-container">
        <div class="info-column">
            <strong>差出人:</strong><br>
            株式会社タグスル<br>
            〒779-0000<br>
            徳島県鳴門市○○<br>
            メール: info@tagusuru.com<br>
            電話: 080-xxx-xxxx
        </div>
        <div class="info-column">
                <strong>宛先:</strong><br>
    <div id="billcustomer"></div><br>
  </div>

  <table>
    <thead>
      <tr>
        <th>品目</th>
        <th>金額</th>
        <th>備考</th>
      </tr>
    </thead>
    <tbody id="invoice-body">
      <!-- JavaScriptで明細を追加 -->
    </tbody>
    <tfoot>
      <tr class="total">
        <td colspan="1">合計金額（税込）</td>
        <td id="total-amount">¥0</td>
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
  const customerbill = document.getElementById("billcustomer");

  numberInput.addEventListener("input", () => {
    const number = numberInput.value.trim();
    const name = customerMap[number] || ""; 
    customerInput.value = name;
    customerbill.textContent = name;
  });

  document.getElementById('loadData').addEventListener('click', async () => {
  const number = document.getElementById('number').value.trim();
  const start = document.getElementById('start').value;
  const end = document.getElementById('end').value;

  if (!number || !start || !end) {
    alert("顧客番号と期間を入力してください");
    return;
  }

  const res = await fetch(`filter_meisai.php?number=${number}&start=${start}&end=${end}`);
  const data = await res.json();

  const tbody = document.getElementById('invoice-body');
  const billCustomer = document.getElementById('billcustomer');
  const customerInput = document.getElementById('customer');
  const totalAmount = document.getElementById('total-amount');

  tbody.innerHTML = '';
  let total = 0;

  if (data.length > 0) {
    billCustomer.textContent = data[0].company;
    customerInput.value = data[0].company;

    data.forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${row.item}</td>
        <td>¥${Number(row.amount).toLocaleString()}</td>
        <td>${row.note}</td>
      `;
      tbody.appendChild(tr);
      total += Number(row.amount);
    });
  } else {
    billCustomer.textContent = '';
    customerInput.value = '';
  }

  totalAmount.textContent = `¥${total.toLocaleString()}`;
});


</script>
    
</body>
</html>