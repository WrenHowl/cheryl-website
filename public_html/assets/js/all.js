const loggedIn = document.querySelector('.navbar.account');

if (loggedIn) loggedIn.addEventListener('click', accountDropDown);

function accountDropDown() {
    const message = loggedIn.children[0].style;
    const arrow = loggedIn.children[1].style;
    const dropDown = loggedIn.children[2].style;

    if (dropDown.display == 'none') {
        dropDown.display = 'flex';
        loggedIn.style.background = 'white';
        message.color = 'black';
        arrow.filter = 'invert(100%)';
        return arrow.transform = 'rotate(-90deg)';
    }

    dropDown.display = 'none';
    loggedIn.style.background = 'transparent';
    message.color = 'white';
    arrow.filter = 'unset';
    return arrow.transform = 'rotate(0deg)';
}