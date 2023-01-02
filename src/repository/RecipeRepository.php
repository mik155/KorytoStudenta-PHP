<?php
session_start();

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Recipe.php';


class RecipeRepository extends Repository
{
    public function getRecipes()
    {
        $recipes = $this->getAllRecipes();
        if (!$recipes)
            return null;

        if ($_SESSION['user']) {
            $favRecId = $this->getUserFavRecipesId();
            if (!$favRecId)
                return $recipes;

            foreach ($favRecId as $id)
                $recipes[$id]['fav'] = 1;
        }

        return $recipes;
    }

    private function getAllRecipes()
    {
        $query = 'SELECT R.id, C.name, R.name, R.description, R.ingridients, R.prep_time, R.ingr_num, R.likes, R.creator_id, R.photo_path FROM "Recipe" R ';
        $query .= 'JOIN "RecipeCategory" C ON R.category_id = C.id';
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();

        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
            $recipe = $this->getRecipeFromQuery($row);
            $recipes[$recipe->getId()] = $recipe->toArray();
        }

        return $recipes;
    }

    private function getUserFavRecipesId()
    {
        $userRepo = new UserRepository();
        $user = $userRepo->getUser($_SESSION['user']);

        $stmt = $this->database->connect()->prepare('SELECT recipe_id FROM "FAV_RECIPES" WHERE user_id = :id');
        $stmt->bindParam(':id', $user->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $array = [];
        while ($row = $stmt->fetch()) {
            array_push($array, $row['recipe_id']);
        }

        return $array;
    }

    private function getRecipeFromQuery($row)
    {
        return new Recipe(
            $row['id'],
            $row['name'][0],
            $row['name'][1],
            $row['description'],
            $row['ingridients'],
            $row['prep_time'],
            $row['ingr_num'],
            $row['likes'],
            $row['creator_id'],
        $row['photo_path']);
    }

    public function getCategories()
    {
        $stmt = $this->database->connect()->prepare('SELECT name FROM "RecipeCategory"');
        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch()) {
            array_push($array, $row['name']);
        }
        return $array;
    }

    public function addRecipe(Recipe $recipe)
    {
        $query = 'INSERT INTO "Recipe"(category_id, name, description, ingridients, prep_time, ingr_num, likes, creator_id, photo_path) ';
        $query .= 'VALUES (?, ?, ?, ?, ? ,? ,? ,?, ?)';
        $stmt = $this->database->connect()->prepare($query);

        $userRepo = new UserRepository();
        $user = $userRepo->getUser($_SESSION['user']);

        $stmt->execute(
            [$this->getCategoryId($recipe->getCategory()),
            $recipe->getName(),
            $recipe->getDescription(),
            $recipe->getIngridients(),
            $recipe->getPrepTime(),
            $recipe->getIngrNum(),
            $recipe->getLikes(),
            $user->getId(),
            $recipe->getPhotoPath()]
        );
    }

    public function getCategoryId($name)
    {
        $stmt = $this->database->connect()->prepare('SELECT id FROM "RecipeCategory" WHERE name = :name');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }
}