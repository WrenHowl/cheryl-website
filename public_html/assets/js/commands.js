function showNewCommands(button) {
    let noCommandSelected = document.getElementById('noCommandSelected');
    displayType = noCommandSelected.style.display = 'none';

    doc1 = document.getElementById('staff_only_cmd');
    doc2 = document.getElementById('mod_cmd');
    doc3 = document.getElementById('fun_cmd');
    doc4 = document.getElementById('util_cmd');

    if (!doc1 == null) {
        doc1.style.display = 'none';
    }

    doc2.style.display = 'none';
    doc3.style.display = 'none';
    doc4.style.display = 'none';

    switch (button) {
        case ('button_staff'):
            doc1.style.width = '900px';
            doc1.style.margin = '0 auto';
            doc1.style.display = 'flex';
            break;
        case ('button_mod'):
            doc2.style.width = '900px';
            doc2.style.margin = '0 auto';
            doc2.style.display = 'flex';
            break;
        case ('button_fun'):
            doc4.style.width = '900px';
            doc4.style.margin = '0 auto';
            doc3.style.display = 'flex';
            break;
        case ('button_util'):
            doc4.style.width = '900px';
            doc4.style.margin = '0 auto';
            doc4.style.display = 'flex';
            break;
    };

    return displayType;
};