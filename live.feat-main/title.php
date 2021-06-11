<?php
    require_once('user_operation.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>title</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://kit.fontawesome.com/5b2a0a04db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/commom.css">
    <link rel="stylesheet" href="css/title.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
    <?php include('header_side.txt'); ?>

    <section class="title_section">
        <a href="./screen.php?LiveID=<?php echo $detail[0]["ChannelID"] ?>">
            <div class="pic">
                <img src="./image/1.jpg" alt="" width="520px" height="580px">
            </div>
        </a>
        <div class="sentence">
            <h1>チャンネル <?php echo $detail[0]["ChannelID"] ?></h1>
            <h2><?php echo $detail[0]["Name"] ?></h2>
            <p>
                チャンネル：<?php echo $detail[0]["Name"] ?><br>
                チャンネルID：<?php echo $detail[0]["ChannelID"] ?><br>
                配信者ID：<?php echo $detail[0]["CreatorID"] ?><br>
                開始時間：<?php echo $detail[0]["StartTime"] ?><br>
                終了時間：<?php echo $detail[0]["EndTime"] ?><br>
                概要：<?php echo $detail[0]["Description"] ?><br>
            </p>
        </div>
    </section>
    
</body>
</html>
