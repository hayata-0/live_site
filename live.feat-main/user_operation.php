<?php
    date_default_timezone_set('Asia/Tokyo');
    session_start();
    //現在のページ
    require_once('DB_connect.php');
    $db = new DB_C;
    $htmlname = $db->nameCheck();
    $bt = debug_backtrace();
    if ($bt) {
        $currentname = basename($bt[0]["file"],".php");
    }
    else {
        $currentname = '';
    }

    //ログイン｜新規｜ログアウト
    require_once('DB_user_account.php');
    $User_status = new User_check;

    if ($htmlname == 'login') {
        if (isset($_POST["login"])) {
            $User_status->loginCheck($_POST["UserID"], $_POST["Password"]);
        } 
    }
    if ($htmlname == 'signin') {
        if (isset($_POST["create"])) {
            $User_status->newCreate($_POST["UserName"], $_POST["Password"], $_POST["Email"]);
        }
    }

    //User|Guest
    $User_status->userStatus();

    /*
    if (isset($_POST["logout"])) {
        $User_status->logout();
    }
    */
    
    //問い合わせ｜通報｜凍結機能
    /*
    if ($htmlname == 'contact') {
        if (isset($_POST["inquiry"]) {
            $User_managment->inquiry($_POST["category"], $_POST["problem"]);
        }
        if (isset($_POST["report"]) {
            $User_managment->report($_POST["targetid"], $_POST["category"], $_POST["problem"]);
        }
    }
    */

    //サブスク購入機能
    if ($htmlname == 'payment') {
        if (isset($_POST["pay"])) {
            require_once('DB_user_subscription.php');
            $User_subscrpt = new User_subscription;
            if (isset($_POST["method"]) && $_POST["method"] == 'credit') {
                $User_subscrpt->credit($_POST);
            }
            else {
                $User_subscrpt->pay($_POST);
            }
        }
    }

    //チャンネル｜配信詳細
    if ($currentname == 'display') {
        require_once('DB_channel_record.php');
        $Record = new Channel_record;
        $list = $Record->channelList();
    }
    if ($htmlname == 'display') {
        require_once('DB_channel_record.php');
        $Record = new Channel_record;
        if (isset($_POST["sync_fav"])) {
            $sync_result = $Record->channelFav();
            echo json_encode($sync_result);
        }
        else if (isset($_POST["update_fav"])) {
            $Record->updateFav($_POST["update_channelID"], $_POST["update_fav"]);
        }
        else {
            $sync_result = array(
                "status" => 'fail'
            );
        }
    }
    if ($currentname == 'title') {
        if (!empty($_GET['detailID'])) {
            if ($_SESSION["User_subpass"] == '期間中') {
                require_once('DB_channel_record.php');
                $Record = new Channel_record;
                $detail = $Record->channelDetail($_GET['detailID']);
            }
            else {
                header("Location: subsucribe.php");
                //var_dump('パスを購入してください');
            }
        }
        else {
            header("Location: display.php");
        }
    }
    if ($currentname == 'screen') {
        if (!empty($_GET['LiveID'])) {
            if ($_SESSION["User_subpass"] == '期間中') {
                require_once('DB_channel_record.php');
                $Record = new Channel_record;
                $detailLive = $Record->liveDetail($_GET['LiveID']);
            }
            else {
                header("Location: subsucribe.php");
                //var_dump('パスを購入してください');
            }
        }
        else {
            header("Location: display.php");
        }
    }

    //チャット記録機能
    /*
    if ($htmlname == '') {
        $Record->chat();
    }
    */

    if (isset($_GET['sign'])) {
        $User_status->logout();
    }
?>
