<?php
    session_start();
    if (isset($_SESSION["newCreateID"])) {
        $newID = $_SESSION["newCreateID"];
    }
    else {
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
<div class="mist"></div>

<form action="user_operation.php" method="POST">
    <div class="form">

    <p class="top_input"><input type="text" placeholder=
    <?php
        if(isset($newID)) {
            echo "新規ユーザーID：".$newID;
        }
        else {
            echo "ユーザーID";
        }
    ?>
    name="UserID" class="input_login"></p>

    <p class="top_input"><input type="password" placeholder="パスワード" name="Password" class="input_login"></p>
    <ul>
        <li><input type="submit" value="ログイン" name="login"></li>
        <li><a href="./signin.html"><button type="button">新規登録</button></a></li>
    </ul>
    </div>
</form>   
</body>
</html>
