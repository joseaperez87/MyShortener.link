const shortenForm = document.getElementById('shortener-form');
const registrationForm = document.getElementById('registration-form');
const confirmButton = document.getElementById('confirm-btn');
const loginForm = document.getElementById('login-form');

shortenForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const urlInput = document.getElementById('url-text');
    const url = urlInput.value;
    if (url == "" || !url.match(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi)) {
        alert('Указанный URL-адрес неверен');
        return;
    }
    shortenForm.setAttribute('disabled', 'disabled');
    fetch('api/?a=savelink', {
        method: 'POST',
        headers: {
            "Content-Type": 'application/json'
        },
        body: JSON.stringify({url: url})
    }).then(response => response.json())
        .then(data => {
            if (data.res) {
                alert("Url-адрес успешно сокращен");
                if (user_id != "") {
                    window.location = ''
                }
                urlInput.value = "";
            } else {
                alert(data.message);
            }
            shortenForm.removeAttribute('disabled');
        }).catch(error => {
        console.log(error)
        shortenForm.removeAttribute('disabled');
    })
});

registrationForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    if (nameInput.value.trim() == "") {
        alert('Имя не может быть пустым');
        return;
    }
    if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(emailInput.value)) {
        alert('Неверный адрес электронной почты');
        return;
    }
    if (passwordInput.value == "") {
        alert('Пароль не может быть пустым');
        return;
    } else if (passwordInput.value != confirmPasswordInput.value) {
        alert('Пароли не совпадают');
        return;
    }

    registrationForm.querySelector('.register-btn').setAttribute('disabled', 'disabled');
    const formData = {
        name: nameInput.value,
        password: passwordInput.value,
        confirm_password: confirmPasswordInput.value,
        email: emailInput.value
    }
    fetch('api/?a=register', {
        method: 'POST',
        headers: {
            "Content-Type": 'application/json'
        },
        body: JSON.stringify(formData)
    }).then(response => response.json())
        .then(data => {
            if (data.res) {
                document.querySelector('.form-container').style.display = "none";
                document.querySelector('.registration-success').style.display = "block";
                document.querySelector('.modal-footer').style.display = "none";
            } else {
                alert(data.message);
            }
            registrationForm.querySelector('.register-btn').removeAttribute('disabled')
        }).catch(error => {
        console.log(error);
        registrationForm.querySelector('.register-btn').removeAttribute('disabled')
    })
})

confirmButton.addEventListener('click', function () {
    const codeInput = document.getElementById('confirm-code');
    if (codeInput.value.trim() == "" || codeInput.value.length != 6) {
        alert('Код должен содержать 6 символов');
        return;
    }
    document.querySelectorAll('.code-valid,.code-invalid').forEach(function(div){
        div.style.display = 'none'
    })

    confirmButton.setAttribute('disabled', 'disabled');
    fetch('api/?a=confirmcode', {
        method: 'POST',
        headers: {
            "Content-Type": 'application/json'
        },
        body: JSON.stringify({code: codeInput.value.trim()})
    }).then(response => response.json())
        .then(data => {
            if (data.res) {
                document.querySelector('.code-valid').style.display = 'block';
                setTimeout(function () {
                    window.location = '';
                }, 2000);
            } else {
                document.querySelector('.code-invalid').style.display = 'block';
            }
            confirmButton.removeAttribute('disabled')
        }).catch(error => {
        console.log(error);
        confirmButton.removeAttribute('disabled')
    })
})

function showConfirmationCode() {
    document.querySelector('.form-container').style.display = "none";
    document.querySelector('.registration-success').style.display = "block";
    document.querySelector('.modal-footer').style.display = "none";
}

function showRegistrationForm() {
    document.querySelector('.form-container').style.display = "block";
    document.querySelector('.registration-success').style.display = "none";
    document.querySelector('.modal-footer').style.display = "block";
}

loginForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const loginEmailInput = document.getElementById('login-email');
    const loginPasswordInput = document.getElementById('login-password');

    if (loginEmailInput.value.trim() == "" || !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(loginEmailInput.value)) {
        alert('Адрес электронной почты недействителен');
        return;
    }
    if (loginPasswordInput.value == "") {
        alert('Пароль не может быть пустым');
        return;
    }

    loginForm.querySelector('.login-btn').setAttribute('disabled', 'disabled');
    fetch('api/?a=login', {
        method: 'POST',
        headers: {
            "Content-Type": 'application/json'
        },
        body: JSON.stringify({email: loginEmailInput.value.trim(), password: loginPasswordInput.value})
    }).then(response => response.json())
        .then(data => {
            if (data.res) {
                window.location = '';
            } else {
                alert(data.message)
            }
            loginForm.querySelector('.login-btn').removeAttribute('disabled')
        }).catch(error => {
        console.log(error);
        loginForm.querySelector('.login-btn').removeAttribute('disabled')
    })
})

function copy (text) {
    navigator.clipboard.writeText(text);
    alert('Ссылка скопированна')
}