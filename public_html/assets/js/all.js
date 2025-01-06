function accountDropDown() {
    const accountIdle = document.getElementById('navbar-account-idle');
    const dropMenu = document.getElementById('navbar-dropdown');
    const arrow = document.getElementById('navbar-arrow');

    dropMenu.style.display == 'none' ?
        dropMenu.style.display = 'flex' :
        dropMenu.style.display = 'none';

    if (accountIdle.style.background == 'white') {
        accountIdle.style.color = 'white';
        accountIdle.style.background = 'unset';
        arrow.style.filter = 'unset';
        arrow.style.transform = 'rotate(90deg)';
    } else {
        accountIdle.style.color = 'black';
        accountIdle.style.background = 'white';
        arrow.style.filter = 'invert(100%)';
        arrow.style.transform = 'rotate(0deg)';
    }
}