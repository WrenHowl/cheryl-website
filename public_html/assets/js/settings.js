const nav = document.querySelector('nav')
const saveButton = document.querySelector('.setting.save');

nav.addEventListener('click', navToggles)

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