<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>다함께 푸쉬업</title>
        <!-- 웹사이트 이름 -->

        <link rel="stylesheet" type="text/css" href="css/login.css">
        <!-- 로그인 화면 css -->
    </head>
    <body>
        <div class="login-box">
            <h2>Login</h2>
            <form action="php/confirmLogIn.php" method="post">
                <div class="user-box">
                    <input type="text" name="id" autocapitalize="off">
                    <label>Username</label>
                </div>
                <div class="user-box">
                    <input type="password" name="pwd" autocapitalize="off">
                    <label>Password</label>
                </div>
                <button type="submit" name="signInBtn">
                    SignIn
                </button>
            </form>
        </div>
    </body>

</html>