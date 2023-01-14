<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>LOGIN PAGE</title>
        <script src="https://kit.fontawesome.com/94db409358.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="/public/css/index.css">
        <link rel="stylesheet" type="text/css" href="/public/css/regulamin.css">
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
                <section id="regulamin">
                    <h1>PRZEPISY</h1>
                            1. Przepisy powinny być jak najprostsze.</br>
                            2. Liczba skladnikow nie powinna przekraczac 15.</br>
                            3. Składniki powinny być łatwo dostępne.</br>
                </section>
            </main>
        
        </div>
 
    </body>
</html>
