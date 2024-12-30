const doc = document.getElementById('page');
const alertDoc = document.getElementById('alert');

function scrollAlert() {
    if (doc.scrollTop > 0) {
        alertDoc.style.maxHeight = '0px'
        return;
    };

    alertDoc.style.maxHeight = 'fit-content';
}