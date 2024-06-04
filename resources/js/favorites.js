
async function createDom(parentDivID, url){

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }


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
                createRouteMessage(parentDivID, legTransitName, legDuration, legCategory, originTrack, originTrack, destinationTrack, destinationTrack);
            } else {
                createWalkBubble(parentDivID, leg.html_instructions, leg.distance.text, leg.duration.text)
            }
        }
    }
    catch (error) {
        console.error('Error fetching directions:', error);
    }
}

function createRouteMessage(parentDiv, legTransitName, legDuration, legCategory, originTrack, originStation, destinationTrack, destinationStation) {
    //div + div header
    let hostDiv = document.getElementById(`${parentDiv}`);
    console.log(hostDiv)
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

    hostDiv.append(routePartial);
}

function createWalkBubble(parentDivID, walkInstructionsText, distance, legDuration) {
    let parent = document.getElementById(`${parentDivID}`);
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

    parent.append(routePartial)

}
