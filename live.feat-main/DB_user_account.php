<?php

    require_once('DB_connect.php');

    Class User_check extends DB_C {
        protected $table_name = 'Users';

        //アカウントステータス判断
        public function userStatus() {
            if (isset($_SESSION["UserID"])) {
                $sql = "SELECT UserID, Subscribe_Expiration, Subscribe_StartDatetime FROM Users WHERE UserID = ".$_SESSION["UserID"];
                $dbh = $this->dbLink();
                $stmt = $dbh->query($sql);
   
                if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                    $current_date = date("Y-m-d H:i:s");
                    if ($result[0]['Subscribe_Expiration'] == '0000-00-00 00:00:00') {
                        $_SESSION["User_subpass"] = '未購入';
                    }
                    elseif($result[0]['Subscribe_Expiration'] != '0000-00-00 00:00:00' && $result[0]['Subscribe_Expiration'] <= $current_date) {
                        $_SESSION["User_subpass"] = '有効切れ';
                    }
                    else {
                        $_SESSION["User_subpass"] = '期間中';
                        $_SESSION["Subscribe"] = $result[0]["Subscribe_StartDatetime"]." ~ ".$result[0]["Subscribe_Expiration"];
                    }
                }
                /*
                if (isset($_SESSION["GuestID"])) {
                    $this->guestDelete($_SESSION["GuestID"]);
                    unset($_SESSION["GuestID"]);
                    //echo "GuestIDはクリアされた";
                }
                */
            }
            else {
                header("Location: login.php");
                exit();
            }
            /*
            elseif (isset($_SESSION["GuestID"])) {
                if ($this->guestSearch($_SESSION["GuestID"])[0] == 1) {
                    //echo "登録済みゲスト";
                }
                else {
                    $this->guestWrite($_SESSION["GuestID"]);
                    //echo "GuestIDが更新された<br>";
                }
            }
            else {
                $_SESSION["GuestID"] = guestUser();
                //echo "新GuestIDが生成された<br>";
            }
            */
        } 
        
        //既存ログイン
        public function loginCheck($UserID, $Password) {
            $UserID = preg_replace("/( |　)/", "", $UserID);
            $Password = preg_replace("/( |　)/", "", $Password);
            if (!empty($UserID) && preg_match('/^[0-9]{1,12}$/', $UserID)) {
                if (preg_match('/^[A-Za-z0-9]{1,36}$/', $Password)) {
                    $sql = "SELECT * FROM Users WHERE UserID = $UserID";
                    $dbh = $this->dbLink();
                    $stmt = $dbh->query($sql);
        
                    if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                        $sql_banCheck = "SELECT * FROM Bans WHERE UserID = $UserID";
                        $stmt = $dbh->query($sql_banCheck);
                        if ($stmt->fetchall(PDO::FETCH_ASSOC)) {
                            exit('こちらのアカウントは大量通報受け、凍結状態におります');
                        }
                        if (password_verify($Password, $result[0]["PassWord"])) {
                            //セッション再発行とindexページ
                            session_regenerate_id(true);
                            session_id();
                            $_SESSION["UserID"] = $result[0]["UserID"];
                            $_SESSION["UserName"] = $result[0]["UserName"];
                            $_SESSION["Email"] = $result[0]["Email"];
                            header("Location: display.php");
                            exit();
                        }
                    }
                }
            }
            exit('ユーザーID・パスワードに誤りがあり');
        }

        //ログアウト
        public function logout() {
            $_SESSION = array();
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 42000, '/');
            }
            session_destroy();
            header("Location: index.html");
            exit();
        }

        //新規登録
        public function newCreate($UserName, $Password, $Email) {
            if ($this->newCheck($UserName, $Password, $Email)) {
                $hash_pass = password_hash($Password, PASSWORD_BCRYPT);
                $sql_create = 'INSERT INTO Users '.'
                (PassWord, UserName, Email, Subscribe_Expiration, Subscribe_StartDatetime)
                VALUES '.'
                (:PassWord, :UserName, :Email, "0000/00/00", "0000/00/00")';

                $dbh = $this->dbLink();
                $dbh->beginTransaction();
                try {
                    $stmt = $dbh->prepare($sql_create);

                    //sqlインジェクション対策
                    $stmt->bindValue(':PassWord', $hash_pass, PDO::PARAM_STR);
                    $stmt->bindValue(':UserName', $UserName, PDO::PARAM_STR);
                    $stmt->bindValue(':Email', $Email, PDO::PARAM_STR);

                    //実行
                    $stmt->execute();
                    $_SESSION["newCreateID"] = $dbh->lastInsertId();
                    $dbh->commit();

                    header("Location: login.php"); //新規アカウントの登録完了
                    exit();
                }
                catch (PDOException $e) {
                    $dbh->rollBack();
                    //echo "同期エラー";
                    exit($e->getMessage());
                }
            }
            exit('ユーザーネームあるいはメールアドレスはすでに使用されています');
        }

        //新規登録チェック
        protected function newCheck($UserName, $Password, $Email) {
            $UserName = preg_replace("/( |　)/", "", $UserName);
            $Password = preg_replace("/( |　)/", "", $Password);
            $Email = preg_replace("/( |　)/", "", $Email);
            $flag = false;
            if (mb_strlen($UserName) >= 0 && mb_strlen($UserName) <= 16) {
                if (preg_match('/^[A-Za-z0-9]{1,36}$/', $Password)) {
                    if (preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $Email)) {
                        $sql_searchName = "SELECT * FROM $this->table_name WHERE UserName = '".$UserName."'";
                        $dbh = $this->dbLink();
                        $stmt = $dbh->query($sql_searchName);
                        if ($stmt->fetchall(PDO::FETCH_ASSOC)) {
                            //name使用済み
                        }
                        else {
                            $sql_searchMail = "SELECT * FROM $this->table_name WHERE Email = '".$Email."'";
                            $dbh = $this->dbLink();
                            $stmt = $dbh->query($sql_searchMail);
                            if ($stmt->fetchall(PDO::FETCH_ASSOC)) {
                                //email使用済み
                            }
                            else {
                                $flag = !$flag;
                            } 
                        }
                        return $flag;
                    }
                }
            }
            exit('入力空白'); //入力空白
        }

        //Guest用仮ログイン
        function guestUser() {
            $str = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
            $GuestID = '';
            do{
                for ($i = 1 ; $i <= 8 ; $i ++) {
                    $GuestID .= $str[rand(0, count($str)) - 1];
                }
            } while ($this->guestSearch($GuestID)[0] == 1);
            $this->guestWrite($GuestID);
            return $GuestID;
        }

        //txt検索
        protected function guestSearch($id) {
            $fp_r = fopen("GuestUser.txt", "r");
            $flag = 0;
            $line = 0;
            while (!feof($fp_r)) {
                $tmp = fgets($fp_r);
                if (strpos($tmp, $id) === 0) {
                    var_dump($tmp);
                    $flag = 1;
                    break;
                }
                $line ++;
            }
            fclose($fp_r);
            $result = array($flag, $line);
            return $result;
        }
    
        //GuestID書き込み
        protected function guestWrite($id) {
            $fp_a = fopen("./GuestUser.txt", "a");
            fwrite($fp_a, "\n".$id."/".session_id());
            fclose($fp_a);
        }
        
        //GuestID削除
        protected function guestDelete($id) {
            if ($this->guestSearch($id)[0] == 1) {
                $file = file("GuestUser.txt");
                unset($file[$this->guestSearch($id)[1]]);
                file_put_contents("GuestUser.txt", $file);
            }
        }
    }
?>
