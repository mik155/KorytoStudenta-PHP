<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>LOGIN PAGE</title>
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
                Koryto Studenta
            </div>

            <div class="message">
                <?php
                    if(isset($messages))
                    {
                        foreach ($messages as $message)
                            echo $message;
                    }
                ?>
            </div>

            <div class="login-container">
                <form action="login" method="POST">
                    <input name="login" type="text" placeholder="LOGIN">
                    <input name="password" type="password" placeholder="PASSWORD"> 
                    <button type="submit">LOGIN</button>
                    <a href="registerPage">
                        <button type="button">CREATE ACCOUNT</button>
                    </a>
                </form>
            </div>


        </div>
    </body>
</html>