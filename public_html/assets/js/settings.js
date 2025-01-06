function toggleSetting(id) {
    const parent = document.getElementById(id).parentElement.parentElement.parentElement;
    const setting = parent.children[2];
    const message = parent.children[1];

    message.style.display = 'none';

    for (i = 0; i < setting.children.length; i++) {
        setting.children[i].id == id ?
            setting.children[i].style.display = 'flex' :
            setting.children[i].style.display = 'none';
    }
}