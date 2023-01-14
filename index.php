<?php
    session_start();
    require_once "Routing.php";

    $path = trim($_SERVER['REQUEST_URI'], "/");
    $path = parse_url($path, PHP_URL_PATH);

    Router::get("login", "SecurityController");
    Router::get("logout", "SecurityController");
    Router::get("register", "SecurityController");

    Router::get("mainPage", "DefaultControler");
    Router::get("addRecipePage", "DefaultControler");
    Router::get("registerPage", "DefaultControler");
    Router::get("regulamin", "DefaultControler");

    Router::get("addRecipe", "RecipeController");
    Router::get("search", "RecipeController");
    Router::get("like", "RecipeController");
    Router::get("dislike", "RecipeController");
    Router::get("display", "RecipeController");

Router::run($path);
?>