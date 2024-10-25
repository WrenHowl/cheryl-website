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

const doc = document.getElementById('page');
const alertDoc = document.getElementById('alert');

function scrollAlert() {
    if (doc.scrollTop > 0) {
        alertDoc.style.maxHeight = '0px'
        return;
    }

    alertDoc.style.maxHeight = 'fit-content'
}