@extends('layouts.app')

@vite(['resources/js/emissions.js'])

@section('content')
    <input id="routeDetails" value="{{$routeDetails}}" hidden="hidden">
    <input id="transitRoute" value="{{$publicRoute}}" hidden="hidden">
    <form style="width: 100vw; display: flex; justify-content: center">
        <select id="carType" onchange="reCalc()" name="carType"
                style="border: 1px solid #ababab; border-radius: 2px; filter: drop-shadow(2px 2px 5px rgb(204, 204, 204))">
            <option value="125 ; 15">Toyota Camry</option>
            <!-- Add more options here -->
        </select>
    </form>
    <section class="flex flex-col  items-center" style="height: 80vh">
        <div class="p-5 flex flex-row justify-between w-full md:w-1/2" style="height: 70vh">
            <div style="display: flex; flex-direction: row; align-items: center">
                <div class="emissions">
                    <img alt="user" src="{{ asset('img/icons/car.svg') }}">
                    <div class="emissions-circle circle-top emissions-user"></div>
                    <div class="emissions-line emissions-user"></div>
                    <div class="emissions-circle circle-bottom emissions-user"></div>
                </div>
                <div>
                    <p id="carEmissions" style="font-size: 1.1rem; font-weight: 700; color: #525252">Laden...</p>
                    <p id="carCost">Laden...</p>
                    <p id="carDuration">Laden...</p>
                </div>
            </div>
            <div style="display: flex; flex-direction: row-reverse; align-items: center">
                <div class="emissions">
                    <img alt="ov" src="{{ asset('img/icons/train.svg') }}">
                    <div class="emissions-circle circle-top emissions-ov"></div>
                    <div class="emissions-line emissions-ov"></div>
                    <div class="emissions-circle circle-bottom emissions-ov"></div>
                </div>
                <div style="text-align: right"><p id="ovEmissions"
                                                  style="font-size: 1.1rem; font-weight: 700; color: #525252">
                        Laden...</p>
                    <p id="ovCost">Niet beschikbaar</p>
                    <p id="ovDuration">Laden...</p>
                </div>
            </div>
        </div>

        <div class="help-box flex flex-row justify-between items-center p-3" style="transform: translateY(-48px)">
            <div class="flex flex-row gap-4">
                <a class="btn-outline-stylish" href="{{ route('chat') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                         fill="#000000">
                        <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/>
                    </svg>
                </a>
            </div>
        </div>
        <form action="{{ route('update.personal-emissions') }}" method="POST">
            @csrf
            <input id="ovEmissionsValue" name="ovEmissionsValue" value="6" type="hidden">
            <input id="carKm" name="carKm" value="6" type="hidden">
            <button type="submit" class="submitButton">Opslaan in emissies</button>
        </form>

    </section>
    <script defer="defer">
        const legType = {
            "car": {
                emission: 153
            },
            "bus": {
                emission: 96
            },
            "tram": {
                emission: 96
            },
            "metro": {
                emission: 96
            },
            "trein": {
                emission: 17
            },
            "disclaimer": {
                text: "Onze data komt uit onderzoeken van het AD en data uit Google Maps"
            }
        };
        const carTypes =
            [
                {
                    "make": "Toyota",
                    "model": "Corolla",
                    "co2_emissions_g_km": 120,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Toyota",
                    "model": "Starlet",
                    "co2_emissions_g_km": 162,
                    "kilometers_per_liter": 18
                },
                {
                    "make": "Honda",
                    "model": "Accord",
                    "co2_emissions_g_km": 130,
                    "kilometers_per_liter": 13
                },
                {
                    "make": "Honda",
                    "model": "Civic",
                    "co2_emissions_g_km": 110,
                    "kilometers_per_liter": 16
                },
                {
                    "make": "Ford",
                    "model": "F-150",
                    "co2_emissions_g_km": 250,
                    "kilometers_per_liter": 22
                },
                {
                    "make": "Ford",
                    "model": "Escape",
                    "co2_emissions_g_km": 170,
                    "kilometers_per_liter": 12
                },
                {
                    "make": "Chevrolet",
                    "model": "Silverado",
                    "co2_emissions_g_km": 260,
                    "kilometers_per_liter": 12
                },
                {
                    "make": "Chevrolet",
                    "model": "Malibu",
                    "co2_emissions_g_km": 150,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Nissan",
                    "model": "Altima",
                    "co2_emissions_g_km": 135,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Nissan",
                    "model": "Rogue",
                    "co2_emissions_g_km": 170,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Hyundai",
                    "model": "Elantra",
                    "co2_emissions_g_km": 127,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Hyundai",
                    "model": "Santa Fe",
                    "co2_emissions_g_km": 190,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Kia",
                    "model": "Sorento",
                    "co2_emissions_g_km": 180,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Kia",
                    "model": "Optima",
                    "co2_emissions_g_km": 130,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Volkswagen",
                    "model": "Jetta",
                    "co2_emissions_g_km": 130,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Volkswagen",
                    "model": "Golf",
                    "co2_emissions_g_km": 115,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Subaru",
                    "model": "Outback",
                    "co2_emissions_g_km": 150,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Subaru",
                    "model": "Forester",
                    "co2_emissions_g_km": 155,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Mazda",
                    "model": "CX-5",
                    "co2_emissions_g_km": 162,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Mazda",
                    "model": "3",
                    "co2_emissions_g_km": 120,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "BMW",
                    "model": "3 Series",
                    "co2_emissions_g_km": 140,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Mercedes-Benz",
                    "model": "C-Class",
                    "co2_emissions_g_km": 135,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Audi",
                    "model": "A4",
                    "co2_emissions_g_km": 150,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Tesla",
                    "model": "Model 3",
                    "co2_emissions_g_km": 54,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Tesla",
                    "model": "Model S",
                    "co2_emissions_g_km": 54,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Nissan",
                    "model": "Leaf",
                    "co2_emissions_g_km": 54,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Chevrolet",
                    "model": "Bolt",
                    "co2_emissions_g_km": 54,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Volvo",
                    "model": "XC60",
                    "co2_emissions_g_km": 170,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Lexus",
                    "model": "RX",
                    "co2_emissions_g_km": 165,
                    "kilometers_per_liter": 15
                },
                {
                    "make": "Toyota",
                    "model": "Highlander",
                    "co2_emissions_g_km": 175,
                    "kilometers_per_liter": 15
                }
            ]
        let route = "{{$routeDetails}}";
        let routes = route.split("^")
        let carEmissions;
        let totalCarEmissions = 0;
        let totalDistance = 0;
        let totalCarDistance = 0;
        let select = document.getElementById('carType')
        let ovObject = document.getElementById('transitRoute')
        console.log(routes[0])

        carTypes.forEach(car => {
            const option = document.createElement("option");
            option.value = `${car.co2_emissions_g_km} ; ${car.kilometers_per_liter}`;
            option.textContent = `${car.make} ${car.model}`;
            select.appendChild(option);
        });

        async function getData() {
            try {
                const apiKey = 'AIzaSyCnrZkJw8-k4KJRMFSk7jdIQ7tUYNqvGYY';
                const proxyUrl = 'https://api.allorigins.win/get?url=';
                const endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(routes[0])}&destination=${encodeURIComponent(routes[1])}&mode=transit&key=${apiKey}&language=nl`;

                const response = await fetch(proxyUrl + encodeURIComponent(endpoint));
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                const apiResponse = JSON.parse(data.contents); // Parse the contents field to get the actual API response


                const ovCost = document.getElementById('ovCost');
                const ovDuration = document.getElementById('ovDuration');
                const ovEmissions = document.getElementById('ovEmissions')
                let finalEmission = 0;
                for (let leg of apiResponse.routes[0].legs[0].steps) {
                    if (leg.travel_mode !== "WALKING") {

                        let name = (leg.transit_details.line.vehicle.name).toLowerCase();
                        if (name in legType) {
                            // Access the emission value
                            let emissionValue = legType[name].emission;
                            // Save the emission value as a different variable
                            finalEmission += (leg.distance.value / 1000) * emissionValue;
                        } else {
                            console.log(`No emission value found for "${name}"`);
                        }
                    }
                }
                console.log(finalEmission)
                ovDuration.innerHTML = `${apiResponse.routes[0].legs[0].duration.text}`;
                ovEmissions.innerHTML = `${Math.round(finalEmission / 10) / 100}Kg Co2`;
                let updateOvE = document.getElementById('ovEmissionsValue');
                updateOvE.value = finalEmission;
                console.log(updateOvE.value);
                if (apiResponse.status !== 'OK') {
                    throw new Error(`API Error: ${apiResponse.status}`);
                }
            } catch (error) {
                console.error('Error fetching directions:', error);
            }
            try {
                const apiKey = 'AIzaSyCnrZkJw8-k4KJRMFSk7jdIQ7tUYNqvGYY';
                const proxyUrl = 'https://api.allorigins.win/get?url=';
                const endpoint = `https://maps.googleapis.com/maps/api/directions/json?origin=${encodeURIComponent(routes[0])}&destination=${encodeURIComponent(routes[1])}&mode=driving&key=${apiKey}&language=nl`;

                const response1 = await fetch(proxyUrl + encodeURIComponent(endpoint));
                if (!response1.ok) {
                    throw new Error('Network response was not ok');
                }

                const data1 = await response1.json();
                const apiResponse1 = JSON.parse(data1.contents); // Parse the contents field to get the actual API response

                const carCost = document.getElementById('carCost');
                const carDuration = document.getElementById('carDuration');
                carEmissions = document.getElementById('carEmissions')
                console.log(apiResponse1)
                for (let leg of apiResponse1.routes[0].legs[0].steps) {
                    if (leg.travel_mode !== "WALKING") {

                        totalDistance += leg.distance.value;
                    }
                }
                console.log(totalDistance)
                totalEmissionsCar = totalDistance / 1000 * select.value.split(';')[0];
                let updateOvE = document.getElementById('carKm');
                updateOvE.value = totalDistance/1000;
                carEmissions.innerHTML = `${Math.round(totalEmissionsCar / 10) / 100}Kg Co2`;
                carDuration.innerHTML = `${apiResponse1.routes[0].legs[0].duration.text}`;
                console.log(select.value.split(';')[0])
                totalCarDistance = apiResponse1.routes[0].legs[0].distance.value;
                let cost = Math.round((totalCarDistance / 1000 / select.value.split(';')[1]) * 2.2);
                carCost.innerHTML = `$${cost}`;
                if (apiResponse1.status !== 'OK') {
                    throw new Error(`API Error: ${apiResponse1.status}`);
                }
            } catch (error) {
                console.error('Error fetching directions:', error);
            }
        }

        getData();

        function reCalc() {
            let newCarEmissions = document.getElementById('carEmissions')
            let carCost = document.getElementById('carCost');
            let totalCarEmissions = totalDistance / 1000 * select.value.split(';')[0];
            newCarEmissions.innerHTML = `${Math.round(totalCarEmissions / 10) / 100}Kg Co2`;
            console.log(newCarEmissions.innerHTML)
            let cost = Math.round((totalCarDistance / 1000 / select.value.split(';')[1]) * 2.2);
            carCost.innerHTML = `$${cost}`;
        }

    </script>
@endsection
