const users = document.querySelectorAll('.user.level')

if (users) {
    for (user of users) {
        user.addEventListener('click', showDetails);
    }
}

function showDetails(event) {
    const target = event.target.closest('.user.real');
    const details = target.children[1];

    details.classList.toggle('clicked');
    target.classList.toggle('clicked');
}

const profileImage = document.querySelectorAll('.user.profile img')

if (profileImage) {
    for (let userImage of profileImage) {
        fetch(userImage.src)
            .then(data => {
                if (data.status !== 200) {
                    const errorMessage = "Error loading the image from Discord, the image might have been moved or deleted.";

                    userImage.title = errorMessage;
                    userImage.src = '/assets/images/all/error.png';
                }
            })
    }
}