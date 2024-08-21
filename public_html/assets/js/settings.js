function uploaded(input) {
    imageUploaded = document.getElementById(input);

    const file = imageUploaded.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
        switch (input) {
            case ('imageSendCard1'):
                selectedImg = 'imgSourceCard1';
                break;
            case ('imageSendShow1'):
                selectedImg = 'imgSourceShowcase1';
                break;
            case ('imageSendShow2'):
                selectedImg = 'imgSourceShowcase2';
                break;
            case ('imageSendShow3'):
                selectedImg = 'imgSourceShowcase3';
                break;
        };

        const imageDataUrl = e.target.result;
        const imagePreview = document.getElementById(selectedImg);
        imagePreview.style.display = "block";
        imagePreview.src = imageDataUrl;
    }

    reader.readAsDataURL(file);
}

function switchSetting(button) {
    comSetting = document.getElementById('commissionSettings').style;
    inProgress = document.getElementById('inProgress').style;

    inProgress.display = "none";
    comSetting.display = "none";

    switch (button) {
        case ('User'):
            inProgress.display = "block"
            break;
        case ('Interface'):
            inProgress.display = "block"
            break;
        case ('Commissions'):
            comSetting.display = "flex"
            break;
    }
};