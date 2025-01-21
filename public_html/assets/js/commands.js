const nav = document.querySelector('nav')

nav.addEventListener('click', toggleSetting)

function toggleSetting(event) {
    console.log(event)
    if (!event.target.id) return;

    const setting = document.querySelector('.command-list');
    document.querySelector('.default-message').style.display = 'none';

    for (i = 0; i < setting.children.length; i++) {
        if (setting.children[i].id == event.target.id) {
            setting.children[i].style.display = 'flex';
            event.target.style.borderBottom = '1px solid white';
            event.target.style.color = 'white';
            continue;
        }

        setting.children[i].style.display = 'none';
        nav.children[i].style.borderBottom = '1px solid rgba(255, 255, 255, 0.05)';
        nav.children[i].style.color = 'rgba(255, 255, 255, 0.05)';
    }
}