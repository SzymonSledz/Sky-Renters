const form = document.querySelector("form");
const emailInput = form.querySelector('input[name="email"]');
const confirmPasswordInput = form.querySelector('input[name="confirmPassword"]');

function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

function arePasswordsSame(password, confirmedPassword) {
    return password === confirmedPassword;
}

function markValidation(element, condition) {
    console.log(condition);
    !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
    console.log(condition);
}

function validateEmail() {
    setTimeout(function () {
            markValidation(emailInput, isEmail(emailInput.value));
        },
        1000
    );
}

function validatePassword() {
    setTimeout(function () {
            const condition = arePasswordsSame(
                confirmPasswordInput.previousElementSibling.value,
                confirmPasswordInput.value
            );
            markValidation(confirmPasswordInput, condition);
        },
        1000
    );
}

emailInput.addEventListener('keyup', validateEmail);
confirmPasswordInput.addEventListener('keyup', validatePassword);