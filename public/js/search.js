const recipeContainer = document.querySelector('.recipes');
const button = document.getElementById("side-nav-button");
button.addEventListener('click', function (event)
{
    event.preventDefault();
    let searchInput =  document.querySelector('input[placeholder="SEARCH RECIPE"]');
    let checkBoxes = document.querySelectorAll('input[name="category-checkbox"]:checked');
    let categories = [];
    for(let i = 0; i < checkBoxes.length; i++)
        categories[i] = checkBoxes[i].value;

    var data;
    if(searchInput != null)
        data = {search: searchInput.value, categories: categories};
    else
        data = {search: '', categories: categories};


    fetch("/search", {
        method: "POST",
        headers: {
            "Content-type" : "application/json"
        },
        body: JSON.stringify(data)
    }).then(function (response)
    {
        return response.json();
    }).then(function (recipes)
    {
        recipeContainer.innerHTML = "";
        loadRecipes(recipes, categories);
    })
})

function loadRecipes(recipes, categories)
{
    recipes.forEach( (recipe) => {
        createRecipe(recipe, categories);
    })
}

function createRecipe(recipe, categories)
{
    const template = document.querySelector('#recipe-template');
    const clone = template.content.cloneNode(true);

    const link = clone.querySelector('a');
    link.href = "display/" + recipe.id;

    const recipeDiv = clone.querySelector('.recipe-1');
    recipeDiv.id = recipe.id;

    const image = clone.querySelector("img");
    image.src = "public/img/" + recipe.photo_path;

    const title = clone.querySelector("h3");
    title.innerHTML = recipe.title;

    const likes = clone.querySelector("#likes");
    likes.innerHTML = recipe.likes;

    const time = clone.querySelector("#prep_time");
    time.innerHTML = "<i class=\"fa-solid fa-bell\"></i>" +  recipe.prep_time;

    const ingr_num = clone.querySelector("#ingr_num");
    ingr_num.innerHTML = "<i class=\"fa-solid fa-briefcase\"></i>" + recipe.ingr_num;

    const fav = clone.querySelector("#add-fav");
    if(recipe.fav === true)
        fav.innerHTML = "<i class=\"fa-solid fa-heart\"></i>";
    else
        fav.innerHTML = "<i class=\"fa-regular fa-heart\"></i>";

    fav.querySelector("i").addEventListener('click', likeAction);

    if(categories.includes('fav'))
    {
        if(recipe.fav === true)
            recipeContainer.appendChild(clone);
    }
    else
    {
        recipeContainer.appendChild(clone);
        if(recipe.fav === false)
            recipeContainer.appendChild(clone);
    }
}