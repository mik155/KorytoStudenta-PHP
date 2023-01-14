dislikeButton = document.getElementsByClassName("fa-solid fa-heart");
likeButton = document.getElementsByClassName("fa-regular fa-heart");

function likeAction(event)
{
    event.preventDefault();
    const clicked = this;
    const container = clicked.parentElement.parentElement;
    const id = container.getAttribute("id");


    const fav = container.querySelector("#add-fav");
    const heartType = fav.querySelector("i");
    let method = "";
    if(heartType.className === "fa-regular fa-heart")
        method = "/like/";
    else
        method = "/dislike/";

    fetch(method + id, {
        method: "POST",
        headers: {
            "Content-type" : "application/json"
        }
    }).then(function (response)
    {
        return response.json();
    }).then(function (response)
    {
        changeHeartType(fav);
        const likes = container.querySelector("#likes");
        likes.innerHTML = response[0]['likes'];
    });
}

function changeHeartType(fav)
{
    const heart = fav.querySelector("i");
    if(heart.className === "fa-solid fa-heart")
        heart.className = "fa-regular fa-heart"
    else
        heart.className = "fa-solid fa-heart"
}

for(let i = 0; i < dislikeButton.length; i++)
    dislikeButton[i].addEventListener('click', likeAction);

for(let i = 0; i < likeButton.length; i++)
    likeButton[i].addEventListener('click', likeAction);