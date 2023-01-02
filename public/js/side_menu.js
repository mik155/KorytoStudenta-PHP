function vw(percent) {
    var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    return (percent * w) / 100;
}

function closeNav()
{
    var array = document.getElementsByClassName("sidenav");
    for(var i = 0; i < array.length; i++)
    {
        array[i].style.width = "0";
    }

    var array = document.getElementsByClassName("side-menu-opt");
    for(var i = 0; i < array.length; i++)
    {
        array[i].style.display = "none";
    }

    document.getElementById("side-menu-form").style.display = "none";
}

function openNav()
{
    var array = document.getElementsByClassName("sidenav");
    for(var i = 0; i < array.length; i++)
    {
        array[i].style.width = "30vw";

    }

    var array = document.getElementsByClassName("side-menu-opt");
    for(var i = 0; i < array.length; i++)
    {
        array[i].style.display = "flex";
    }

    document.getElementById("side-menu-form").style.display = "flex";

}