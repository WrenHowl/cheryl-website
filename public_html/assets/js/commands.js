function showNewCommands(button) {
    const listCommand = document.getElementById('listCommand');
    const staff = document.getElementById('staff_only_cmd');
    const mod = document.getElementById('mod_cmd');
    const funDoc = document.getElementById('fun_cmd');
    const utilDoc = document.getElementById('util_cmd');

    listCommand.style.alignItems = "normal";
    listCommand.style.justifyContent = "normal";

    if (staff != null) {
        staff.style.display = 'none';
    }

    mod.style.display = 'none';
    funDoc.style.display = 'none';
    utilDoc.style.display = 'none';

    switch (button) {
        case ('button_staff'):
            staff.style.display = 'flex';
            break;
        case ('button_mod'):
            mod.style.display = 'flex';
            break;
        case ('button_fun'):
            funDoc.style.display = 'flex';
            break;
        case ('button_utilDoc'):
            utilDoc.style.display = 'flex';
            break;
    };

    return document.getElementById('noCommandSelected').style.display = 'none';
};