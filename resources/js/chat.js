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
let userQuestions = [];
let botBubbles = [];

let helpBox;
let helpBoxArrow;
let helpBoxArrowIcon;
let helpBoxOpen = false;
let firstChat = true;

let chatID;

let emmisionsButton;
let mapButton;

const apiKey = 'AIzaSyCnrZkJw8-k4KJRMFSk7jdIQ7tUYNqvGYY';

function init() {
    chatInput = document.getElementById('chat-box');
    helpText = document.getElementById('help-text');
    chatForum = document.getElementById('chatbox');
    appSplash = document.getElementById('app-splash');
    messageArea = document.getElementById('message-area');

    helpBox = document.getElementById('help-box');
    helpBoxArrow = document.getElementById('help-box-arrow')
    helpBoxArrowIcon = document.getElementById('help-box-arrow-icon')

    emmisionsButton = document.getElementById('emmisions-button');
    mapButton = document.getElementById('map-button');

    emmisionsButton.addEventListener('click', function () {
        console.log(chatID)
        window.location.href = `/uitstoot/${chatID}`;
    })

    mapButton.addEventListener('click', function () {
        console.log(chatID)
        window.location.href = `/map/${chatID}`;
    })


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

    if (chatHistoryData) {
        console.log(chatHistoryData)
            chatID = chatHistoryData[0].history_id
        try {

            chatHistoryData.forEach(history => {
                try {
                    const messageObj = JSON.parse(history.message);

                    if (messageObj.agent === 'user') {
                        createUserBubble(messageObj.message);
                    } else if (messageObj.agent === 'bot' && messageObj.message !== null) {
                        createBotBubble(messageObj.message);
                    }

                    const dataObj = JSON.parse(history.data);
                    if (dataObj) {
                        createRoute(dataObj, dataObj)
                    }

                } catch (error) {
                    console.error('Error parsing message:', error);
                }
            });

            openHelpBox()
            appSplash.remove()

            console.log(chatID)
        } catch (error) {
            console.error('Error parsing chat history data:', error);
        }
    }
}

function createRouteMessage(legTransitName, legDuration, legCategory, originTrack, originStation, destinationTrack, destinationStation) {
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

function createExplanation() {
    let explanation = document.createElement('div');
    explanation.classList.add('chat-explanation');
    explanation.innerHTML = `Informatie kan soms afwijkend zijn · <a href="/verklaring/${chatID}">Bekijk waarop dit gebaseerd is</a>`;
    messageArea.appendChild(explanation);
}

function createUserBubble(text) {
    let chatBubble = document.createElement('div');
    chatBubble.classList.add('chat-bubble-user');
    chatBubble.innerText = text;
    messageArea.appendChild(chatBubble);

    let elementWidth = chatBubble.offsetWidth;
    chatBubble.style.transform = 'translateX(calc(50vw - 25px - ' + elementWidth / 2 + 'px))';

    userBubbles.push(chatBubble)
    userQuestions.push(text)
}

function createBotBubble(text) {
    let chatBubble = document.createElement('div');
    chatBubble.classList.add('chat-bubble-bot');
    chatBubble.innerText = text;
    messageArea.appendChild(chatBubble);

    let elementWidth = chatBubble.offsetWidth;
    chatBubble.style.transform = 'translateX(calc(-50vw + 25px + ' + elementWidth / 2 + 'px))';

    botBubbles.push(chatBubble)

    console.log(firstChat)

    if (firstChat) {
        createExplanation()
        openHelpBox();
    }
    firstChat = false
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
        helpText.remove();
    }

    appSplash.remove();

    chatText = chatInput.value;
    chatInput.value = '';

    createUserBubble(chatText);

    let newMessage = {
        agent: 'user',
        message: chatText,
    };

    handleChat(newMessage);

    messages.push(newMessage);
    console.log(messages);

    messageArea.scrollTop = messageArea.scrollHeight;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const locationName = await getLocationName(lat, lng);
            sendMessageToBackend(locationName);
            console.log(locationName)
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}


async function getLocationName(lat, lng) {
    const endpoint = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${apiKey}`;
    try {
        const response = await fetch(endpoint);
        const data = await response.json();
        if (data.status === 'OK') {
            const results = data.results;
            if (results.length > 0) {
                console.log(results)
                return results[0].formatted_address;
            } else {
                throw new Error('No results found');
            }
        } else {
            throw new Error(`Geocode error: ${data.status}`);
        }
    } catch (error) {
        console.error('Error fetching location name:', error);
        return null;
    }
}


async function sendMessageToBackend(locationName) {
    let body = {
        message: chatText, // Replace with your actual chat text
        location: locationName, // Replace with your location name variable
        previousQuestions: JSON.stringify(userQuestions), // Replace with your user questions array
    };

    console.log(body);

    try {
        const response = await fetch('/submit-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken, // Replace with your CSRF token variable
            },
            body: JSON.stringify(body), // Ensure body is stringified
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        receiveMessage(data); // Handle received message data

    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
}

async function handleChat(messsage) {
    if (firstChat) {
        try {
            const response = await createHistory('Gesprek zonder titel');
            chatID = response.chatID; // Update chatID with the newly created one
        } catch (error) {
            console.error('Error creating history:', error);
            return; // Exit if there's an error creating history
        }
    }

    if (chatID) {
        await uploadMessages(messsage);
    }
}


async function receiveMessage(data) {
    console.log(data.response);
    let jsonResponse = JSON.parse(data.response);

    createBotBubble(jsonResponse.message);

    let newMessage = {
        agent: 'bot',
        message: jsonResponse.message,
    };

    if (chatID) {
        await uploadMessages(newMessage, null, chatID);
    }
    updateHistory(jsonResponse.title);

    console.log(data);

    try {
        const proxyUrl = 'https://api.allorigins.win/get?url=';
        let endpoint;

        endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(jsonResponse.origin)}&destination=${encodeURIComponent(jsonResponse.destination)}&mode=transit&key=${apiKey}&language=nl`;

        console.log(endpoint)

        const response = await fetch(proxyUrl + encodeURIComponent(endpoint));
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        const apiResponse = JSON.parse(data.contents); // Parse the contents field to get the actual API response

        if (apiResponse.status !== 'OK') {
            throw new Error(`API Error: ${apiResponse.status}`);
        }

        let newMessage2 = {
            agent: 'bot',
            message: null,
        };

        if (chatID) {
            await uploadMessages(newMessage2, JSON.stringify(apiResponse), chatID);
        }
        createRoute(apiResponse, jsonResponse);


        messages.push(newMessage);
        console.log(messages);

        messageArea.scrollTop = messageArea.scrollHeight;
    } catch (error) {

        let errorMessage = 'Er is helaas iets misgegaan. Probeer je vraag nog een keer te stellen of kom later terug!'

        createBotBubble(errorMessage)

        if (chatID) {
            let newMessage = {
                agent: 'bot',
                message: errorMessage,
            };

            await uploadMessages(newMessage, null, chatID);
        }
        console.error('Error fetching directions:', error);
    }
}


function createRoute(apiResponse, jsonResponse) {
    let mapInfoPoints = [];
    const proxyUrl = 'https://api.allorigins.win/get?url=';
    let endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(jsonResponse.origin)}&destination=${encodeURIComponent(jsonResponse.destination)}&mode=transit&key=${apiKey}&language=nl`;
    for (let leg of apiResponse.routes[0].legs[0].steps) {
        if (leg.travel_mode !== "WALKING") {
            let legTransitName = `${leg.transit_details.line.vehicle.name} ${leg.transit_details.headsign}`;
            let legDuration = leg.duration.text;
            let legCategory = leg.transit_details.line.vehicle.name;
            let originTrack = leg.transit_details.departure_stop.name;
            let destinationTrack = leg.transit_details.arrival_stop.name;
            let mapInfo = leg;

            // Push origin and destination points to their respective arrays
            mapInfoPoints.push(mapInfo);
            createRouteMessage(legTransitName, legDuration, legCategory, originTrack, originTrack, destinationTrack, destinationTrack);
        } else {
            createWalkBubble(leg.html_instructions, leg.distance.text, leg.duration.text);
        }
    }

    document.getElementById("trip_name").value = `${jsonResponse.origin} -> ${jsonResponse.destination}`;
    document.getElementById("trip_url").value = proxyUrl + encodeURIComponent(endpoint);
    console.log(document.getElementById('trip_url').value);
    localStorage.setItem('mapInfoPoints', JSON.stringify(mapInfoPoints));
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
            body: JSON.stringify({history: title}),
        });
        return await response.json();
    } catch (error) {
        console.error('Error creating history:', error);
    }
}

async function updateHistory(title) {
    try {
        const response = await fetch(`/berichten-update/${chatID}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({title: title}),
        });
        await console.log(response.json())
    } catch (error) {
        console.error('Error updating history:', error);
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

    helpBox.style.transform = 'translateY(-84px)';
    helpBoxArrowIcon.style.transform = 'rotateX(0deg)';
}

function closeHelpBox() {
    console.log('Help closed')

    helpBoxOpen = false

    helpBox.style.transform = 'translateY(44px)';
    helpBoxArrowIcon.style.transform = 'rotateX(180deg)';
}
