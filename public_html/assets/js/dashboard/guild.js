const nav = document.querySelector('nav');
const body = document.querySelector('body');
const setting = document.querySelectorAll('.setting.option button');
const language = document.querySelectorAll('.setting.option label');
const saveButton = document.querySelector('.setting.save');

nav.addEventListener('click', navToggles)

for (let settings of setting) {
    settings.addEventListener('click', toggleOption);
}

for (let languages of language) {
    languages.addEventListener('click', unSelect);
}

function navToggles(event) {
    if (!event.target.id) return;

    const allSettings = document.querySelectorAll('.setting.type');
    const defaultMessage = document.querySelector('.default-message')
    const input = document.querySelectorAll(`#${event.target.id} input`);

    if (saveButton.style.display === 'none') {
        for (let setting of allSettings) {
            if (setting.id === event.target.id) {
                setting.style.display = 'flex';
                event.target.style.borderBottom = '1px solid white';
                event.target.style.color = 'white';
            } else {
                setting.style.display = 'none';
                const allNav = document.querySelectorAll('nav button');

                for (let navButton of allNav) {
                    if (navButton.id === event.target.id) continue;

                    navButton.style.borderBottom = '1px solid rgba(255, 255, 255, 0.05)';
                    navButton.style.color = 'rgba(255, 255, 255, 0.05)';
                }
            }
        }

        for (let inputs of input) {
            inputs.addEventListener('input', inputClicked);

            function inputClicked() {
                saveButton.style.display = 'flex';
            }
        }
    } else {
        alert('Please save your settings before switching setting.')
    }

    defaultMessage.style.display = 'none';
}

function toggleOption(event) {
    const button = event.target.closest('.setting.option button');
    const option = button.parentElement.children[1];

    // Check if the toggle is off
    if (option.style.visibility === 'hidden') {
        // Rotate the arrow
        button.children[0].style.transform = 'rotate(180deg)';
        option.style.pointerEvents = 'auto';
        option.style.position = 'static';
        return option.style.visibility = 'visible';
    } else {
        // Rotate the arrow
        button.children[0].style.transform = 'rotate(0deg)';
        option.style.pointerEvents = 'none';
        option.style.position = 'absolute';
        return option.style.visibility = 'hidden';
    }
}

function unSelect(event) {
    for (let languages of language) {
        if (languages.htmlFor === event.target.id && event.target.children) continue;
        languages.children[0].checked = false;
    }
}