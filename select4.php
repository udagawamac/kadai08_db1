<?php
//1. DB接続します
try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');} catch (PDOException $e) {
  exit('DB_CONNECT:'.$e->getMessage());
}

//２．データ登録SQL作成
$sql = "SELECT * FROM gs_bm_table";
$stmt = $pdo->prepare("$sql");
$status = $stmt->execute(); //true or false

//３．データ表示
// $view="";  //無視
if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQL_ERROR:".$error[2]);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]

// var_dump($values);
//JSONに値を渡す場合に使う
$json = json_encode($values,JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="./img/parette.png">
    <title>集計結果</title>
</head>

<!-- Main[Start] -->

<body class="haikei">
    <main>
        <h3>アンケート結果</h3>
        <div>
            <table border='1'>
                <tr>
                    <!-- <th>番号</th> -->
                    <th>名前</th>
                    <th>E-mail</th>
                    <th>好きな色</th>
                    <th>入力日時</th>
                </tr>
                <?php
foreach($values as $value){ ?>
                <tr>
                    <!-- <td><?=$value["id"]?></td> -->
                    <td><?=$value["name"]?></td>
                    <td><?=$value["email"]?></td>
                    <td><?=$value["color"]?></td>
                    <td><?=$value["indate"]?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <p><a href="index2.php">入力画面に戻る</a></p>
    </main>
    <!-- Main[End] -->
    <!-- グラフを表示するキャンバス -->
    <div style="width:300px;height:300px;margin-left:auto;margin-right:auto;margin-bottom:20px;">
        <canvas id="colorChart"></canvas>
    </div>
    <!-- 合計値を表示する領域 -->
    <h3 id="totalCount"></h3>
 
    <!-- <script> -->
    <!-- JSON受け取り -->
    <script>
        const a = '<?php echo $json; ?>';
        const data = JSON.parse(a);
        console.log(a);

        // カウントを格納するオブジェクトを初期化
        const colorCount = {};

        // 配列を走査し、各色の出現回数を数える
        data.forEach(item => {
            const color = item.color;
            if (colorCount[color]) {
                colorCount[color]++;
            } else {
                colorCount[color] = 1;
            }
        });
        // 確認のため結果を出力
        console.log(colorCount);

        // グラフのデータを準備
        const labels = Object.keys(colorCount);
        const counts = Object.values(colorCount);
        const totalCount = counts.reduce((total, count) => total + count, 0);

        // キャンバス要素を取得
        const ctx = document.getElementById('colorChart').getContext('2d');

        // 円グラフを描画
        const colorChart = new Chart(ctx, {
            type: 'pie',
            data: {
                // labels: labels,
                datasets: [{
                    label: 'Color Count',
                    data: counts,
                    backgroundColor: [
                        'white',
                        'green',
                        'red',
                        'blue',
                        'pink',
                        'yellow',
                        'black',
                        'purple'
                    ],
                    borderColor: 'rgba(0, 0, 0, 0)',
                }]
            },
        });
               // 合計値を表示する要素に合計値を挿入
               document.getElementById('totalCount').innerText = '回答総数: ' + totalCount + '人';
    </script>
    <!-- </script> -->
</body>

</html>