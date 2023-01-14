<?php
    require_once 'AppControler.php';
    require_once __DIR__.'/../repository/RecipeRepository.php';
    class DefaultControler extends AppControler
    {

        public function addRecipePage()
        {
            $recipeRepo = new RecipeRepository();
            $categories = $recipeRepo->getCategories();
            $this->render("addRecipe", ['recipeCategories' => $categories]);
        }
        public function login()
        {
            $this->render("login");
        }
        public function registerPage()
        {
            $this->render("register");
        }

        public function regulamin()
        {
            $this->render("regulamin");
        }
        public function mainPage()
        {
            $recipeRepo = new RecipeRepository();
            $categories = $recipeRepo->getCategories();

            $recipeRepo = new RecipeRepository();
            $recipes = $recipeRepo->getRecipes();

            $this->render("mainPage", ['recipes' => $recipes, 'recipeCategories' => $categories]);
        }

    }
?>
