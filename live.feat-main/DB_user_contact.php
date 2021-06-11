<?php
    require_once('DB_connect.php');

    Class User_contact extends DB_C {
        protected $dbh = $this->dbLink();

        //問い合わせ
        public function inquiry($category, $problem) {
            $this->table_name = 'Contacts';
            $problem = preg_replace("/( |　)/", "", $problem);
            if (!isset($_SESSION["GuestID"])) {
                if (!empty($category)) {
                    if (!empty($problem)) {

                        $sql = "INSERT INTO $this->table_name
                        (C_UserID, C_Datetime, C_Category, C_Inquiry)
                        VALUES
                        (C_UserID, C_Datetime, C_Category, C_Inquiry)";

                        $dbh->beginTransaction();
                        $date = date("Y-m-d H:i:s");
                        try {
                            $stmt = $dbh->prepare($sql);
            
                            //sqlインジェクション対策
                            $stmt->bindValue(':C_UserID', $_SESSION["UserID"], PDO::PARAM_INT);
                            $stmt->bindValue(':C_Datetime', $date, PDO::PARAM_STR);
                            $stmt->bindValue(':C_Category', $category, PDO::PARAM_INT);
                            $stmt->bindValue(':C_Inquiry', $problem, PDO::PARAM_STR);
            
                            //実行
                            $stmt->execute();
                            $dbh->commit();
                            echo 'Success　:　問い合わせは送信されました';
                            header("Location: index.html");
                            exit();
                        }
                        catch (PDOException $e) {
                            $dbh->rollBack();
                            exit($e);
                        }   
                    }    
                }
            }
            exit();
        }

        //通報
        public function report($targetid, $category, $problem) {
            $this->table_name = 'Reports';
            $targetid = preg_replace("/( |　)/", "", $targetid);
            $category = preg_replace("/( |　)/", "", $category);
            $problem = preg_replace("/( |　)/", "", $problem);
            if (!isset($_SESSION["GuestID"])) {
                if (!empty($targetid) {
                    if (!empty($category)) {
                        if (!empty($problem)) {
    
                            $sql = "INSERT INTO $this->table_name
                            (R_UserID, R_AccusedUserID, R_Datetime, R_Category, R_Inquiry)
                            VALUES
                            (R_UserID, R_AccusedUserID, R_Datetime, R_Category, R_Inquiry)";
    
                            $dbh->beginTransaction();
                            $date = date("Y-m-d H:i:s");
                            try {
                                $stmt = $dbh->prepare($sql);
                
                                //sqlインジェクション対策
                                $stmt->bindValue(':R_UserID', $_SESSION["UserID"], PDO::PARAM_INT);
                                $stmt->bindValue(':R_AccusedUserID', $targetid, PDO::PARAM_INT);
                                $stmt->bindValue(':R_Datetime', $date, PDO::PARAM_STR);
                                $stmt->bindValue(':R_Category', $category, PDO::PARAM_INT);
                                $stmt->bindValue(':R_Inquiry', $problem, PDO::PARAM_STR);
                
                                //実行
                                $stmt->execute();
                                $dbh->commit();
                                echo 'Success　:　通報は立案されました';

                                //通報数累計｜凍結判断
                                $this->ban($targetid);

                                header("Location: index.html");
                                exit();
                            }
                            catch (PDOException $e) {
                                $dbh->rollBack();
                                exit($e);
                            }   
                        }   
                    }
                }
            }
            exit();
        }

        //凍結
        protected function ban($id) {
            $sql_search = "SELECT * FROM Bans WHERE B_UserID = $id";
            $stmt = $dbh->query($sql);
            if ($stmt->fetchall(PDO::FETCH_ASSOC)) {
                echo '通報対象は他ユーザーの通報より２週間の凍結処分を受けています';
            }
            else {
                $sql_record = "SELECT COUNT(
                    DISTINCT CONCAT(R_UserID, DATE_FORMAT(R_Datetime, '%Y%m%d'))
                ) FROM Reports 
                WHERE R_AccusedUserID = $id AND R_Datetime >= DATE_SUB(NOW(), INTERVAL '14' DAY)
                ORDER BY R_Datetime ASC, R_UserID ASC";
    
                $stmt = $dbh->query($sql_record);
                $R_count = $stmt->fetchColumn();
    
                if ($R_count >= 5) {
                    $sql_ban = "INSERT INTO Bans
                    (B_UserID, B_Expiration, B_StartTime)
                    VALUES
                    (B_UserID, B_Expiration, B_StartTime)";
        
                    $dbh->beginTransaction();
                    $date = date("Y-m-d H:i:s");
                    $expiration = date("Y-m-d H:i:s", strtotime("+2 week"));
                    try {
                        $stmt = $dbh->prepare($sql_ban);
                        
                        //sqlインジェクション対策
                        $stmt->bindValue(':B_UserID', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':B_Expiration', $expiration, PDO::PARAM_STR);
                        $stmt->bindValue(':B_StartTime', $date, PDO::PARAM_STR);
                        
                        //実行
                        $stmt->execute();
                        $dbh->commit();
                        echo 'Success　:　通報対象は２週間内５件以上の通報を受け、２週間の凍結処分を受けました';
                    }
                }
                else {
                    echo "システムはすでに自動的に通報対象に警告メールをおくりました<br>今後も対象の行動に対して注意を払います";
                }
            }
        }
    }
?>