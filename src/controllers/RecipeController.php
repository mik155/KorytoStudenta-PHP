<?php
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Recipe.php';
require_once __DIR__.'/../repository/RecipeRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class RecipeController extends AppControler
{
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/img/';
    private $messages = [];
    private $recipeRepository;
    private $userRepository;

    public function __construct()
    {
        $this->recipeRepository = new RecipeRepository();
        $this->userRepository = new UserRepository();
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
            $_POST['prep_time'], $_POST['ingr_num'], 0, -1, $newName);
            $this->recipeRepository->addRecipe($recipe);

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/mainPage");
        }


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/mainPage");
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