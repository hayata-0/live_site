<?php
    require_once('user_operation.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>screen</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://kit.fontawesome.com/5b2a0a04db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/commom.css">
    <link rel="stylesheet" href="css/screen.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
    <?php include('header_side.txt'); ?>

    <section class="main">
        <div class="screen">
            <iframe src=""></iframe>
        </div>

        <div class="underscreen">
            <div class="detaile">
                <h3>概要</h3>
                <p>
                    ライブ配信ID：<?php echo $detailLive[0]["LiveStreamID"] ?><br>
                    配信者ID：<?php echo $detailLive[0]["CreatorID"] ?><br>
                    開始時間：<?php echo $detailLive[0]["StartTime"] ?><br>
                    終了時間：<?php echo $detailLive[0]["EndTime"] ?><br>
                </p>
            </div>
            <div class="lorem">
                <h3><?php echo $detailLive[0]["Name"] ?></h3>
                <p><?php echo $detailLive[0]["Description"] ?><br>チャット機能を使うにはまず　https://20.78.120.148:3000<br>にアクセスしてまたこの画面に戻して、<br>ニックネームを入力してから使用してください</p>
            </div>
            <div class="coment">
                <iframe src='' width="300px" height="400px"></iframe>
            </div>
        </div>
    </section>

</body>
</html>
