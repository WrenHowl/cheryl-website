const browse = document.querySelector('.browse');
const search = document.querySelector('.browse input');
const option = document.querySelector('.option');

search.addEventListener('input', searching);

async function searching() {
    let i = 0;

    const request = await fetch('/api/browse', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            tags: search.value
        })
    });
    const response = await request.json()

    if (search.value.length !== 0 && search.classList.length === 0) search.classList.toggle('create');

    while (option.children.length > 0) {
        option.removeChild(option.lastChild);
    }

    if (response['error'] !== undefined) {
        const noServer = document.querySelector('.option')

        if (noServer.children.length === 0 && search.className.length) {
            noServer.classList.toggle('none');
            search.classList.toggle('create');
        }
    } else {
        const aOption = document.querySelector('.option');

        const url = new URLSearchParams(window.location.search);

        parameter = url.get('page') ?
            `?page=${url.get('page')}&` :
            '';

        for (tag of response) {
            i++;

            const element = document.createElement('a');
            option.appendChild(element)
            element.href = `/browse${parameter}?tags=${tag}`;
            element.text = tag;
        }

        if (i >= response.length) {
            aOption.lastChild.classList.toggle('last')
        }
    }
}

const button = document.querySelectorAll('.guild.button');

for (buttons of button) {
    buttons.addEventListener('click', toggleDescription);
}

function toggleDescription(event) {
    console.log(event.target.closest('img'))
    event.target.children[0].classList.toggle('active');
    event.target.parentElement.children[0].classList.toggle('active');
}

const serverIcon = document.querySelectorAll('.guild.icon')

for (let userImage of serverIcon) {
    fetch(userImage.src)
        .then(data => {
            if (data.status !== 200) {
                const errorMessage = "Error loading the image from Discord, the image might have been moved or deleted.";

                userImage.title = errorMessage;
                userImage.src = '/assets/images/all/error-square.jpg';
            }
        })
}