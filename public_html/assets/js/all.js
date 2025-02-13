const loggedIn = document.querySelector('.navbar.account');
const dropdown = document.querySelector('.navbar.dropdown');
const icon = document.querySelector('.navbar.account img');

if (loggedIn) icon.addEventListener('click', accountDropDown);

function accountDropDown() {
    dropdown.classList.toggle('show')
    loggedIn.classList.toggle('show')
    icon.classList.toggle('show')
}