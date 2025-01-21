const user = document.querySelector('.user')

if (user) user.addEventListener('click', copyID);

function copyID(event) {
    navigator.clipboard.writeText(event.target.id);
}

const profileImage = document.querySelectorAll('.user-profile img')

if (profileImage) {
    for (i = 0; i < profileImage.length; i++) {
        let img = profileImage[i];

        fetch(profileImage[i].src)
            .then(data => {
                if (data.status == 404) {
                    img.title = 'Error';
                    img.src = '/assets/images/all/error.png';
                } else return;
            })
    }
}