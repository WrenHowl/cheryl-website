const browse = document.querySelector('.browse');
const search = document.querySelector('.browse input');
const option = document.querySelector('.option')

search.addEventListener('input', searching)
let i = 0;

async function searching() {
    let response = await fetch('/api/browse', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            tags: search.value
        })
    });
    response = await response.json()

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

        for (server of response) {
            i++;

            const element = document.createElement('a');
            option.appendChild(element)
            element.href = `/browse/guild/${server['id']}`
            element.text = server['name']
        }

        if (i >= response.length) {
            aOption.lastChild.classList.toggle('last')
        }
    }
}