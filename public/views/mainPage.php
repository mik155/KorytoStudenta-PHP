<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>LOGIN PAGE</title>
        <script src="https://kit.fontawesome.com/94db409358.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="/public/css/index.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;900&display=swap" rel="stylesheet">
        <script type="text/javascript" src="/public/js/side_menu.js" defer></script>
        <script type="text/javascript" src="/public/js/statistics.js" defer></script>
        <script type="text/javascript" src="/public/js/search.js" defer></script>
    </head>
    <body>
    <?php
    if(isset($messages))
    {
        foreach ($messages as $message)
            echo $message;
    }
    ?>
        <div class="base-container">
            <header>
                <a href="mainPage">
                    <i class="fa-solid fa-bowl-food"></i>
                    Koryto Studenta
                </a>
            </header>

          <div class="sidenav">
                <form action="search" id="side-menu-form" >
                    <button id="side-nav-button" onclick="closeNav()" type="button" >ZATWIERDŹ</button>
                    <?php  if(isset($_SESSION['user'])): ?>
                    <input type="text"  id="search" name="category-search" placeholder="SEARCH RECIPE">
                    <?php  endif ?>

                    <?php  foreach($recipeCategories as $cat): ?>
                    <div class="side-menu-opt">
                        <label for=<?php echo "\"$cat\""?>> <?php echo "$cat"?></label>
                         <input type="checkbox" id="input" name="category-checkbox" value=<?php echo "\"$cat\""?> checked="checked">
                    </div>
                    <?php  endforeach; ?>

                    <?php  if(isset($_SESSION['user'])): ?>
                        <div class="side-menu-opt">
                            <label for="fav">FAVOURITES</label>
                            <input type="checkbox" id="fav-input" name="category-checkbox" value="fav" checked="checked">
                        </div>
                    <?php  endif ?>

                </form>
            </div>

            <nav>
                <ul>
                    <li>
                        <a onclick="openNav()">
                            KATEGORIE
                        </a>
                    </li>
                    <li>
                        <a href="regulamin">
                            REGULAMIN
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user'])) : ?>
                    <li>
                        <a href="addRecipePage">
                            DODAJ PRZEPIS
                        </a>
                    </li>
                    <li>
                        <a href="logout">
                            WYLOGUJ
                        </a>
                    </li>
                    <?php else : ?>
                    <li>
                        <a href="login">
                            LOGOWANIE
                        </a>
                    </li>
                    <li>
                        <a href="registerPage">
                            REJESTRACJA
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <main>
              <!-- <div class="search-bar">

                    <form>
                        
                        <input placeholder="search recipe">
                    </form>
                </div>
            -->
                <section class="recipes">
                    <?php if($recipes): ?>
                    <?php foreach ($recipes as $recipe): ?>
                        <a href = <?php echo "\"display/{$recipe['id']}\"" ?>>
                    <div class="recipe-1"<?php echo "id=\"{$recipe['id']}\"" ?>>
                        <?php echo "<img src=\"public/img/{$recipe['photo_path']}\">"?>
                        <div class="recipe-stat">
                            <div class="recipe-title">
                                <h3><?php echo $recipe['name'] ?></h3>
                            </div>

                            <div class="recipe-rate">
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

                        </div>
                        <div id="add-fav">
                            <?php if($recipe['fav'] === 1): ?>
                            <i class="fa-solid fa-heart"></i>
                            <?php else : ?>
                            <i class="fa-regular fa-heart"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                        </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </section>
            </main>
        
        </div>
 
    </body>
</html>


<template id="recipe-template">
    <a href ="">
    <div class="recipe-1">
        <img id="image" src="">
        <div class="recipe-stat">
            <div class="recipe-title">
                <h3>name</h3>
            </div>

            <div class="recipe-rate">
                <div class="recipe-rate-specific">
                    <i class="fa-solid fa-heart"></i>
                    <div id ="likes">likes</div>
                </div>

                <div class="recipe-rate-specific" id="prep_time">
                    <i class="fa-solid fa-bell"></i>
                    prep_time
                </div>

                <div class="recipe-rate-specific" id="ingr_num">
                    <i class="fa-solid fa-briefcase"></i>
                     ingr_num
                </div>
            </div>

        </div>

        <div id="add-fav">
            <i class="fa-solid fa-heart"></i>
            <i class="fa-regular fa-heart"></i>
        </div>
    </div>
    </a>
    </template>