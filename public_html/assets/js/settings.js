// Query All
const setting = document.querySelectorAll('.setting.option button');
const guildIcon = document.querySelectorAll('.guild.icon');
const reviewIcon = document.querySelectorAll('.review.icon');
const button = document.querySelectorAll('.review.button');
const submit = document.querySelectorAll('input[type="submit"]');

// Query Single
const main = document.querySelector('main');
const nav = document.querySelector('nav');
const settingList = document.querySelector('.setting.list');
const statusMessage = document.querySelector('.status.message');
const closeGuild = document.querySelector('.guild.close');
const closeReview = document.querySelector('.review.close');
const guildModify = document.querySelector('.guild.modify');
const review = document.querySelector('.review.status');
const guildColumn = document.querySelector('.guild.column.padding');
const adminReview = document.querySelector('.admin.review');

if (review) {
    review.addEventListener('click', reviewToggle);
    closeReview.addEventListener('click', reviewToggle);

    function reviewToggle() {
        adminReview.classList.toggle('active');
    }
}

for (i of reviewIcon) {
    i.addEventListener('click', toggleReview);
}

for (i of setting) {
    i.addEventListener('click', toggleOption);
}

for (i of guildIcon) {
    i.addEventListener('click', modifyGuild);
}

for (i of button) {
    i.addEventListener('click', toggleDescription);
}

for (i of submit) {
    i.addEventListener('click', submitInformation);
}

window.addEventListener('resize', resizeWindow);
nav.addEventListener('click', navToggles);
closeGuild.addEventListener('click', modifyGuild);

if (window.innerWidth < 768) resizeWindow();

function resizeWindow() {
    if (window.innerWidth < 768) {
        nav.classList.add('badHeight');
        settingList.classList.add('badHeight');
        status('badHeight', 'You cannot use this section of the site with this device.', 0);
    } else {
        nav.classList.remove('badHeight');
        settingList.classList.remove('badHeight');
        statusMessage.classList.remove('badHeight');
    }
}

function navToggles(event) {
    if (!event.target.id) return;

    const allSettings = document.querySelectorAll('.setting.type');

    for (let setting of allSettings) {
        if (setting.id === event.target.id) {
            setting.classList.add('active');
        } else {
            setting.classList.remove('active');
        }
    }
}

function toggleOption(event) {
    const button = event.target.closest('.setting.option');

    button.children[0].children[0].classList.toggle('active');
    button.classList.toggle('active');
}

async function modifyGuild(event) {
    const id = event.target.dataset.id ?? '';
    guildColumn.lastElementChild.dataset.id = id;

    if (!guildModify.classList.contains('active')) {
        const request = await fetch(`/api/user/guild`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                type: 'display_servers'
            })
        });

        const response = await request.json();
        const inputs = document.querySelectorAll(`.guild.input`);

        guildColumn.parentElement.children[0].innerHTML = `Modify → ${response.name}`; // Change name

        inputs[0].value = response.description; // Change the description

        if (typeof response.tag === null) {
            inputs[1].value = response.tag.join(', ') // Change the tags
        }

        inputs[2].checked = response.nsfw === 1 ? true : false; // Change the NSFW status
        inputs[3].checked = response.public === 1 ? true : false; // Change the Public status

        // Check if the description is scrollable
        inputs[0].scrollHeight > inputs[0].clientHeight ?
            inputs[0].classList.add('scroll') :
            inputs[0].classList.remove('scroll');
    }

    guildModify.classList.toggle('active');
}

function toggleReview(event) {
    const parent = event.target.closest('.review.column');
    const icon = event.target.closest('.review.icon');

    parent.classList.toggle('active');
    parent.children[1].classList.toggle('active');
    parent.children[2].classList.toggle('active');
    icon.classList.toggle('active');
}

function toggleDescription(event) {
    event.target.children[0].classList.toggle('active');
    event.target.parentElement.children[0].classList.toggle('active');
}

async function submitInformation(event) {
    const id = event.target.dataset.id ?? '';

    let data = {
        id: id,
        type: event.target.dataset.name,
    };

    switch (event.target.dataset.name) {
        case "submit_settings":
            typeInput = 'settings';
            requestUrl = '/api/user/settings';

            break;
        case "submit_server":
            typeInput = 'guild';
            requestUrl = '/api/user/guild';
            break;
    }

    const inputs = document.querySelectorAll(`.${typeInput}.input`);
    for (input of inputs) {
        value = input.type === 'checkbox' ?
            input.checked === true ?
                1 :
                0 : input.value;

        data[input.name] = value;
    }

    const request = await fetch(requestUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    const response = await request.json();

    response.status === 'Ok' || !request.ok ?
        status('ok', 'You update this server successfully.', 15000) :
        status('error', 'There was an error while trying to save these informations.', 15000);

    if (event.target.dataset.name === 'submit_server') {
        guildModify.classList.remove('active')
    }
}

function status(type, text, time) {
    statusMessage.className = `status message ${type}`;
    statusMessage.children[0].innerText = text;

    statusMessage.classList.add(type);

    if (time !== 0) {
        setTimeout(() => {
            statusMessage.classList.remove(type);
        }, time);
    }
}