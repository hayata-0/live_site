<?php
    require_once('DB_connect.php');
    Class Channel_record extends DB_C {
        protected $table_name;
        
        //配信リスト                                 
        public function channelList() {
            $sql = "SELECT * FROM Channels";        //の最新９件 ORDER BY L_StartTime DESC limit 9
            $dbh = $this->dbLink();
            $stmt = $dbh->query($sql);
            $list = $stmt->fetchall(PDO::FETCH_ASSOC);
            return $list;
        }
        
        //クリックした配信の遷移｜詳細
        public function channelDetail($detailID) {
            $sql = "SELECT * FROM LiveStreams L
                INNER JOIN Channels C
                ON L.ChannelID = C.ChannelID
                WHERE L.ChannelID = $detailID";
            $dbh = $this->dbLink();    
            $stmt = $dbh->query($sql);
            
            if ($detail = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                return $detail;
            }
            else {
                //header("Location: notFound.html");
                exit("チャンネルはまだ予約されていない");
            }
        }

        public function channelFav() {
            $sql = "SELECT channelID, Favorites FROM Channels";
            $dbh = $this->dbLink();    
            $stmt = $dbh->query($sql);
            
            if ($result = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                return $result;
            }
            else {
                exit("");
            }
        }

        public function updateFav($channelID, $favs) {
            $sql_update = "UPDATE Channels SET Favorites = $favs WHERE ChannelID = $channelID";
            $dbh = $this->dbLink();
            $dbh->beginTransaction();
            try {
                $stmt = $dbh->prepare($sql_update);
                $stmt->execute();
                $dbh->commit();
                return "success";
            }
            catch (PDOException $e) {
                $dbh->rollBack();
                exit();
            }
        }

        public function liveDetail($liveID) {
            $sql = "SELECT * FROM LiveStreams L
                INNER JOIN Channels C
                ON L.ChannelID = C.ChannelID
                WHERE L.LiveStreamID = $liveID";
            $dbh = $this->dbLink();    
            $stmt = $dbh->query($sql);
            
            if ($detail = $stmt->fetchall(PDO::FETCH_ASSOC)) {
                $current_date = date("Y-m-d H:i:s");
                if ($current_date >= $detail[0]["StartTime"] && $current_date <= $detail[0]["EndTime"]) {
                    return $detail;
                }
                else {
                    exit('ライブ配信まだ始まっておりません');
                }
            }
            else {
                //header("Location: notFound.html");
                exit("ライブルームをもう一度確認してください");
            }
        }

        //チャットレコーダー
        //public function chat() {}
    }
?>
