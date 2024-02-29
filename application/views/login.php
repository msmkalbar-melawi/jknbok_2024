<!DOCTYPE html>
<html lang="en">

<head>
    <title>LOGIN SIMAKDA</title>
    <!-- Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="Description" content="simakda 2022 Kabupaten Melawi">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <script>
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <style>
        .alert {
            padding: 20px;

            color: white;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }

        body {
            background-color: #ffffff;
        }
    </style>


    <!-- //Meta-Tags -->

    <!-- css files -->
    <link href="<?php echo base_url(); ?>assets/login/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="<?php echo base_url(); ?>assets/login/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!-- //css files -->

    <!-- google fonts -->
    <link href="//fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- //google fonts -->

</head>


<!-- design by wasis wibowo (Infastpedia.net) -->

<body>
    <img class="wave" src="<?php echo base_url(); ?>assets/login/img/wave.png">
    <div class="container">
        <div class="img">
            <img src="<?php echo base_url(); ?>assets/login/img/logoLogin.png">
        </div>
        <div class="login-content">
            <form action="<?php echo base_url(); ?>index.php/welcome/login" method="post">
                <img src="<?php echo base_url(); ?>assets/login/img/image.png">
                <h3>PEMERINTAH KABUPATEN MELAWI</h3>
                <h2>JKN BOK 2024</h2>
                <p>Jaminan Kesehatan Nasional dan Bantuan Operasional Kesehatan</p>
                <?php echo isset($pesan) ?
                    "<div class='alert'><font color='red'>" . $pesan . "</font></div>" : ""; ?>
                <br />
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Username</h5>
                        <input class="input" type="text" name="username" id="username" required="">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input class="input" type="password" name="password" id="password" required="">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="div">
                        <input class="input" type="text" name="pcthang" id="pcthang" value="2024" readonly="true">


                    </div>
                </div>
                <!-- <a href="#">Forgot Password?</a> -->
                <input type="submit" class="btn" value="Login">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/login/js/main.js"></script>
</body>



<!-- <p class="account">By clicking login, you agree to our <a href="#">Terms & Conditions!</a></p>
                <p class="account1">Dont have an account? <a href="#">Register here</a></p> -->

</div>
</div>
<!-- //main content -->
</div>
<!-- footer -->

<!-- footer -->
</div>

</body>

</html>