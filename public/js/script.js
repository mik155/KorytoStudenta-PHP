const form = document.querySelector("form");
const loginInput = form.querySelector('input[name="login"]');
const emailInput = form.querySelector('input[name="email"]');
const password = form.querySelector('input[name="password"]');
const confirmedPasswordInput = form.querySelector('input[name="confirm-password"]');


function isLoginCorrect(login)
{
    console.log(login.length < 30 );
    return login.length < 30 && /^[A-Za-z0-9]+$/.test(login);
}
function isEmail(email) {
    return /^\S+@\S+\.\S+$/.test(email);
}

function arePasswordsSame(password, confirmedPassword) {
    return password === confirmedPassword;
}

function markValidation(element, condition) {
    !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
}

function validateEmail() {
    setTimeout(function () {
            markValidation(emailInput, isEmail(emailInput.value));
        },
        1000
    );
}

function validateLogin() {
    setTimeout(function () {
            markValidation(loginInput, isLoginCorrect(loginInput.value));
        },
        1000
    );
}

function validatePassword() {
    setTimeout(function () {
            const condition = arePasswordsSame(
                password.value,
                confirmedPasswordInput.value
            );
            markValidation(password, condition);
            markValidation(confirmedPasswordInput, condition);
        },
        1000
    );
}

emailInput.addEventListener('keyup', validateEmail);
confirmedPasswordInput.addEventListener('keyup', validatePassword);
loginInput.addEventListener('keyup', validateLogin);