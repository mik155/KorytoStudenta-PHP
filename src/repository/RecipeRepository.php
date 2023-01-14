<?php
session_start();

require_once 'Repository.php';
require_once 'UserRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Recipe.php';


class RecipeRepository extends Repository
{

    public function getRecipeById($id)
    {
        $stmt = $this->database->connect()->prepare(
            'SELECT * FROM "Recipe" WHERE id = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->getSingleRecipeFromQuery($stmt->fetch(PDO::FETCH_ASSOC));
    }
    public function getRecipeByNameAndCat($name, $categories)
    {
        if($name !== '')
            $name = '%'.strtoupper($name).'%';
        $index = 1;
        if (isset($_SESSION['user']) && in_array("favourites", $categories))
            $query = 'SELECT *, R.name  title, R.id id, (? in (SELECT user_id FROM "FAV_RECIPES" FR WHERE R.id = FR.recipe_id)) fav FROM "Recipe" R JOIN "RecipeCategory" RC ON R.category_id = RC.id ';
        else if(isset($_SESSION['user']))
            $query = 'SELECT *, R.name title, R.id id, (? in (SELECT user_id FROM "FAV_RECIPES" FR WHERE R.id = FR.recipe_id)) fav FROM "Recipe" R JOIN "RecipeCategory" RC ON R.category_id = RC.id ';
        else
            $query = 'SELECT *, R.name title, R.id id FROM "Recipe" R JOIN "RecipeCategory" RC ON R.category_id = RC.id ';

        if($name !== '')
            $query .= 'WHERE UPPER(R.name) LIKE ? AND UPPER(RC.name) IN (\'';
        else
            $query .= 'WHERE UPPER(RC.name) IN (\'';

        $query .=  implode("','",  $categories)
            . "')";

        $stmt = $this->database->connect()->prepare($query);

        if(isset($_SESSION['user']))
        {
            $userRepo = new UserRepository();
            $user = $userRepo->getUser($_SESSION['user']);
            $stmt->bindParam($index, $user->getId(), PDO::PARAM_INT);
            $index += 1;
        }

        if($name !== '')
            $stmt->bindParam($index, $name, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
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

    public function like($recipe_id, $user_id)
    {
        $stmt = $this->database->connect()->prepare(
            'CALL likeRecipe(:recipe_id, :user_id)'
        );
        $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->database->connect()->prepare(
            'SELECT COUNT(*) likes FROM "FAV_RECIPES" WHERE recipe_id = :recipe_id'
        );
        $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $stmt->execute();
        $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function dislike($recipe_id, $user_id)
    {
        $stmt = $this->database->connect()->prepare(
            'CALL dislikeRecipe(:recipe_id, :user_id)'
        );
        $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->database->connect()->prepare(
            'SELECT COUNT(*) likes FROM "FAV_RECIPES" WHERE recipe_id = :recipe_id'
        );
        $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $stmt->execute();
        $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
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

    private function getSingleRecipeFromQuery($row)
    {
        return new Recipe(
            $row['id'],
            '',
            $row['name'],
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