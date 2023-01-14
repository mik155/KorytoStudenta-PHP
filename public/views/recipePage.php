<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>RECIPE</title>
    <script src="https://kit.fontawesome.com/94db409358.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="/public/css/recipePage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;900&display=swap" rel="stylesheet">

</head>

<body>
<div id="base-container">
    <header>
        <a href="mainPage">
            <i class="fa-solid fa-bowl-food"></i>
            Koryto Studenta
        </a>
    </header>
    <?php echo "<img src=\"/public/img/{$recipe['photo_path']}\">"?>
    <section>
        <h2 id="name_header"><?php echo $recipe['name'] ?></h2>

        <div class="recipe-stat">
            <div class="recipe-rate-specific">
                <i class="fa-solid fa-heart"></i>
                <div id ="likes">
                    <?php echo $recipe['likes'] ?>
                </div>
            </div>

            <div class="recipe-rate-specific" id="prep_time">
                <i class="fa-solid fa-bell"></i>
                <?php echo $recipe['prep_time'] ?>
            </div>

            <div class="recipe-rate-specific" id="ingr_num">
                <i class="fa-solid fa-briefcase"></i>
                <?php echo $recipe['ingr_num'] ?>
            </div>
        </div>

        <h2 id="ingr_header">SKŁADNIKI</h2>
        <div id="ingr">
            <?php echo str_replace("\n","<br/>",$recipe['ingridients']) ?>
        </div>
        <h2 id="desc_header">OPIS</h2>
        <div id="desc">
            <?php echo str_replace("\n","<br/>",$recipe['description']) ?>
        </div>

    </section>
</div>


</body>
</html>

