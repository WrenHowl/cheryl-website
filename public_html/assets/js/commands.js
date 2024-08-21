function showNewCommands(button) {
    let noCommandSelected = document.getElementById('noCommandSelected');
    displayType = noCommandSelected.style.display = 'none';

    doc1 = document.getElementById('staff_only_cmd').style;
    doc2 = document.getElementById('mod_cmd').style;
    doc3 = document.getElementById('fun_cmd').style;
    doc4 = document.getElementById('util_cmd').style;

    switch (button) {
        case ('button_staff'):
            doc2.display = 'none';
            doc3.display = 'none';
            doc4.display = 'none';

            doc1.width = '900px';
            doc1.margin = '0 auto';
            doc1.display = 'flex';
            break;
        case ('button_mod'):
            doc1.display = 'none';
            doc3.display = 'none';
            doc4.display = 'none';

            doc2.width = '900px';
            doc2.margin = '0 auto';
            doc2.display = 'flex';
            break;
        case ('button_fun'):
            doc1.display = 'none';
            doc2.display = 'none';
            doc4.display = 'none';

            doc4.width = '900px';
            doc4.margin = '0 auto';
            doc3.display = 'flex';
            break;
        case ('button_util'):
            doc1.display = 'none';
            doc2.display = 'none';
            doc3.display = 'none';

            doc4.width = '900px';
            doc4.margin = '0 auto';
            doc4.display = 'flex';
            break;
    };

    return displayType;
};