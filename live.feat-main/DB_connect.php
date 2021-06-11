<?php
    Class DB_C {
        protected function dbLink() {
            $dsn = 'mysql:host=;dbname=localdb;charset=utf8';
            $user = 'azure';
            $pass = '';
            //echo '接続成功';
            try {
                $dbh = new PDO($dsn, $user, $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]
                );   
            }
            //echo '接続失敗';
            catch (PDOException $e) {
                echo '接続失敗'.$e->getMessage();
                exit();
            }
            return $dbh;
        }

        public function nameCheck() {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $lastname = pathinfo($_SERVER['HTTP_REFERER'], PATHINFO_FILENAME);
                return $lastname;
            }
            else {
                $_SERVER['HTTP_REFERER'] = NULL;
            }
        }
    } 
?>
