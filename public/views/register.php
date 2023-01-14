<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>REGISTER PAGE</title>
        <script src="public/js/script.js" type="text/javascript" defer></script>
        <script src="https://kit.fontawesome.com/94db409358.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="public/css/login.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class = "container">
            <div class="logo">
                <i class="fa-solid fa-bowl-food"></i>
                Register
            </div>

            <?php
            if(isset($messages))
            {
                foreach ($messages as $message)
                    echo $message;
            }
            ?>

            <div class="login-container">
                <form action = "register" method="POST">
                    <input name="login" type="text" placeholder="LOGIN">
                    <input name="password" type="password" placeholder="PASSWORD"> 
                    <input name="confirm-password" type="password" placeholder="CONFIRM PASSWORD">
                    <input name="email" type="text" placeholder="EMAIL">

                    <button type="submit">CREATE ACCOUNT</button>
                </form>
            </div>


        </div>
    </body>
</html>