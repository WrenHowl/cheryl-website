// Query All
const setting = document.querySelectorAll('.setting.option button');
const guildIcon = document.querySelectorAll('.guild.icon');
const reviewIcon = document.querySelectorAll('.review.icon');

// Query Single
const nav = document.querySelector('nav')
const closeGuild = document.querySelector('.guild.close');
const closeReview = document.querySelector('.review.close');
const guildModify = document.querySelector('.guild.modify');
const review = document.querySelector('.review.status');
const adminReview = document.querySelector('.admin.review');
const reviewBottom = document.querySelector('.review.bottom');
const reviewCenter = document.querySelector('.review.center');
const reviewTop = document.querySelector('.review.column');

// Event Listener
nav.addEventListener('click', navToggles);
closeGuild.addEventListener('click', modifyGuild);
closeReview.addEventListener('click', reviewToggle);
if (review) review.addEventListener('click', reviewToggle);

for (let a of setting) {
    a.addEventListener('click', toggleOption);
}

for (let a of guildIcon) {
    a.addEventListener('click', modifyGuild);
}

for (let a of reviewIcon) {
    a.addEventListener('click', toggleReview);
}

// Functions
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
    const guildColumn = document.querySelector('.guild.column.padding');
    const guildName = document.querySelector('.guild.padding');
    const id = event.target.dataset.id ?? '';

    guildColumn.children[0].value = id;

    guildName.parentElement.action = `/api/guild/${id}`;

    if (!guildModify.classList.contains('active')) {
        const request = await fetch(`/api/guild/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id
            })
        });
        const response = await request.json();

        guildName.innerHTML = `Modify → ${response[0].name}`
        guildColumn.children[2].value = response[0].description; // Change the description
        guildColumn.children[5].children[0].checked = response[0].nsfw === 1 ?
            true :
            false; // Change the NSFW status
        guildColumn.children[7].children[0].checked = response[0].public === 1 ?
            true :
            false; // Change the Public status

        tags = []

        for (let guild of response) {
            tags.push(guild.tag);
        }

        guildColumn.children[4].value = tags.toString(); // Change the tags
    }

    guildModify.classList.toggle('active');
}

function reviewToggle() {
    adminReview.classList.toggle('active');
}

function toggleReview(event) {
    const parent = event.target.closest('.review.column');
    const icon = event.target.closest('.review.icon');

    parent.classList.toggle('active');
    parent.children[1].classList.toggle('active');
    parent.children[2].classList.toggle('active');
    icon.classList.toggle('active');
}