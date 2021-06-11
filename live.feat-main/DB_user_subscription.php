<?php
    require_once('DB_connect.php');
    Class User_subscription extends DB_C {

        //決済情報保有者
        public function credit($data) {         
            foreach ($data as $key => $value) {
                $data["$key"] = preg_replace("/( |　)/", "", $value);
                if (empty($data["$key"])) {
                    exit('未入力項目あり');
                }
            }
            if (preg_match('/^[0-9]{16}$/', $data["card_number"])) {
                if (preg_match('/^[0-9]{6}$/', $data["expiredate"])) {
                    if (preg_match('/^[A-Z]{0,32}+$/', $data["holdername"])) {
                        if (preg_match('/^[0-9]{3}$/', $data["card_cvv"])) {   
                            //var_dump('1');
                            //exit('1');
                            $this->searchExistUsers($data);
                        }
                    }
                }
            }
            var_dump($data);
            exit('未入力項目ありまたは、入力した項目に様式と異なる所がおります');
        }

        //SubscribePurchasesテーブル参照
        protected function searchExistUsers($data) {
            $sql = "SELECT * FROM SubscribePurchases WHERE PurchaseUserID = ".$_SESSION["UserID"];
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);

            if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                $check_C = $this->searchCredit($data, $result[0]["CreditSettlementID"]);
                //$check_P = $this->searchPersonal($data, $result[0]["PersonalDataID"]);

                if ($check_C == false ) {//&& $check_P == false
                    $this->updateSub();
                    $this->updateUser();
                    header("Location: display.php");
                    exit();
                }
                else {
                    exit('入力した決済情報はまだ登録されていません、一回完全入力し使用してから、簡易化決済を再び試してみてください');
                }
            }
            exit('まだ購入されたことがないユーザーは、先に完全入力し決済してから、簡易化決済を再び試してみてください');
        }


        //新規pay part
        public function pay($data) {           
            $this->check_P($data);
        }

        protected function check_P($data) {
            foreach ($data as $key => $value) {
                $data["$key"] = preg_replace("/( |　)/", "", $value);
                if (empty($data["$key"])) {
                    exit('未入力項目あり');
                }
            }
            if (preg_match('/^[A-Z]{1,32}+$/', $data["fullname"])) {
                if (preg_match('/^[A-Za-z]{1,16}$/', $data["prefecture"])) {
                    if (preg_match('/^[A-Za-z]{0,32}$/', $data["city"])) {
                        if (preg_match('/^[0-9]{7}$/', $data["zipcode"])) {
                            if (preg_match('/^[0-9]{16}$/', $data["card_number"])) {
                                if (preg_match('/^[0-9]{6}$/', $data["expiredate"])) {            //[0-9]{2}\/[0-9]{2}
                                    if (preg_match('/^[A-Z]{0,32}+$/', $data["holdername"])) {
                                        if (preg_match('/^[0-9]{3}$/', $data["card_cvv"])) {        
                                            $this->searchSubscribe($data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            exit('入力した項目に様式と異なる所がおります');
        }

        //SubscribePurchasesテーブル参照
        protected function searchSubscribe($data) {
            $sql = "SELECT * FROM SubscribePurchases WHERE PurchaseUserID = ".$_SESSION["UserID"];
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);
            if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                $rowID = $this->tablerow();
                $row_defualt = $rowID;
                for ($i = count($result)-1 ; $i >= 0 ; $i --) {                              
                    var_dump($rowID);
                    $check_C = $this->searchCredit($data, $result[$i]["CreditSettlementID"]);
                    $check_P = $this->searchPersonal($data, $result[$i]["PersonalDataID"]);
        
                    if (!$check_C && !$check_P) {
                        exit('完全一致な決済情報はすでに登録されました、Creditボタンを押し、簡易化決済を行ってください');
                    }
                    else if ($check_C && !$check_P) {
                        if ($result[$i]["CreditSettlementID"] <= $rowID[1]) {
                            $rowID[1] = $result[$i]["CreditSettlementID"];
                        }
                    }
                    else if (!$check_C && $check_P) {
                        if ($result[$i]["PersonalDataID"] <= $rowID[0]) {
                            $rowID[0] = $result[$i]["PersonalDataID"];
                        }
                    }
                    else {}                    
                }
                
                if ($rowID[0] == $row_defualt[0]) {
                    $row_0 = true;
                }
                else {
                    $row_0 = false;
                }
                if ($rowID[1] == $row_defualt[1]) {
                    $row_1 = true;
                }
                else {
                    $row_1 = false;
                }

                if ($row_1 && $row_0) {
                    $idC = $this->createCredit($data);
                    $idP = $this->createPersonal($data);
                    $this->creatSubscribe($idC, $idP);
                    $this->updateUser();
                    header("Location: display.php");
                    exit();
                }
                else if ($row_1 && !$row_0) {
                    $idC = $rowID[1];
                    $idP = $this->createPersonal($data);          
                }
                else if (!$row_1 && $row_0) {         
                    $idC = $this->createCredit($data);
                    $idP = $rowID[0];
                }
                else {
                    $idC = $rowID[1];
                    $idP = $rowID[0];
                } 
            }
            else {
                $idC = $this->createCredit($data);
                $idP = $this->createPersonal($data);  
            }

            $this->creatSubscribe($idC, $idP);
            $this->updateUser();
            header("Location: display.php");
            exit();
        }

        //テーブルレコード数取得
        protected function tablerow() {
            //$sql = "SELECT table_name, table_rows FROM information_schema.TABLES WHERE table_schema ='hew'";
            $sql = "SELECT (SELECT COUNT(*) FROM CreditSettlements AS C_COUNT),(SELECT COUNT(*) FROM PersonalData AS P_COUNT)";
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            $row = array(
                $result[0]['(SELECT COUNT(*) FROM PersonalData AS P_COUNT)'],
                $result[0]['(SELECT COUNT(*) FROM CreditSettlements AS C_COUNT)']
            );
            return $row;
        }


        //外部キーCreditSettlementsテーブル参照
        protected function searchCredit($data, $creditID) {
            $sql = "SELECT * FROM CreditSettlements WHERE SettlementID = $creditID";
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);
            $flg = true;

            if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                if ($data["card_number"] == $result[0]["CreditNumber"]) {
                    if ($data["expiredate"] == $result[0]["ExpireDate"]) {
                        if ($data["holdername"] == $result[0]["CardholderName"]) {
                            if ($data["card_cvv"] == $result[0]["CVV"]) {
                                $flg = !$flg;
                            }
                        }  
                    }
                }    
            }
            return $flg;
        }

        //外部キーPersonalDataテーブル参照
        protected function searchPersonal($data, $personalID) {
            $sql = "SELECT * FROM PersonalData WHERE PersonalDataID = $personalID";
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);
            $flg = true;

            if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                if ($data["fullname"] == $result[0]["FullName"]) {
                    if ($data["prefecture"] == $result[0]["Prefecture"]) {
                        if ($data["city"] == $result[0]["City"]) {
                            if ($data["zipcode"] == $result[0]["ZipCode"]) {
                                if ($data["address"] == $result[0]["address"]) {
                                    $flg = !$flg;
                                }
                            }
                        }  
                    }
                }   
            }
            return $flg;
        }

        //新規Credit
        protected function createCredit($data) {
            $sql_createC = 'INSERT INTO CreditSettlements '.'
            (CreditNumber, ExpireDate, CardholderName, CVV)
            VALUES '.'
            (:CreditNumber, :ExpireDate, :CardholderName, :CVV)';

            $dbh = $this->dbLink();
            $dbh->beginTransaction();
            try {
                $stmt = $dbh->prepare($sql_createC);

                //sqlインジェクション対策
                $stmt->bindValue(':CreditNumber', $data["card_number"], PDO::PARAM_STR);
                $stmt->bindValue(':ExpireDate', $data["expiredate"], PDO::PARAM_INT);
                $stmt->bindValue(':CardholderName', $data["holdername"], PDO::PARAM_STR);
                $stmt->bindValue(':CVV', $data["card_cvv"], PDO::PARAM_STR);

                //実行
                $stmt->execute();
                $ID = $dbh->lastInsertId();
                $dbh->commit();
                return $ID;
            }
            catch (PDOException $e) {
                $dbh->rollBack();
                //echo "同期エラー";
                exit($e->getMessage());
            }
        }

        //新規Credit
        protected function createPersonal($data) {
            $sql_createP = 'INSERT INTO PersonalData '.'
            (FullName, Prefecture, City, ZipCode, address)
            VALUES '.'
            (:FullName, :Prefecture, :City, :ZipCode, :address)';

            $dbh = $this->dbLink();
            $dbh->beginTransaction();
            try {
                $stmt = $dbh->prepare($sql_createP);

                //sqlインジェクション対策
                $stmt->bindValue(':FullName', $data["fullname"], PDO::PARAM_STR);
                $stmt->bindValue(':Prefecture', $data["prefecture"], PDO::PARAM_STR);
                $stmt->bindValue(':City', $data["city"], PDO::PARAM_STR);
                $stmt->bindValue(':ZipCode', $data["zipcode"], PDO::PARAM_STR);
                $stmt->bindValue(':address', $data["address"], PDO::PARAM_STR);

                //実行
                $stmt->execute();
                $ID = $dbh->lastInsertId();
                $dbh->commit();
                return $ID;
            }
            catch (PDOException $e) {
                $dbh->rollBack();
                //echo "同期エラー";
                exit($e->getMessage());
            }
        }

        protected function creatSubscribe($IDC, $IDP) {
            $current_date = date("Y-m-d H:i:s");
            $sql_createS = 'INSERT INTO SubscribePurchases '.'
            (PurchaseUserID, SubscribePlan, CreditSettlementID, PersonalDataID, PurchaseDatetime)
            VALUES '.'
            (:PurchaseUserID, :SubscribePlan, '.$IDC.', '.$IDP.', :PurchaseDatetime)';

            $dbh = $this->dbLink();
            $dbh->beginTransaction();
            try {
                $stmt = $dbh->prepare($sql_createS);

                //sqlインジェクション対策
                $stmt->bindValue(':PurchaseUserID', $_SESSION["UserID"], PDO::PARAM_INT);
                $stmt->bindValue(':SubscribePlan', 100, PDO::PARAM_INT);
                //$stmt->bindValue(':CreditSettlementID', $IDC, PDO::PARAM_INT);
                //$stmt->bindValue(':PersonalDataID', $IDP, PDO::PARAM_INT);
                $stmt->bindValue(':PurchaseDatetime', $current_date, PDO::PARAM_STR);

                //実行
                $stmt->execute();
                $dbh->commit();
            }
            catch (PDOException $e) {
                $dbh->rollBack();
                //echo "同期エラー";
                exit($e->getMessage());
            }
        }

        //Subscribe購入日時更新
        protected function updateSub() {
            $sql_update = "UPDATE SubscribePurchases SET PurchaseDatetime = :PurchaseDatetime WHERE PurchaseUserID = ".$_SESSION["UserID"];
            $dbh = $this->dbLink();
            $dbh->beginTransaction();
            $purchase_date = date("Y-m-d H:i:s");

            try {
                $stmt = $dbh->prepare($sql_update);

                //sqlインジェクション対策
                $stmt->bindValue(':PurchaseDatetime', $purchase_date, PDO::PARAM_STR);

                //実行
                $stmt->execute();
                $dbh->commit();
                
                echo 'Success　:　サブスク購入が成功しました';
            }
            catch (PDOException $e) {
                $dbh->rollBack();
                //echo "同期エラー";
                exit();
            }
        }

        //Users購入日時更新
        protected function updateUser() {
            $sql = "SELECT * FROM Users WHERE UserID = " . $_SESSION["UserID"];
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);

            if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                if ($result[0]["Subscribe_StartDatetime"] == "0000-00-00 00:00:00") {
                    $sql_update = "UPDATE Users SET Subscribe_StartDatetime = :Subscribe_StartDatetime, Subscribe_Expiration = :Subscribe_Expiration WHERE UserID = ".$_SESSION["UserID"];
                    $dbh->beginTransaction();
                    $purchase_date = date("Y-m-d H:i:s");
                    $expire_date = date("Y-m-d H:i:s", strtotime("+1 month"));

                    try {
                        $stmt = $dbh->prepare($sql_update);

                        //sqlインジェクション対策
                        $stmt->bindValue(':Subscribe_StartDatetime', $purchase_date, PDO::PARAM_STR);
                        $stmt->bindValue(':Subscribe_Expiration', $expire_date, PDO::PARAM_STR);

                        //実行
                        $stmt->execute();
                        $dbh->commit();
                        
                        //echo 'Success　:　購入が更新された';
                    }
                    catch (PDOException $e) {
                        $dbh->rollBack();
                        //echo "同期エラー";
                        exit();
                    }
                }
                else {
                    $sql_update = "UPDATE Users SET Subscribe_Expiration = DATE_ADD(Subscribe_Expiration,INTERVAL 1 MONTH) WHERE UserID = ".$_SESSION["UserID"];
                    $dbh->beginTransaction();
                    try {
                        $stmt = $dbh->prepare($sql_update);

                        //実行
                        $stmt->execute();
                        $dbh->commit();
                        
                        //echo 'Success　:　サブスクの期限が更新されました';
                    }
                    catch (PDOException $e) {
                        $dbh->rollBack();
                        //echo "同期エラー";
                        exit();
                    }
                }
            }
            else {
                exit('アカウントを一回ログアウトしてからまた試してみてください');
            }         
        }
    }
?>
