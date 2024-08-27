const trueValue = '#00c700';
const falseValue = '#c70000';
const white = '#ffffff';
const mainColor = '#23242a';

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
        imagePreview.style.display = 'block';
        imagePreview.src = imageDataUrl;
    }

    reader.readAsDataURL(file);
}

const commissionSettings = document.getElementById('commissionSettings');
const userSettings = document.getElementById('userSettings');
const inProgress = document.getElementById('inProgress');

const statusDoc = document.getElementById('optionStatus');
const currentPricingDoc = document.getElementById('currentPricing');
const pricingDoc = document.getElementById('optionPricing');
const toggleMenuDoc = document.getElementById('toggleMenu');

function changePricing(eventName) {
    switch (eventName) {
        case ('Disabled'):
            valueInt = 0;
            break;
        case ('Free'):
            valueInt = 1;
            break;
        case ('$'):
            valueInt = 2;
            break;
        case ('$$'):
            valueInt = 3;
            break;
        case ('$$$'):
            valueInt = 4;
            break;
        case ('$$$$'):
            valueInt = 5;
            break;
        case ('$$$$$'):
            valueInt = 6;
            break;
    }

    return valueInt;
}

async function sendRequest(eventName, valueInt) {
    await fetch('/api/settings', {
        method: 'POST',
        headers: {
            "Content-type": "application/x-www-form-urlencoded"
        },
        body: `${eventName}=${valueInt}`
    });
};

async function postSubmit(event) {
    event.preventDefault();

    const eventName = event.submitter;

    switch (eventName.name) {
        case ('commissionStatus'):
            if (event.submitter.value === 'On') {
                valueInt = 0;
                event.submitter.value = 'Off';
                event.submitter.style.background = falseValue;
                event.submitter.style.border = `2px solid ${falseValue}`;
            } else {
                valueInt = 1;
                event.submitter.value = 'On';
                event.submitter.style.background = trueValue;
                event.submitter.style.border = `2px solid ${trueValue}`;
            }

            return sendRequest(eventName.name, valueInt);
        case ('pricingStatus'):
            await fetch('/api/settings', {
                method: 'GET',
            })
                .then((data) => data.json())
                .then((r) => {
                    pricing = r['pricing'];
                    valueInt = changePricing(eventName.value);

                    if (valueInt == pricing) {
                        event.submitter.style.background = white;
                        event.submitter.style.color = mainColor;

                        currentPricingDoc.innerHTML = 'Disabled';
                        valueInt = 0;
                    } else {
                        currentPricingDoc.innerHTML = event.submitter.value;

                        Array.prototype.slice.call(pricingDoc.children).forEach((input) => {
                            if (input.value == eventName.value) {
                                input.style.background = trueValue;
                                return input.style.color = white;
                            } else {
                                input.style.background = white;
                                input.style.color = mainColor;
                            }
                        });
                    }

                    return sendRequest(eventName.name, valueInt);
                });
    }
}

async function loadOption(event) {
    commissionSettings.style.display = 'none';
    userSettings.style.display = 'none';
    inProgress.style.display = 'none';

    switch (event.srcElement.value) {
        case ('User'):
            if (inProgress.style.display == 'flex') return;

            inProgress.style.display = 'block';

            break;
        case ('Interface'):
            if (inProgress.style.display == 'flex') return;

            inProgress.style.display = 'block';

            break;
        case ('Commissions'):
            if (commissionSettings.style.display == 'flex') return;

            commissionSettings.style.display = 'flex'

            await fetch('/api/settings', {
                method: 'GET',
            })
                .then((data) => data.json())
                .then((response) => {
                    status = response['status'];
                    pricing = response['pricing'];

                    /*              */
                    /*    Loader    */
                    /*              */

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
                        case (0):
                            valueChange = 'Disabled';
                            break;
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
                    }

                    currentPricingDoc.innerHTML = valueChange;

                    Array.prototype.slice.call(pricingDoc.children).forEach((input) => {
                        valueChange = changePricing(input.value);

                        if (valueChange == pricing) {
                            input.style.background = trueValue;
                            input.style.color = white;
                        }
                    });

                    // Slot
                });
            break;
    }
}

function openMenuToggle() {
    if (pricingDoc.style.display == 'none') {
        pricingDoc.style.display = 'inline';
    } else {
        pricingDoc.style.display = 'none';
    }
}