const guildId = window.location.search.substring(4);

//
// Send request to self
//

async function sendRequest(eventName, eventValue, guildId) {
    const data = await fetch('/api/dashboard/guild', {
        method: 'POST',
        headers: {
            "Content-type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            'guildId': guildId,
            'name': eventName,
            'value': eventValue,
        })
    });
    return response = await data.text();
};

//
// Show option
//

async function loadOption(event) {
    const listSetting = document.getElementById('listSetting');
    const settingType_Bot = document.getElementById('settingType_Bot');
    const settingType_Admin = document.getElementById('settingType_Admin');
    const settingType_Mod = document.getElementById('settingType_Mod');
    const settingType_Fun = document.getElementById('settingType_Fun');
    const settingType_Util = document.getElementById('settingType_Util');

    // Disabling all the settings to get a full refresh.
    settingType_Bot.style.display = 'none';
    settingType_Admin.style.display = 'none';
    settingType_Mod.style.display = 'none';
    settingType_Fun.style.display = 'none';
    settingType_Util.style.display = 'none';

    // Aligning back the command list, so it looks pretty!
    listSetting.style.alignItems = "normal";
    listSetting.style.justifyContent = "normal";

    // Select the type of setting to show.
    switch (event.srcElement.value) {
        case ('Bot Settings'):
            if (settingType_Bot.style.display == 'flex') return;
            settingType_Bot.style.display = 'flex';

            break;
        case ('Admin'):
            if (settingType_Admin.style.display == 'flex') return;
            settingType_Admin.style.display = 'flex';

            break;
        case ('Moderation'):
            if (settingType_Mod.style.display == 'flex') return;
            settingType_Mod.style.display = 'flex';

            break;
        case ('Fun'):
            if (settingType_Fun.style.display == 'flex') return;
            settingType_Fun.style.display = 'flex';

            break;
        case ('Utilities'):
            if (settingType_Util.style.display == 'flex') return;
            settingType_Util.style.display = 'flex';

            break;
    }

    return document.getElementById('noSettingSelected').style.display = 'none';
}

//
// Each toggles
//

const setting_Command = document.getElementById('setting_Command');
const settingCommand_Menu = document.getElementById('settingCommand_Menu');

function toggleMenu(event) {
    const main = event.target.closest('.setting_Command');
    const dropDown = main.children[3];

    main.style.height = 'fit-content';

    if (dropDown.style.display == "none") {
        main.style.padding = "0 10px 10px 10px";
        return dropDown.style.display = "flex";
    }

    main.style.padding = "0 10px 0 10px";
    dropDown.style.display = "none";
}

function switchToggle(event) {
    const main = event.target.closest('.setting_Command').children[1];
    const main_Switch = main.children[0];

    let switchSide = 'switchToR';
    let translateTo = '25';
    let color = 'rgb(0, 162, 0)';

    if (main_Switch.style.transform == 'translate(25px)') {
        switchSide = 'switchToL';
        translateTo = '0';
        color = 'red';
    }

    main_Switch.style.animation = `${switchSide} 1.5s`;
    main_Switch.style.transform = `translate(${translateTo}px)`;
    main_Switch.style.background = color;
}

//
// When pressing on toggle send request
//

async function postSubmit(event) {
    event.preventDefault();

    const eventName = event.submitter;
    switch (eventName.name) {
        case 'language':
            switch (eventName.value) {
                case 'Français':
                    eventValue = 'fr';
                    break;
                default:
                    eventValue = 'en';
                    break;
            }

            settingCommand_Menu.style.display = 'none';
            break;
        case 'action_status':
            const message = event.target.children[1].children[2].children[1].children[0];
            const image = event.target.children[1].children[3].children[1].children[0];

            switch (eventName.id) {
                case 'toggle':
                    eventName.style.background == 'red' ?
                        eventValue = 0 :
                        eventValue = 3;

                    break;
                case 'message':
                    if (eventName.style.background == 'red' && image.style.background == 'red') {
                        eventValue = 0;
                    } else if (eventName.style.background == 'red') {
                        eventValue = 2;
                    } else if (image.style.background == 'red') {
                        eventValue = 1;
                    } else {
                        eventValue = 3;
                    }

                    break;
                case 'image':
                    if (eventName.style.background == 'red' && message.style.background == 'red') {
                        eventValue = 0;
                    } else if (eventName.style.background == 'red') {
                        eventValue = 1;
                    } else if (message.style.background == 'red') {
                        eventValue = 2;
                    } else {
                        eventValue = 3;
                    }

                    break;
            }

            break;
        case 'action_nsfw':
            console.log(eventName.name)
            eventName.style.background == 'red' ?
                eventValue = 0 :
                eventValue = 1;

            break;
    }

    console.log(eventValue)

    await sendRequest(eventName.name, eventValue, guildId);
}