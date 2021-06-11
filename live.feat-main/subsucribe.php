<?php require_once('user_operation.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>subscribe</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <script src="https://kit.fontawesome.com/5b2a0a04db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/commom.css">
    <link rel="stylesheet" href="css/subscribe.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
    <?php include('header_side.txt'); ?>

    <!-- main -->
    <section class="sub_container">
        <div class="sub_sentence">
        <h1>月額コース</h1>
        <h2>お求めのプランでご購入してください</h2>
        <p>*プランがまだある場合に追加購入した場合はその日付にまたたされることになります。くれぐれもお間違いのないようおねがいいたします。</p>
        </div>

        <div class="plices">
            <div class="plice">
                <div class="per_month">
                    <h3>￥100</h3>
                    <span>/month</span>
                </div>
                <div class="circle plan_choice" id="low">
                    <span class="material-icons">add</span>
                </div>
            </div>
            <div class="plice">
                <div class="per_month">
                    <h3>￥200</h3>
                    <span>/month</span>
                </div>
                <div class="circle plan_choice" id="mid">
                    <span class="material-icons">add</span>
                </div>
            </div>
            <div class="plice">
                <div class="per_month">
                    <h3>￥300</h3>
                    <span>/month</span>
                </div>
                <div class="circle plan_choice" id="heigh">
                    <span class="material-icons">add</span>
                </div>

            </div>
        </div>
    </section>
    
    <div id="mask" class="hidden"></div>
    <section id="modal" class="hidden">
        <span id="close" class="material-icons">close</span>
        <p>よろしいですか?</p>
        <h1>¥<span id="plice"></span>/month</h1>
        <a id="linkHtml" href="./payment.php"><div id="jump">CONTINUE</div></a>
    </section>


    <footer>
        <div class="footer_container">
            <div class="footer_box">
                <small class="footer_head">CONTACT US</small>
                <small>+81 xx xxxx xxxx</small>
                <small>xxxx@mail.com</small>
                <small>Find a Store</small>
            </div>

            <div class="footer_box">
                <small class="footer_head">CUSTOMER RERVICE</small>
                <small>Contact us</small>
                <small>Ordering & Payment</small>
                <small>Shipping</small>
                <small>Returms</small>
                <small>FAQ</small>
            </div>

            <div class="footer_box">
                <small class="footer_head">INFORMATION</small>
                <small>About Adobe XD Kit</small>
                <small>Work With Us</small>
                <small>Privacy Pollcy</small>
                <small>Terms & Conditions</small>
                <small>Press Enquires</small>
            </div>

            <div class="footer_sub">
                <small class="footer_head">Subscribe to Laive.feat via Email</small>
                <small>Excepteur sint occaecat cupidatat non <br> proident, sunt in culpa qui officia</small>
                <div class="footer_mail">
                    <form method="POST">
                        <input type="email" placeholder="Email Adress">
                        <input type="submit" value="SUBSCRIBE">
                    </form>
                </div>
                
            </div>
        </div>

    </footer>

</body>
</html>
