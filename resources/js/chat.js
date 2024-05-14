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

function init() {
    chatInput = document.getElementById('chat-box');
    helpText = document.getElementById('help-text');
    chatForum = document.getElementById('chatbox');
    appSplash = document.getElementById('app-splash');
    messageArea = document.getElementById('message-area');

    chatForum.addEventListener('submit', function (e) {
        e.preventDefault();

        if (chatInput.value !== '') {
            submitChat();
        }
    })
}

function createRouteMessage(routeDetailObject) {
    //div + div header
    let routePartial = document.createElement('div');
    routePartial.classList.add('route-partial');
    let legHeader = document.createElement('div');
    legHeader.classList.add('leg-header');
    let transportName = document.createElement('p');
    transportName.textContent = `${routeDetailObject.product.notes[1][0].shortValue} ● ${routeDetailObject.plannedDurationInMinutes}m`;
    let transportType = document.createElement('p');
    transportType.textContent = `${routeDetailObject.product.longCategoryName}`;
    legHeader.append(transportName);
    legHeader.append(transportType)
    routePartial.append(legHeader);

    //Inner div content
    let leftSideDiv = document.createElement('div');
    let stationPerron = document.createElement('p');
    stationPerron.classList.add('station')
    stationPerron.textContent = `Opstap Spoor: ${routeDetailObject.origin.actualTrack}`;
    let stationName = document.createElement('p');
    stationName.textContent = `${routeDetailObject.origin.name}`;
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
    stationPerronA.textContent = `Afstap Spoor:${routeDetailObject.destination.actualTrack}`;
    let stationNameA = document.createElement('p');
    stationNameA.textContent = `${routeDetailObject.destination.name}`;
    rightSideDiv.append(stationPerronA)
    rightSideDiv.append(stationNameA)
    routePartial.append(rightSideDiv)

    messageArea.append(routePartial);
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

function submitChat() {
    helpText.remove()
    appSplash.remove()

    chatText = chatInput.value
    chatInput.value = '';

    createUserBubble(chatText);

    let newMessage = {
        agent: 'user',
        message: chatText
    };

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

async function receiveMessage(data) {

    let jsonResponse = JSON.parse(data.response)

    try {
        const response = await fetch(`${jsonResponse.url}`, {
            headers: {
                'Accept': 'application/json',
                'Ocp-Apim-Subscription-Key': 'eae2b92d3a49458f80503a5bb6f7df14'
            }
        });
        if (response.ok) {
            let array = [];
            const data = await response.json();
            for (let stop of data.trips[0].legs[0].stops) {
                array.push([stop.lng, stop.lat])
            }
            console.log(data.trips);

            createBotBubble(jsonResponse.beschrijving);
            for (let leg of data.trips[0].legs) {
                createRouteMessage(leg);
            }

        } else {
            console.error('Fout bij ophalen reisadvies:', response.statusText);
            console.log({error: "An error occured while generating MijnOV's response"});
        }
    } catch (error) {
        console.error('Er is een fout opgetreden:', error);
    }

    console.log(jsonResponse.data)



    let newMessage = {
        agent: 'bot',
        message: jsonResponse.beschrijving,
        data: jsonResponse.data,
    };

    messages.push(newMessage);
    console.log(messages);

    messageArea.scrollTop = messageArea.scrollHeight;
}
