<?php require_once('user_operation.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>payment</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://kit.fontawesome.com/5b2a0a04db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/commom.css">
    <link rel="stylesheet" href="css/payment.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
    <?php include('header_side.txt'); ?>
    
    <!-- CONTENTS -->
    <section class="payment_container">
        <div class="payment_container_head">
            <h1>Payment</h1>
            <p>Choose payment method below</p>
        </div>

        <form action="user_operation.php" method="POST">
        <div class="payment_logo_area">

            <label class="radio">
                <input type="radio" name="method" value="credit">
            <div class="payment_logo">
                <i class="fas fa-credit-card" ></i>
            </div>
            </label>

            <label class="radio">
                <input type="radio" name="method" value="paypal">
            <div class="payment_logo">
                <i class="fab fa-cc-paypal"></i>
            </div>
            </label>
            
            <label class="radio">
                <input type="radio" name="method" value="amazon">
            <div class="payment_logo">
                <i class="fab fa-amazon-pay"></i>
            </div>
            </label>
        </div>

        <div class="info_area">
            <div class="billing_info">
                <h3>Billing Info</h3>
                <!-- <form action="" method="POST"> -->
                    <div class="up_forms">
                <div class="form">
                <h4>FULL NAME</h4>
                <p><input type="text" name="fullname" placeholder="YAMADA TARO（大文字）" class="input_payment"></p>
                </div>

                <div class="form">
                <h4>PREFECTURE</h4>
                <p><input type="text" name="prefecture" placeholder="Tokyo（大文字、小文字）" class="input_payment"></p>
                </div>
                    </div>

                    <div class="mid_forms">
                <div class="form">
                <h4>CITY</h4>
                    <p><input type="text" name="city" placeholder="Tokyo（大文字、小文字）" class="input_payment"></p>
                </div>

                <div class="form">
                    <h4>ZIP CODE</h4>
                    <p><input type="text" name="zipcode" placeholder="1600023" class="input_payment">
                </div>
                    </div>

                    <div class="bottom_forms">
                <div class="form">
                <h4>ADRESS</h4>
                    <p><input type="text" name="address" placeholder="東京都新宿区西新宿1-7-3" class="input_payment"></p>
                </div>
                    </div>
                    
                <!-- </form> -->
            </div>

            <div class="creditcard_info">
                <h3>Credit Card Info</h3>
                <!-- <form action="" method="POST"> -->
                    <div class="top_forms">
                        <div class="form">
                        <h4>CARD NUMBER</h4>
                        <p><input type="text" name="card_number" placeholder="1234567890123456" class="input_payment" autocomplete="off"></p>
                        </div>
                        <i class="fab fa-cc-visa"></i>
        
                    </div>
                    
                    <div class="up_forms">
                        <div class="form">
                            <h4>EXPIRE DATE</h4>
                            <p><input type="text" name="expiredate" placeholder="202508" class="input_payment"></p>
                        </div>
                        <div class="form">
                            <h4>CARDHOLDER NAME</h4>
                            <p><input type="text" name="holdername" placeholder="YAMADA TARO（大文字）" class="input_payment"></p>
                        </div>
                    </div>
        
                            <div class="down_forms">
                        <div class="form">
                        <h4>CVV</h4>
                            <p><input type="text" name="card_cvv" placeholder="＊＊＊" class="input_payment"></p>
                        </div>  
                        
                        
                    </div>
                </div>
        </div>
        <div class="submit">
            <input type="submit" value="Submit" name="pay">
        </div>
            </form>
    </section>

</body>
</html>
