window.addEventListener('load', init)
window.addEventListener('resize', updateTransform);


let chatInput;
let helpText;
let chatForum;
let chatText;
let appSplash;
let messageArea;
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let messages = [];
let userBubbles = [];
let botBubbles = [];

let helpBox;
let helpBoxArrow;
let helpBoxArrowIcon;
let helpBoxOpen = false;
let firstChat = true;

let chatID;


function init() {
    chatInput = document.getElementById('chat-box');
    helpText = document.getElementById('help-text');
    chatForum = document.getElementById('chatbox');
    appSplash = document.getElementById('app-splash');
    messageArea = document.getElementById('message-area');

    helpBox = document.getElementById('help-box');
    helpBoxArrow = document.getElementById('help-box-arrow')
    helpBoxArrowIcon = document.getElementById('help-box-arrow-icon')

    chatForum.addEventListener('submit', function (e) {
        e.preventDefault();

        if (chatInput.value !== '') {
            submitChat();
        }
    })

    helpBoxArrow.addEventListener('click', function () {
        if (!helpBoxOpen) {
            openHelpBox()
        } else {
            closeHelpBox()
        }
    })
}

function createRouteMessage(legTransitName, legDuration, legCategory, originTrack, originStation, destinationTrack, destinationStation) {
    if (firstChat) {
        openHelpBox();
    }
    firstChat = false

    //div + div header
    let routePartial = document.createElement('div');
    routePartial.classList.add('route-partial');
    let legHeader = document.createElement('div');
    legHeader.classList.add('leg-header');
    let transportName = document.createElement('p');
    transportName.textContent = `${legTransitName} ● ${legDuration}`;
    let transportType = document.createElement('p');
    transportType.textContent = `${legCategory}`;
    legHeader.append(transportName);
    legHeader.append(transportType)
    routePartial.append(legHeader);

    //Inner div content
    let leftSideDiv = document.createElement('div');
    let stationPerron = document.createElement('p');
    stationPerron.classList.add('station')
    stationPerron.textContent = `Opstap Spoor: ${originTrack}`;
    let stationName = document.createElement('p');
    stationName.textContent = `${originStation}`;
    leftSideDiv.append(stationPerron)
    leftSideDiv.append(stationName)
    routePartial.append(leftSideDiv)

    let arrowImg = document.createElement('img');
    arrowImg.src = 'img/arrow-right.svg';
    arrowImg.classList.add('arrowImg');
    routePartial.append(arrowImg);

    let rightSideDiv = document.createElement('div');
    let stationPerronA = document.createElement('p');
    stationPerronA.classList.add('station')
    stationPerronA.textContent = `Afstap Spoor:${destinationTrack}`;
    let stationNameA = document.createElement('p');
    stationNameA.textContent = `${destinationStation}`;
    rightSideDiv.append(stationPerronA)
    rightSideDiv.append(stationNameA)
    routePartial.append(rightSideDiv)

    messageArea.append(routePartial);
}

function createWalkBubble(walkInstructionsText, distance, legDuration) {
    let routePartial = document.createElement('div');
    routePartial.classList.add('route-partial');
    let walkInstructions = document.createElement('p');
    walkInstructions.textContent = walkInstructionsText;
    routePartial.append(walkInstructions)
    let legHeader = document.createElement('div');
    legHeader.classList.add('leg-header');
    let transportName = document.createElement('p');
    transportName.textContent = `${distance} ● ${legDuration}`;
    let transportType = document.createElement('p');
    transportType.textContent = `Lopen`;
    legHeader.append(transportName);
    legHeader.append(transportType);
    routePartial.append(legHeader);

    messageArea.append(routePartial)
}

function createUserBubble(text) {
    let chatBubble = document.createElement('div');
    chatBubble.classList.add('chat-bubble-user');
    chatBubble.innerText = text;
    messageArea.appendChild(chatBubble);

    let elementWidth = chatBubble.offsetWidth;
    chatBubble.style.transform = 'translateX(calc(50vw - 25px - ' + elementWidth / 2 + 'px))';

    userBubbles.push(chatBubble)
}

function createBotBubble(text) {

    let chatBubble = document.createElement('div');
    chatBubble.classList.add('chat-bubble-bot');
    chatBubble.innerText = text;
    messageArea.appendChild(chatBubble);

    let elementWidth = chatBubble.offsetWidth;
    chatBubble.style.transform = 'translateX(calc(-50vw + 25px + ' + elementWidth / 2 + 'px))';

    botBubbles.push(chatBubble)
}

function updateTransform() {
    for (let bubble of userBubbles) {
        let elementWidth = bubble.offsetWidth;
        bubble.style.transform = 'translateX(calc(50vw - 25px - ' + elementWidth / 2 + 'px))';
    }
    for (let bubble of botBubbles) {
        let elementWidth = bubble.offsetWidth;
        bubble.style.transform = 'translateX(calc(-50vw + 25px + ' + elementWidth / 2 + 'px))';
    }
}

async function submitChat() {

    if (helpText !== null) {
        helpText.remove()
    }

    appSplash.remove()

    chatText = chatInput.value
    chatInput.value = '';

    createUserBubble(chatText);

    let newMessage = {
        agent: 'user',
        message: chatText
    };

    handleChat(newMessage)

    messages.push(newMessage);
    console.log(messages);

    messageArea.scrollTop = messageArea.scrollHeight;

    fetch(`/submit-message`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            message: chatText,
            history: messages,
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            receiveMessage(data)
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

async function handleChat(messsage) {
    if (firstChat) {
        try {
            const response = await createHistory('test');
            chatID = response.chatID; // Update chatID with the newly created one
        } catch (error) {
            console.error('Error creating history:', error);
            return; // Exit if there's an error creating history
        }
    }

    if (chatID) {
        messsage.history_id = chatID;
        await uploadMessages(messsage);
    }
}


async function receiveMessage(data) {
    console.log(data.response)
    let jsonResponse = JSON.parse(data.response);

    try {
        const apiKey = '***';
        const proxyUrl = 'https://api.allorigins.win/get?url=';
        const endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(jsonResponse.origin)}&destination=${encodeURIComponent(jsonResponse.destination)}&mode=transit&key=${apiKey}`;

        const response = await fetch(proxyUrl + encodeURIComponent(endpoint));
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        createBotBubble(jsonResponse.message)

        const data = await response.json();
        const apiResponse = JSON.parse(data.contents); // Parse the contents field to get the actual API response

        if (apiResponse.status !== 'OK') {
            throw new Error(`API Error: ${apiResponse.status}`);
        }

        for (let leg of apiResponse.routes[0].legs[0].steps) {
            if (leg.travel_mode !== "WALKING") {
                let legTransitName = `${leg.transit_details.line.vehicle.name} ${leg.transit_details.headsign}`;
                let legDuration = leg.duration.text;
                let legCategory = leg.transit_details.line.vehicle.name;
                let originTrack = leg.transit_details.departure_stop.name;
                let destinationTrack = leg.transit_details.arrival_stop.name;

                console.log(originTrack, destinationTrack);
                createRouteMessage(legTransitName, legDuration, legCategory, originTrack, originTrack, destinationTrack, destinationTrack);
            } else {
                createWalkBubble(leg.html_instructions, leg.distance.text, leg.duration.text)
            }
        }


        let newMessage = {
            agent: 'bot',
            message: jsonResponse.beschrijving,
            data: jsonResponse.data,
        };

        messages.push(newMessage);
        console.log(messages);

        messageArea.scrollTop = messageArea.scrollHeight;
    } catch (error) {
        console.error('Error fetching directions:', error);
    }
}

async function createHistory(title) {
    try {
        const response = await fetch('/berichten-create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ history: title }),
        });
        return await response.json();
    } catch (error) {
        console.error('Error creating history:', error);
    }
}

async function uploadMessages(messages, apidata, id) {
    try {
        const response = await fetch('/berichten', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                messages: messages,
                data: apidata || null,
                history_id: chatID,
            }),
        });
        const data = await response.json();
        console.log('Success:', data);
    } catch (error) {
        console.error('Error uploading messages:', error);
    }
}


function openHelpBox() {
    console.log('Help open')
    helpBoxOpen = true

    helpBox.style.transform = 'translateY(-18px)';
    helpBoxArrowIcon.style.transform = 'rotateX(0deg)';
}

function closeHelpBox() {
    console.log('Help closed')

    helpBoxOpen = false

    helpBox.style.transform = 'translateY(69px)';
    helpBoxArrowIcon.style.transform = 'rotateX(180deg)';
}
