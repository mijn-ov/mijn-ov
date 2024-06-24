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

function createRouteMessage(legTransitName, legDuration, legCategory, originTrack, originStation, destinationTrack, destinationStation, parent) {
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
    stationPerron.textContent = `Opstap:`;
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
    stationPerronA.textContent = `Afstap:`;
    let stationNameA = document.createElement('p');
    stationNameA.textContent = `${destinationStation}`;
    rightSideDiv.append(stationPerronA)
    rightSideDiv.append(stationNameA)
    routePartial.append(rightSideDiv)
    let imgHoldDiv = document.createElement('div');
    imgHoldDiv.classList.add('legTypeIcon');
    let newImg = document.createElement('img');
    if (legCategory === 'Trein') {
        newImg.src = './img/icons/r_trein.svg'
    } else if (legCategory === 'Bus') {
        newImg.src = './img/icons/r_bus.svg'
    } else {
        newImg.src = './img/icons/r_tram.svg'
    }
    imgHoldDiv.append(newImg)
    routePartial.append(imgHoldDiv);
    parent.append(routePartial);
}

function createWalkBubble(walkInstructionsText, distance, legDuration, parent) {

    let routePartial = document.createElement('div');
    routePartial.classList.add('route-partial');
    routePartial.classList.add('route')
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

    parent.append(routePartial)
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
    if (firstChat) {
        openHelpBox();
    }
    firstChat = false

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
    let jsonResponse = JSON.parse(data.response);

    try {
        const apiKey = 'AIzaSyCnrZkJw8-k4KJRMFSk7jdIQ7tUYNqvGYY';
        const proxyUrl = 'https://api.allorigins.win/get?url=';
        const endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(jsonResponse.origin)}&destination=${encodeURIComponent(jsonResponse.destination)}&alternatives=true&mode=transit&key=${apiKey}&language=nl`;

        const response = await fetch(proxyUrl + encodeURIComponent(endpoint));
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }


        const data = await response.json();
        const apiResponse = JSON.parse(data.contents); // Parse the contents field to get the actual API response
        console.log(apiResponse);
        if (apiResponse.status !== 'OK') {
            throw new Error(`API Error: ${apiResponse.status}`);
        }
        console.log(apiResponse);
        let setRouteObject = document.getElementById('emissionsRoute');
        let setRouteStatus = document.getElementById('routeObject');
        setRouteStatus.value = [`${apiResponse.routes[0].legs[0].start_address} ^ ${apiResponse.routes[0].legs[0].end_address}`];
        setRouteObject.value = apiResponse;
        console.log(setRouteStatus.value);
        let parentDiv = document.createElement('div');
        parentDiv.classList.add('route');
        for (let leg of apiResponse.routes[0].legs[0].steps) {
            if (leg.travel_mode !== "WALKING") {
                let legTransitName = `${leg.transit_details.line.vehicle.name} ${leg.transit_details.headsign}`;
                let legDuration = leg.duration.text;
                let legCategory = leg.transit_details.line.vehicle.name;
                let originTrack = leg.transit_details.departure_stop.name;
                let destinationTrack = leg.transit_details.arrival_stop.name;
                let imgHoldDiv = document.createElement('div');
                let departureTime = null;
                let arrivalTime = null;
                console.log(originTrack, destinationTrack);
                createRouteMessage(legTransitName, legDuration, legCategory, originTrack, originTrack, destinationTrack, destinationTrack, parentDiv
                )
                ;

            } else {
                createWalkBubble(leg.html_instructions, leg.distance.text, leg.duration.text, parentDiv)
            }
        }
        let arrivalTDiv = document.createElement('div')
        arrivalTDiv.classList.add('arrivalT');
        let arrivalText = document.createElement('p');
        arrivalText.innerHTML = apiResponse.routes[0].legs[0].arrival_time.text;
        arrivalText.classList.add('arrivalText');
        arrivalTDiv.append(arrivalText);

        let departureTDiv = document.createElement('div')
        departureTDiv.classList.add('departureT');
        let departureText = document.createElement('p');
        departureText.innerHTML = apiResponse.routes[0].legs[0].departure_time.text;
        departureText.classList.add('departureText')
        departureTDiv.append(departureText)
        parentDiv.append(arrivalTDiv);
        parentDiv.append(departureTDiv)
            messageArea.append(parentDiv)
        document.getElementById("trip_name").value = `${jsonResponse.origin} -> ${jsonResponse.destination}`;
        document.getElementById("trip_url").value = proxyUrl + encodeURIComponent(endpoint);
        console.log(document.getElementById('trip_url').value);

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

function uploadMessages(messages) {
    fetch('/berichten', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({messages: messages}),
    })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
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
