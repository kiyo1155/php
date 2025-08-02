<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>請求書発行</title>
  <link rel="stylesheet" href="invoicestyle.css">
  <style>
    .bill {
        width: 180mm; /* A4: 210mm - マージン両端で30mm（15mmずつ） */
        padding: 10mm;
        margin: 0 auto;
        background: white;
        box-sizing: border-box;
      }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      width: 100px;
    }
    .invoice-title {
      font-size: 32px;
      font-weight: bold;
    }
    .info-container {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    .info-column {
      width: 45%;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }
    .total {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <fieldset>
    <legend>明細選択</legend>
    <div class="content">
      顧客番号: <input type="text" name="number" id="number">
    </div>
    <div class="content">
      顧客名: <input type="text" name="customer" id="customer" readonly>
    </div>
    <div class="content">
      期間: <input type="date" name="date" id="start">～<input type="date" name="date" id="end">
    </div>
    <div class="content">
      <button type="button" id="loadData">明細読み込み</button>
    </div>
    <div class="content">
      <button id="pdf">発行</button>
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
        <div id="billcustomer"></div>
      </div>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    // 顧客マップ（顧客番号ごとに情報を定義）
    const customerMap = {
      "1001": {
        name: "株式会社A",
        address: "〒100-0001 東京都千代田区1-1-1",
        phone: "03-1111-1111"
      },
      "1002": {
        name: "株式会社B",
        address: "〒200-0002 大阪府大阪市2-2-2",
        phone: "06-2222-2222"
      },
      "1003": {
        name: "株式会社C",
        address: "〒300-0003 愛知県名古屋市3-3-3",
        phone: "052-333-3333"
      }
    };

    const numberInput = document.getElementById("number");
    const customerInput = document.getElementById("customer");
    const customerBill = document.getElementById("billcustomer");

    numberInput.addEventListener("input", () => {
      const number = numberInput.value.trim();
      const info = customerMap[number];
      if (info) {
        customerInput.value = info.name;
        customerBill.innerHTML = `
          ${info.name}<br>
          ${info.address}<br>
          TEL: ${info.phone}
        `;
      } else {
        customerInput.value = "";
        customerBill.innerHTML = "";
      }
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
      const totalAmount = document.getElementById('total-amount');

      tbody.innerHTML = '';
      let total = 0;

      if (data.length > 0) {
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
      }

      totalAmount.textContent = `¥${total.toLocaleString()}`;
    });

    document.getElementById("pdf").addEventListener("click", () => {
      const element = document.querySelector(".bill");
      const opt = {
        margin: [10, 10, 10, 10],
        filename: 'invoice.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 1 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
      };
      html2pdf().set(opt).from(element).save();
    });
  </script>
</body>
</html>
