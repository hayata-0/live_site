<?php require_once('user_operation.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>display</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://kit.fontawesome.com/5b2a0a04db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/commom.css">
    <link rel="stylesheet" href="css/display.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/channel_fav.js"></script>
</head>
<body>
    <?php include('header_side.txt'); ?>
    
    <section class="main">

        <?php
            $i = 1;
            foreach ($list as $vertical => $line) {
                if ($line) {
                    include('display_contents.php');
                    ++$i;
                }
            }
        ?>
        
        
        <div class="content">
            <div class="pic">

            </div>

            <div class="underpic">

                <label class="fav">
                    <input type="checkbox">
                    <span class="material-icons heart">favorite</span>
                    <div class="riple"></div>
                </label>
                <span class="favnum">609</span>

                <span class="material-icons comment">insert_comment</span>
                <span class="commentnum">120</span>

                <span class="share">SHARE</span>
            </div>
        </div>

    </section>

</body>
</html>