async function sendRequest(eventName, valueInt) {
    await fetch('/api/dashboard/guild', {
        method: 'POST',
        headers: {
            "Content-type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            'name': eventName,
            'value': valueInt
        })
    });
};

async function postSubmit() {
    event.preventDefault();

    const eventName = event.submitter;

    switch (eventName.name) {
        case ('language'):
            switch (eventName.value) {
                case 'Français':
                    valueInt = 1;
                    break;
                default:
                    valueInt = 0;
                    break;
            }

            return sendRequest(eventName.name, valueInt);
    }
}

const botSettings = document.getElementById('settings_bot');
const adminSettings = document.getElementById('settings_admin');
const modSettings = document.getElementById('settings_mod');
const funSettings = document.getElementById('settings_fun');
const utilitiesSettings = document.getElementById('settings_util');

async function loadOption(event) {
    botSettings.style.display = 'none';
    adminSettings.style.display = 'none';
    modSettings.style.display = 'none';
    funSettings.style.display = 'none';
    utilitiesSettings.style.display = 'none';

    switch (event.srcElement.value) {
        case ('Bot Settings'):
            if (botSettings.style.display == 'flex') return;

            botSettings.style.display = 'flex'

            break;
        case ('Admin'):
            if (adminSettings.style.display == 'flex') return;

            adminSettings.style.display = 'flex'

            break;
        case ('Moderation'):
            if (modSettings.style.display == 'flex') return;

            modSettings.style.display = 'flex'

            break;
        case ('Fun'):
            if (funSettings.style.display == 'flex') return;

            funSettings.style.display = 'flex'

            break;
        case ('Utilities'):
            if (utilitiesSettings.style.display == 'flex') return;

            utilitiesSettings.style.display = 'flex'

            const data = await fetch('/api/post', {
                method: 'GET',
            });
            const response = await data.text();

            const status = await response['status'];
            const pricing = await response['pricing'];

            // Status
            if (status == 1) {
                statusDoc.value = "On";
                statusDoc.style.background = trueValue;
                statusDoc.style.border = `2px solid ${trueValue}`;
            } else {
                statusDoc.value = "Off";
                statusDoc.style.background = falseValue;
                statusDoc.style.border = `2px solid ${falseValue}`;
            }

            // Pricing
            switch (pricing) {
                case (1):
                    valueChange = 'Free';
                    break;
                case (2):
                    valueChange = '$';
                    break;
                case (3):
                    valueChange = '$$';
                    break;
                case (4):
                    valueChange = '$$$';
                    break;
                case (5):
                    valueChange = '$$$$';
                    break;
                case (6):
                    valueChange = '$$$$$';
                    break;
                default:
                    valueChange = 'Disabled';
                    break;
            }

            currentPricingDoc.innerHTML = valueChange;

            Array.prototype.slice.call(pricingDoc.children).forEach((input) => {
                valueChange = changePricing(input.value);

                if (valueChange == pricing) {
                    input.style.background = trueValue;
                    input.style.color = white;
                }
            });

            break;
    }
}

const menuOption = document.getElementById('settingsBotLanguage_menu');
const selectOption = document.getElementById('settingsOption_select');

function openMenuToggle() {
    switch (menuOption.style.display) {
        case 'none':
            menuOption.style.display = 'flex';
            settingsOption_select.style.borderRadius = '5px 5px 0 0';
            break;
        case 'flex':
            menuOption.style.display = 'none';
            settingsOption_select.style.borderRadius = '5px';
            break;
    }
}