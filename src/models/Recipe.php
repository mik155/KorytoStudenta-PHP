<?php

class Recipe
{
    private $id;
    private $category;
    private $name;
    private $description;
    private $ingridients;
    private $prep_time;
    private $ingr_num;
    private $likes;
    private $creator_id;
    private $photo_path;

    public function __construct($id, $category, $name, $description, $ingridients, $prep_time, $ingr_num, $likes, $creator_id, $photo_path)
    {
        $this->id = $id;
        $this->category = $category;
        $this->name = $name;
        $this->description = $description;
        $this->ingridients = $ingridients;
        $this->prep_time = $prep_time;
        $this->ingr_num = $ingr_num;
        $this->likes = $likes;
        $this->creator_id = $creator_id;
        $this->photo_path = $photo_path;
    }

    public function toArray()
    {
        return ['id' => $this->id,'category' => $this->category, 'name' => $this->name, 'description' => $this->description,
            'ingridients' => $this->ingridients, 'prep_time' => $this->prep_time,
            'ingr_num' => $this->ingr_num, 'likes' => $this->likes, 'creator_id' => $this->creator_id, 'fav' => 0,
            'photo_path' => $this->photo_path];
    }

    public function __toString()
    {
        return "$this->id : $this->name : $this->description : $this->category";
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getIngridients()
    {
        return $this->ingridients;
    }

    public function setIngridients($ingridients)
    {
        $this->ingridients = $ingridients;
    }

    public function getPrepTime()
    {
        return $this->prep_time;
    }


    public function setPrepTime($prep_time)
    {
        $this->prep_time = $prep_time;
    }

    public function getIngrNum()
    {
        return $this->ingr_num;
    }

    public function setIngrNum($ingr_num)
    {
        $this->ingr_num = $ingr_num;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    public function getCreatorId()
    {
        return $this->creator_id;
    }

    public function setCreatorId($creator_id)
    {
        $this->creator_id = $creator_id;
    }

    public function getPhotoPath()
    {
        return $this->photo_path;
    }

    public function setPhotoPath($photo_path)
    {
        $this->photo_path = $photo_path;
    }
}