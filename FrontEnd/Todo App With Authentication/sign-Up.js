let username = document.getElementById('name');
let password = document.getElementById('password');

let regButton = document.getElementById('register');
let logButton = document.getElementById('log-in');

let error = document.getElementById('error-msg');

class Users {
    constructor(name, password){
        this.name = name;
        this.password = password;
    }

    addUser(name, password){
        let users = JSON.parse(localStorage.getItem('users')) || [];
        users.push({ name: name, password: password});
        localStorage.setItem('users', JSON.stringify(users));
        const userData = {
            username: name,
            notes: [] // Empty array to store user notes with status
        };
        localStorage.setItem(name, JSON.stringify(userData));
    }

    checkUser(name, password){
        let users = JSON.parse(localStorage.getItem('users')) || [];
        return users.some(user => user.name === name && user.password === password);
    }

    userExists(name){
        let users = JSON.parse(localStorage.getItem('users')) || [];
        return users.some(user => user.name === name);
    }
    getUserData(name) {
        return JSON.parse(localStorage.getItem(name)) || null;
    }

    updateUserData(name, data) {
        localStorage.setItem(name, JSON.stringify(data));
    }

}

regButton.addEventListener('click', e => {
    e.preventDefault();
    let usernameValue = username.value.trim();
    let passwordValue = password.value.trim();
    if(usernameValue === '' || passwordValue === ''){
        error.innerText = "Please Enter Both username and password.";
        errorStyle();
        return;
    }
    console.log(passwordValue.length);
    if (passwordValue.length < 8) {
        error.innerText = "Password must be at least 8 characters long.";
        error.style.background = 'red';
        setTimeout(() => {
            error.innerText = '';
            error.style.background = '#6a11cb';
        }, 3000);
        return;
    }
    const user = new Users(usernameValue, passwordValue);
    if(user.userExists(usernameValue)){
        error.innerText = "You are already registered. Please log in.";
        errorStyle();
    }else{
        user.addUser(usernameValue, passwordValue);
        error.innerText = 'Registeration Successful!';
        error.style.background = 'green';
        setTimeout(() => {
            error.innerText = '';
            error.style.background = '#6a11cb';
        }, 3000);
        window.location.href = './home/home.html';
        sessionStorage.setItem('loggedInUser', usernameValue);
    }

});

logButton.addEventListener('click', e => {
    e.preventDefault();
    let usernameValue = username.value.trim();
    let passwordValue = password.value.trim();
    
    if (usernameValue === '' || passwordValue === '') {
        error.innerText = "Please enter both username and password.";
        error.style.background = 'red';
        setTimeout(() => {
            error.innerText = '';
            error.style.background = '#6a11cb';
        }, 3000);
        return;
    }

    if (passwordValue.length < 8) {
        error.innerText = "Password must be at least 8 characters long.";
        error.style.background = 'red';
        setTimeout(() => {
            error.innerText = '';
            error.style.background = '#6a11cb';
        }, 3000);
        return;
    }
    
    const user = new Users(usernameValue, passwordValue);
    if(user.checkUser(usernameValue, passwordValue)){
        window.location.href = './home/home.html';
        sessionStorage.setItem('loggedInUser', usernameValue);
    }else{
        error.innerText = "Invalid Credentials. Please try again";
        errorStyle();
    }
});



function errorStyle(){
    error.style.background = 'red';
        setTimeout(() => {
            error.innerText = '';
            error.style.background = '#6a11cb';
        }, 3000);
}