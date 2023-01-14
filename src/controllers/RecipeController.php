<?php
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Recipe.php';
require_once __DIR__.'/../repository/RecipeRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class RecipeController extends AppControler
{
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/img/';
    private $messages = [];
    private $recipeRepository;
    private $userRepository;

    public function __construct()
    {
        $this->recipeRepository = new RecipeRepository();
        $this->userRepository = new UserRepository();
    }

    public function search()
    {
        $conntentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if($conntentType === "application/json")
        {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            header('Content-type: application/json');
            http_response_code(200);
            echo json_encode($this->recipeRepository->getRecipeByNameAndCat($decoded['search'], $decoded['categories']));
        }
    }
    public function addRecipe()
    {
        $newName = $this->getFreeFileName();
        if(is_uploaded_file($_FILES['file']['tmp_name']) && $this->validate($_FILES['file']))
        {
            move_uploaded_file
            (
                $_FILES['file']['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$_FILES['file']['name']
           );

            rename(                dirname(__DIR__).self::UPLOAD_DIRECTORY.$_FILES['file']['name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$newName.'.jpg');

            $recipe = new Recipe(-1, $_POST['category'], $_POST['title'], $_POST['desc'],$_POST['ingr'],
            $_POST['prep_time'], $_POST['ingr_num'], 0, -1, $newName . '.jpg');
            $this->recipeRepository->addRecipe($recipe);

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/mainPage");
        }


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/mainPage");
    }

    public function like($recipe_id)
    {
        if(isset($_SESSION['user']))
        {
            $user = $this->userRepository->getUser($_SESSION['user']);
            header('Content-type: application/json');
            http_response_code(200);

            echo json_encode($this->recipeRepository->like($recipe_id, $user->getId()));
        }
    }

    public function dislike($recipe_id)
    {
        if(isset($_SESSION['user']))
        {
            $user = $this->userRepository->getUser($_SESSION['user']);
            header('Content-type: application/json');
            http_response_code(200);
            echo json_encode($this->recipeRepository->dislike($recipe_id, $user->getId()));
        }
    }

    private function validate(array $file): bool
    {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->messages[] = 'File is too large.';
            return false;
        }

        if(!isset($file['type']) && !in_array($file['type'], self::SUPPORTED_TYPES))
        {
            $this->messages[] = 'File type not supported.';
            return false;
        }
        return true;
    }

    public function display($recipeId)
    {
        if($recipeId === "mainPage")
        {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/mainPage");
        }
        $recipe = $this->recipeRepository->getRecipeById($recipeId);
        $this->render("recipePage", ['recipe' => $recipe->toArray()]);
    }

    public function getFreeFileName()
    {
        $name = $this->getFileCount() + 1;
        return "$name";
    }

    private function getFileCount()
    {
        $iterator = new FilesystemIterator(dirname(__DIR__).self::UPLOAD_DIRECTORY);
        return iterator_count($iterator);
    }


}