window.addEventListener('load', init);

let previousButton;
let nextButton;
let days = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];
let popUp;
let body;
let popUpContent
let day

const apiKey = 'AIzaSyCnrZkJw8-k4KJRMFSk7jdIQ7tUYNqvGYY';

function init() {
    console.log('loaded')
    previousButton = document.getElementById('back');
    nextButton = document.getElementById('next');

    body = document.getElementById('app')

    // Get the current day from the URL
    const url = new URL(window.location.href);
    const currentDay = url.pathname.split('/').pop().toLowerCase();

    // Check if the current day is valid
    if (!days.includes(currentDay)) {
        console.error("Invalid day in URL");
        return;
    }

    const agendaEntries = document.querySelectorAll('.agenda-entry');

    agendaEntries.forEach(entry => {
        entry.addEventListener('click', () => {
            const tripData = JSON.parse(entry.dataset.trip);

            day = tripData.day

            openPopUp(tripData)
            getRoute(tripData)
        });
    });

    updateButtons(currentDay);
}

function updateButtons(currentDay) {
    const currentIndex = days.indexOf(currentDay);

    // Handle previous button click
    previousButton.addEventListener('click', () => {
        const previousIndex = (currentIndex - 1 + days.length) % days.length;
        const previousDay = days[previousIndex];
        window.location.href = `/agenda/${previousDay}`;
    });

    // Handle next button click
    nextButton.addEventListener('click', () => {
        const nextIndex = (currentIndex + 1) % days.length;
        const nextDay = days[nextIndex];
        window.location.href = `/agenda/${nextDay}`;
    });
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
    arrowImg.src = '../img/arrow-right.svg';
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

    popUpContent.append(routePartial);
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

    popUpContent.append(routePartial)
}

async function getRoute(tripData) {
    try {
        const proxyUrl = 'https://api.allorigins.win/get?url=';
        let endpoint;

        if (tripData.travel_type === 0) {
            endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(tripData.start_address)}&destination=${encodeURIComponent(tripData.end_address)}&mode=transit&key=${apiKey}&language=nl&arrival_time=${getTimeInEpoch(tripData.time)}`;
        } else {
            endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(tripData.start_address)}&destination=${encodeURIComponent(tripData.end_address)}&mode=transit&key=${apiKey}&language=nl&departure_time=${getTimeInEpoch(tripData.time)}`;
        }

        console.log("Generated endpoint:", endpoint);

        const response = await fetch(proxyUrl + encodeURIComponent(endpoint));
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const proxyData = await response.json();
        console.log("Proxy response data:", proxyData);

        // Parse the contents field to get the actual API response
        const apiResponse = JSON.parse(proxyData.contents);

        if (apiResponse.status !== 'OK') {
            throw new Error(`API Error: ${apiResponse.status}`);
        }

        console.log(apiResponse.routes)

        updateAgenda(apiResponse, tripData)

        if (apiResponse.routes) {
            createRoute(apiResponse); // Pass the route object to createRoute function
        } else {
            throw new Error('No routes found in API response');
        }

    } catch (error) {
        console.error('Error fetching directions:', error);
        // Handle error as needed, e.g., display an error message to the user
    }
}



function getTimeInEpoch(timeString) {
    let dayOfWeek = day

    // Validate dayOfWeek
    const daysOfWeek = ["zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"];
    const index = daysOfWeek.indexOf(dayOfWeek.toLowerCase());
    if (index === -1) {
        throw new Error("Invalid day of the week. Please use one of: zondag, maandag, dinsdag, woensdag, donderdag, vrijdag, zaterdag.");
    }

    // Split the time string by colon (:)
    const timeParts = timeString.split(':');

    // Check if the format is valid (hours:minutes)
    if (timeParts.length !== 2) {
        throw new Error("Invalid time format. Please use HH:MM format.");
    }

    // Extract hours and minutes
    const hours = parseInt(timeParts[0]);
    const minutes = parseInt(timeParts[1]);

    // Validate hours and minutes
    if (hours < 0 || hours > 23 || minutes < 0 || minutes > 59) {
        throw new Error("Invalid time values. Hours must be between 0 and 23, minutes between 0 and 59.");
    }

    // Get current date and time
    const currentDate = new Date();

    // Calculate days to add to reach the specified day of the week
    let daysToAdd = index - currentDate.getDay();
    if (daysToAdd <= 0) {
        daysToAdd += 7; // If the day has passed this week, add 7 days to get to next occurrence
    }

    // Create a new Date object for the next occurrence of the specified day of the week
    const nextDate = new Date(currentDate);
    nextDate.setDate(currentDate.getDate() + daysToAdd);

    // Set hours, minutes, seconds, milliseconds
    nextDate.setHours(hours, minutes, 0, 0);

    // Get epoch time in milliseconds
    const epochTime = nextDate.getTime();

    return epochTime / 1000;
}

function createRoute(apiResponse) {
    let mapInfoPoints = [];

    let info = document.createElement('h3')
    info.innerHTML = `Vertrek: ${apiResponse.routes[0].legs[0].departure_time.text}, Aankomst ${apiResponse.routes[0].legs[0].arrival_time.text} | ${apiResponse.routes[0].legs[0].duration.text},`
    popUpContent.appendChild(info)

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
}

async function updateAgenda(apiResponse, tripData) {
    let body = {
        id: tripData.id,
        duration: apiResponse.routes[0].legs[0].duration.text,
        departure_time: apiResponse.routes[0].legs[0].departure_time.text,
        arrival_time: apiResponse.routes[0].legs[0].arrival_time.text
    };

    console.log("Request Body:", body);

    try {
        const response = await fetch('/agenda/edit/time', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(body),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        console.log('Response from server:', data); // Log the response data

        // Optionally handle the response data here

    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
}


function openPopUp(data) {
    popUp = document.createElement('div');
    popUp.classList.add('popup')
    body.appendChild(popUp)

    let popUpBody = document.createElement('div')
    popUpBody.classList.add('popup-body')
    popUp.appendChild(popUpBody)

    let popUpTitle = document.createElement('h2')
    popUpTitle.innerText = `Je reis`;
    popUpBody.appendChild(popUpTitle)

    popUpContent = document.createElement('div')
    popUpContent.classList.add('popup-content')
    popUpBody.appendChild(popUpContent)

    let buttonContainer = document.createElement('div')
    buttonContainer.classList.add('button-container')
    popUpBody.appendChild(buttonContainer)


    let cancelButton = document.createElement('a')
    cancelButton.classList.add('btn')
    cancelButton.innerText = 'Oke'
    buttonContainer.appendChild(cancelButton)

    let editButton = document.createElement('a')
    editButton.classList.add('btn-outline')
    editButton.innerText = 'Bewerken'
    buttonContainer.appendChild(editButton)

    let deleteButton = document.createElement('a')
    deleteButton.classList.add('btn-outline')
    deleteButton.innerText = 'Verwijderen'
    buttonContainer.appendChild(deleteButton)

    cancelButton.addEventListener('click', () => {
        window.location.reload(); // Refresh the page
        popUp.remove();
    });

    editButton.addEventListener('click', () => {
        window.location.href = `/agenda/bewerk/${data.id}`;
    });

    deleteButton.addEventListener('click', () => {
        window.location.href = `/agenda/delete/${data.id}`;
    });
}
