@extends('layouts.app')
@vite(['resources/js/favorites.js'])
@section('content')
    <script>
        const openedArrayCheck = [];

        async function createDom(parentDivID, url) {
            if (openedArrayCheck[`${parentDivID}`] === undefined) {
                openedArrayCheck[`${parentDivID}`] = true;
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
                            await createRouteMessage(parentDivID, legTransitName, legDuration, legCategory, originTrack, originTrack, destinationTrack, destinationTrack);
                        } else {
                            await createWalkBubble(parentDivID, leg.html_instructions, leg.distance.text, leg.duration.text)
                        }
                    }
                    let parent = document.getElementById(`${parentDivID}`);
                    parent.style.marginBottom = `-${parent.offsetHeight}`;
                    parent.style.transitionProperty = "none !important"
                    parent.style.opacity = "1";
                    parent.style.transform = "translateY(0)";
                    parent.style.transition = "700ms ease-in-out 700ms";
                    parent.style.marginBottom = '0';

                } catch (error) {
                    console.error('Error fetching directions:', error);
                }
            } else if (openedArrayCheck[`${parentDivID}`] === true) {
                openedArrayCheck[`${parentDivID}`] = false;
                let parent = document.getElementById(`${parentDivID}`);
                parent.style.marginBottom = `-${parent.offsetHeight}`;
                parent.style.transform = "translateY(-100%)";
                parent.style.opacity = "0";
            } else {
                openedArrayCheck[`${parentDivID}`] = true;
                let parent = document.getElementById(`${parentDivID}`);
                parent.style.marginBottom = `0`;
                parent.style.transform = "translateY(0)";
                parent.style.opacity = "1";
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

    </script>
    <style>
        main {
            overflow: scroll;
        }


        .py-4 {
            padding-top: 0 !important;
            border-top: 1rem solid #f5f5f5;
        }
    </style>
    <div class="w-100 h-100 flex flex-col items-center justify-content-center" style="margin-bottom: 100px;">
        <div
            style="width: 100vw; background-color: #f5f5f5; z-index: 40; color: #3d3d3d; display: flex; justify-content: center">
            <h1>Favoriete Reizen</h1>
        </div>
        @foreach($favorites as $favorite)
            <div style="display: flex; justify-content: center; flex-direction: column; align-items: center">
                <button onclick="createDom({{$favorite->id}}, '{{$favorite->trip_url}}')" style="z-index:50">
                    <div class="favoriteCard flex justify-between flex-col">
                        <p class="favoriteText">{{$favorite->trip_name}}</p>
                        <p style="font-weight: lighter; font-style: italic; font-size: 0.9rem">Klik hier om uw
                            reisadvies te
                            zien</p>
                    </div>
                </button>
            </div>
            <div id="{{$favorite->id}}" style="z-index: 30; transform: translateY(-100%); opacity: 0"
                 class="trip"></div>
        @endforeach
        <div id="200"
             style="z-index: 30; transform: translateY(0px); opacity: 1; margin-bottom: 0px; transition: all 700ms ease-in-out 700ms;"
             class="route">
            <div class="route-partial"><p>Loop naar Amsterdam Centraal</p>
                <div class="leg-header"><p>0,2 km ● 1 min</p>
                    <p>Lopen</p></div>
                <div class="legTypeIcon"><img src="{{ asset('img/icons/walk.svg') }}"></div>
            </div>
            <div class="route-partial">
                <div class="leg-header"><p>Trein Rotterdam Centraal ● 43 min</p>
                    <p>Trein</p></div>
                <div><p class="station">Opstap:</p>
                    <p>Amsterdam Centraal</p></div>
                <img src="img/arrow-right.svg" class="arrowImg">
                <div><p class="station">Afstap:</p>
                    <p>Rotterdam Centraal</p></div>
            </div>
            <div class="route-partial"><p>Loop naar Rotterdam, Henegouwerplein</p>
                <div class="leg-header"><p>0,6 km ● 8 min</p>
                    <p>Lopen</p></div>
            </div>
            <div class="route-partial">
                <div class="leg-header"><p>Bus Station Schiedam Centrum ● 14 min</p>
                    <p>Bus</p></div>
                <div><p class="station">Opstap:</p>
                    <p>Rotterdam, Henegouwerplein</p></div>
                <img src="img/arrow-right.svg" class="arrowImg">
                <div><p class="station">Afstap:</p>
                    <p>Schiedam, Station Schiedam Centrum</p></div>
                <div class="legTypeIcon"><img src="{{ asset('img/icons/r_bus.svg') }}"></div>
            </div>
        </div>
    </div>
@endsection
