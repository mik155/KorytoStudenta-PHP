<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>ADD PROJECT</title>
        <script src="https://kit.fontawesome.com/94db409358.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="/public/css/index.css">
        <link rel="stylesheet" type="text/css" href="/public/css/addRecipe.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;900&display=swap" rel="stylesheet">
        <script type="text/javascript" src="/public/js/side_menu.js"></script>
    </head>
    <body>
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
                <section>
                    <div class="recipe-loader">
                        <form action="addRecipe" method="POST" ENCTYPE="multipart/form-data">

                            <label for="title" >NAZWA PRZEPISU:</label>
                            <input type="text" name="title" placeholder="nazwa do 30 znaków">

                            <label for="category">KATEGORIA:</label>
                            <select name="category" id="category">
                                <?php
                                    foreach ($recipeCategories as $cat)
                                        echo "<option value=\"$cat\">$cat</option>";

                                ?>
                            </select>

                            <label for="prep_time" >CZAS POTRZEBNY DO PRZYGOTOWANIA:</label>
                            <input type="number" name="prep_time" rows="1" placeholder="ilość minut"></input>

                            <label for="ingr_num" > ILOŚĆ SKŁADNIKÓW:</label>
                            <input type="number" name="ingr_num" rows="1"></input>

                            <label for="ingr" >SKŁADNIKI:</label>
                            <textarea name="ingr" rows="5" placeholder="wymień po -"></textarea>

                            <label for="desc" >LISTA KROKÓW:</label>
                            <textarea name="desc" rows="10" placeholder="opis przygotowania"></textarea>

                            <label for="file" >DODAJ ZDJĘCIE:</label>
                            <input type="file" name="file">

                            <div class="buttons">
                                <button type="submit" name="addRecipe">DODAJ</button>
                                <a href="mainPage.php">
                                    <button type="button" name="cancel">ANULUJ</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        
        </div>
 
    </body>
</html>