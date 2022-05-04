function validateLoginForm() {
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    let error = false;

    // Used regex from here https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
    const match = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (email.value.match(match) == null) {
        error = true;
        errorMsg('email', true, 'Please enter a valid email.');
    }
    else {
        errorMsg('email', false);
    }

    if (password.value.length < 1) {
        error = true;
        errorMsg('password', true, 'Please enter a password.');
    }
    else {
        errorMsg('password', false);
    }
    return !error;
}

function validateRegisterForm() {
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const email = document.getElementById('email');
    const address = document.getElementById('address');
    // const billingAddress = document.getElementById('billingAddress');
    const password = document.getElementById('password');
    const verifyPassword = document.getElementById('verifyPassword');

    let error = false;

    if (firstName.value.length < 1) {
        error = true;
        errorMsg('firstName', true, 'Please enter a first name');
    }
    else {
        errorMsg('firstName', false);
    }

    if (lastName.value.length < 1) {
        error = true;
        errorMsg('lastName', true, 'Please enter a last name');
    }
    else {
        errorMsg('lastName', false);
    }

    // Used regex from here https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
    const match = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (email.value.match(match) == null) {
        error = true;
        errorMsg('email', true, 'Please enter a valid email');
    }
    else {
        errorMsg('email', false);
    }

    if (address.value.length < 1) {
        error = true;
        errorMsg('address', true, 'Please enter a address');
    }
    else {
        errorMsg('address', false);
    }

    if (password.value.length < 1) {
        error = true;
        errorMsg('password', true, 'Please enter a password');
    }
    else if (password.value.length < 8) {
        error = true;
        errorMsg('password', true, 'Password needs to be atleast 8 characters');
    }
    else {
        errorMsg('password', false);
    }

    if (verifyPassword.value.length < 1) {
        error = true;
        errorMsg('verifyPassword', true, 'Please enter password to verify');
    }
    else if (verifyPassword.value !== password.value) {
        error = true;
        errorMsg('verifyPassword', true, 'Passwords don\'t not match!');
    }
    else {
        errorMsg('verifyPassword', false);
    }
    return !error;
}

function validateUserModalForm() {
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const email = document.getElementById('email');
    const address = document.getElementById('address');

    let error = false;

    if (firstName.value.length < 1) {
        error = true;
        errorMsg('firstName', true, 'Please enter a first name');
    }
    else {
        errorMsg('firstName', false);
    }

    if (lastName.value.length < 1) {
        error = true;
        errorMsg('lastName', true, 'Please enter a last name');
    }
    else {
        errorMsg('lastName', false);
    }

    // Used regex from here https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
    const match = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (email.value.match(match) == null) {
        error = true;
        errorMsg('email', true, 'Please enter a valid email');
    }
    else {
        errorMsg('email', false);
    }

    if (address.value.length < 1) {
        error = true;
        errorMsg('address', true, 'Please enter a address');
    }
    else {
        errorMsg('address', false);
    }

    return !error;
}

/**This function will only work properly if invalid-feedback div is right next to the input field */
function errorMsg(inputID, show=true, msg='') {
    if (show) {
        document.getElementById(inputID).classList.add('is-invalid');
        if (document.getElementById(inputID).nextElementSibling && document.getElementById(inputID).nextElementSibling.classList.contains('invalid-feedback')) {
            document.getElementById(inputID).nextElementSibling.innerHTML = msg;
        }
    }
    else {
        document.getElementById(inputID).classList.remove('is-invalid');
    }
}