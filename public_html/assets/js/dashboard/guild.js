const nav = document.querySelector('nav')

nav.addEventListener('click', toggleSetting)

function toggleSetting(event) {
    if (!event.target.id) return;

    const setting = document.querySelector('.all-settings');
    document.querySelector('.default-message').style.display = 'none';

    for (i = 0; i < setting.children.length - 1; i++) {
        if (setting.children[i].id === event.target.id) {
            setting.children[i].style.display = 'flex';
            document.querySelector('.save-button-zone').style.display = 'block';
            event.target.style.borderBottom = '1px solid white';
            event.target.style.color = 'white';
            continue;
        }

        setting.children[i].style.display = 'none';
        nav.children[i].style.borderBottom = '1px solid rgba(255, 255, 255, 0.05)';
        nav.children[i].style.color = 'rgba(255, 255, 255, 0.05)';
    }
}

const setting = document.querySelectorAll('.setting-option button');

for (i = 0; i < setting.length; i++) {
    setting[i].addEventListener('click', toggleOption);
}

function toggleOption(event) {
    const button = event.target.closest('.setting-option button');
    const option = button.parentElement.children[1];
    if (option.style.display === 'none') {
        button.children[0].style.transform = 'rotate(180deg)';
        return option.style.display = 'flex'
    }

    button.children[0].style.transform = 'rotate(0deg)';
    return option.style.display = 'none'
}

const languageOption = document.querySelectorAll('.setting-option label');

for (i = 0; i < languageOption.length; i++) {
    languageOption[i].addEventListener('click', unSelect);
}

function unSelect(event) {
    console.log(event.target.checked)

    for (i = 0; i < languageOption.length; i++) {
        if (languageOption[i].htmlFor === event.target.id && event.target.children) continue;
        languageOption[i].children[0].checked = false;
    }
}