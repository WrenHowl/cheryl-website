function dropDown() {
    let selectDropDown = document.getElementById('dropDown');

    if (selectDropDown.style.display === 'none') {
        selectDropDown.style.display = 'flex';
    } else {
        selectDropDown.style.display = 'none';
    }
}

function logSign(input) {
    console.log(input);
}