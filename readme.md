# プロダクトのタイトル
 請求書発行アプリ
## プロダクトの紹介
明細を記入して顧客番号と期間をもとに請求書を発行できます。

## 工夫した点，こだわった点

JSにて顧客情報を記載しておいて、企業名を自動反映させた。
<br>CSVのデータで300,000と記載すると列がずれることがあったので、
<br>$write_data = [$number, $customer, $date, $merchandise, $amount, $detail];で
<br>セルを切り分けた。

## 苦戦した点，共有したいハマりポイントなど

住所やインボイス番号の記載や、明細の修正など、
今ある請求書発行アプリは作り込まれているなと改めて感じた。
