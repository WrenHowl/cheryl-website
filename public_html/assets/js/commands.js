function showNewCommands(button) {
    let listCommand = document.getElementById('listCommand');
    let doc1 = document.getElementById('staff_only_cmd');
    let doc2 = document.getElementById('mod_cmd');
    let doc3 = document.getElementById('fun_cmd');
    let doc4 = document.getElementById('util_cmd');

    listCommand.style.alignItems = "normal";

    if (doc1 != null) {
        doc1.style.display = 'none';
    }

    doc2.style.display = 'none';
    doc3.style.display = 'none';
    doc4.style.display = 'none';

    switch (button) {
        case ('button_staff'):
            doc1.style.margin = '0 auto';
            doc1.style.display = 'flex';
            break;
        case ('button_mod'):
            doc2.style.margin = '0 auto';
            doc2.style.display = 'flex';
            break;
        case ('button_fun'):
            doc4.style.margin = '0 auto';
            doc3.style.display = 'flex';
            break;
        case ('button_util'):
            doc4.style.margin = '0 auto';
            doc4.style.display = 'flex';
            break;
    };

    return document.getElementById('noCommandSelected').style.display = 'none';
};