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
        <script type="text/javascript" src="/public/js/side_menu.js"></script>
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
                <i class="fa-solid fa-bowl-food"></i>
                Koryto Studenta
            </header>

          <div class="sidenav">
                <form action="login" id="side-menu-form" >
                    <button onclick="closeNav()">ZATWIERDŹ</button>
                    <div class="side-menu-opt">
                        <label for="pasta">PASTA</label>
                         <input type="checkbox" id="pasta-input" name="pasta" value="pasta" checked="checked">
                    </div>

                    <div class="side-menu-opt">
                        <label for="burger">BURGER</label>
                        <input type="checkbox" id="burger-input" name="burger" value="burger" checked="checked">

                    </div>

                    <div class="side-menu-opt">
                        <label for="kebab">KEBAB</label>
                        <input type="checkbox" id="kebab-input" name="kebab" value="kebab" checked="checked">

                    </div>
                    <div class="side-menu-opt">
                        <label for="georgian">GEORGIAN</label>
                        <input type="checkbox" id="georgian-input" name="georgian" value="georgian" checked="checked">

                    </div>
                   <div class="side-menu-opt">
                        <label for="pancakes">PANCAKES</label>
                        <input type="checkbox" id="pancakes-input" name="pancakes" value="pancakes" checked="checked">

                   </div>

                    <div class="side-menu-opt">
                        <label for="vegan">VEGAN</label>
                        <input type="checkbox" id="vegan-input" name="vegan" value="vegan" checked="checked">

                    </div>

                    <div class="side-menu-opt">
                        <label for="fav">FAVOURITES</label>
                        <input type="checkbox" id="fav-input" name="fav" value="fav" checked="checked">

                    </div>

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
                        REGULAMIN
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
                <section>
                    <?php if($recipes): ?>
                    <?php foreach ($recipes as $recipe): ?>
                    <div class='recipe-1' >
                        <?php echo "<img src=\"public/img/{$recipe['photo_path']}.jpg\">"?>
                        <div class="recipe-stat">
                            <div class="recipe-title">
                                <h3><?php echo $recipe['name'] ?></h3>
                            </div>

                            <div class="recipe-rate">
                                <div class="recipe-rate-specific">
                                    <i class="fa-solid fa-heart"></i>
                                    <?php echo $recipe['likes'] ?>
                                </div>

                                <div class="recipe-rate-specific">
                                    <i class="fa-solid fa-bell"></i>
                                    <?php echo $recipe['prep_time'] ?>
                                </div> 

                                <div class="recipe-rate-specific">
                                    <i class="fa-solid fa-briefcase"></i>
                                    <?php echo $recipe['ingr_num'] ?>
                                </div> 
                            </div>

                        </div>
                        <div class="add-fav">
                            <?php if($recipe['fav'] === 1): ?>
                            <i class="fa-solid fa-heart"></i>
                            <?php else : ?>
                            <i class="fa-regular fa-heart"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </section>
            </main>
        
        </div>
 
    </body>
</html>