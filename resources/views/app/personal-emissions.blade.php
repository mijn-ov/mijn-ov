@extends('layouts.app')
@vite([ 'resources/js/emissions.js' ])
@section('content')
    <!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>
    <style>
        .value-text {
            font-size: 2rem;
        }

        .progress-text {
            width: 200px;
            display: flex;
            text-align: center;
            align-items: center;
            justify-content: center;
        }

        body {
            overflow-x: hidden;
            overflow-y: scroll !important;
        }

        #progress {
            width: 220px;
            height: 220px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        legend {
            position: absolute;
            top: -18px; /* Adjust distance from top */
            left: -2px; /* Adjust distance from left */
            padding: 0 4px;
            z-index: 50;
            font-size: 0.8rem;
            color: #666; /* Adjust color */
        }
html{
    scroll-behavior: smooth;
}
        .value {
            font-size: 1.4rem;
            margin: 0 6px 0 0;
            font-weight: 600;
            color: #444444;
        }

        h2 {
            font-weight: 400;
            margin: 10px 0 0 0;
            color: #494949;
        }
    </style>
</head>
<body>
<div style="width: 100vw; height: 100vh; display: flex; align-items: center; flex-direction: column;">
    <h1 style="font-weight: 600; margin: 10px 0 30px 0; color: #494949;">Persoonlijke uitstoot</h1>

    <form style="position: relative; z-index: 40">
        <fieldset style="border: 1px solid #c9c9c9; border-radius: 2px;">
            <legend>Type auto</legend>
            <label for="carType"></label>
            <select id="carType" onchange="reCalc()" name="carType"
                    style="border: 1px solid #ababab; border-radius: 2px; filter: drop-shadow(2px 2px 5px rgb(204, 204, 204))">
                <option value="125">Toyota Camry</option>
            </select>
        </fieldset>
        <div
            style="display: flex; flex-direction: row; color: #464646; justify-content: space-between; align-items: center">
            <div><p style="font-weight: 800; color: #484848;">OV:</p>
                <p style="transform: translateY(-5px)">{{round($ovEmissions/1000)}}Kg Co2</p></div>
            <p>></p>
            <div><p style="font-weight: 800; color: #484848;">Auto:</p>
                <p id="carEmissions" style="transform: translateY(-5px)"></p></div>
        </div>
    </form>
    <div id="progress" style="z-index: 40"></div>
    <h2>Dan bespaart u evenveel als:</h2>
    <div>
        <div style="display: flex; flex-direction: row; align-items: center; transform: translateX(-5px)">
            <img src="{{ asset('img/icons/tree.svg')}}" style="width: 50px; height: 50px;">
            <span class="value" id="trees" akhi="500">0</span>
            <p> Bomen per dag absorberen</p>
        </div>
        <div style="display: flex; flex-direction: row; align-items: center; transform: translateX(-5px); justify-content: flex-start">
            <img src="{{ asset('img/icons/bottle.svg')}}" style="width: 50px; height: 50px;">
            <span class="value" id="bags" akhi="500">0</span>
            <p> water flesjes aan co2 uitstoot</p>
        </div>
        <div style="display: flex; flex-direction: row; align-items: center; transform: translateX(-5px); justify-content: flex-start">
            <img src="{{ asset('img/icons/light.svg')}}" style="width: 50px; height: 50px;">
            <span class="value" id="light" akhi="500">0</span>
            <div>
                <p> uur aan electriciteit in huis</p>
            </div>
        </div>
    </div>
    <div id="data" style="padding-bottom: 100px; margin-top: 50px; width: 70vw; display: flex; flex-direction: column; align-items: center; text-align: center">
        <h2>Onze berekeningen</h2>
        <p>Onze data komt vanuit het Ad, Eneco en Google maps.
            Onze berekeningen doen wij op basis van data over de gemiddelde uitstoot van een voertuig. Hierin wordt geen
            rekening gehouden met de snelheid, het optrekken of remmen van de auto.
        <br><br>
        Om te berekenen hoeveel kg Co2 u met de auto uit zou stoten gebruiken wij een gegeven routebeschrijving uit Google Maps en berekenen wij daarvan het aantal kilometers met gegevens uit de Rijksdienst voor het wegverkeer.
            In onderstaande bron is ook meer informatie over hoe wij aan onze data komen voor electrische autos.
            Het aantal uren aan electriciteit is op basis van een éénpersoonswoning<br><br></p>
        <a href="https://eneco.be/nl/energieverbruik/elektriciteit#:~:text=Een%20%C3%A9%C3%A9npersoonshuishouden%20gebruikt%20gemiddeld%20zo,meter%20gemiddelde%203500kWh%20op%20jaarbasis." style="text-decoration: underline">Electriciteitgebruik, Eneco</a>
        <a href="https://www.rdw.nl/" style="text-decoration: underline">Uitstoot autos, Rijksdienst van het wegverkeer</a>
        <a href="https://www.milieucentraal.nl/duurzaam-vervoer/co2-uitstoot-fiets-ov-en-auto/" style="text-decoration: underline">Uitstoot door ov, Milieu centraal</a>
        <a href="https://www.milieucentraal.nl/duurzaam-vervoer/elektrische-auto/" style="text-decoration: underline">Uitstoot door electrische autos, Milieu centraal</a>
    </div>
</div>

<script defer="defer">
    const carTypes =
        [
            {
                "make": "Toyota",
                "model": "Corolla",
                "co2_emissions_g_km": 120
            },
            {
                "make": "Toyota",
                "model": "Starlet",
                "co2_emissions_g_km": 162
            },
            {
                "make": "Honda",
                "model": "Accord",
                "co2_emissions_g_km": 130
            },
            {
                "make": "Honda",
                "model": "Civic",
                "co2_emissions_g_km": 110
            },
            {
                "make": "Ford",
                "model": "F-150",
                "co2_emissions_g_km": 250
            },
            {
                "make": "Ford",
                "model": "Escape",
                "co2_emissions_g_km": 170
            },
            {
                "make": "Chevrolet",
                "model": "Silverado",
                "co2_emissions_g_km": 260
            },
            {
                "make": "Chevrolet",
                "model": "Malibu",
                "co2_emissions_g_km": 150
            },
            {
                "make": "Nissan",
                "model": "Altima",
                "co2_emissions_g_km": 135
            },
            {
                "make": "Nissan",
                "model": "Rogue",
                "co2_emissions_g_km": 170
            },
            {
                "make": "Hyundai",
                "model": "Elantra",
                "co2_emissions_g_km": 127
            },
            {
                "make": "Hyundai",
                "model": "Santa Fe",
                "co2_emissions_g_km": 190
            },
            {
                "make": "Kia",
                "model": "Sorento",
                "co2_emissions_g_km": 180
            },
            {
                "make": "Kia",
                "model": "Optima",
                "co2_emissions_g_km": 130
            },
            {
                "make": "Volkswagen",
                "model": "Jetta",
                "co2_emissions_g_km": 130
            },
            {
                "make": "Volkswagen",
                "model": "Golf",
                "co2_emissions_g_km": 115
            },
            {
                "make": "Subaru",
                "model": "Outback",
                "co2_emissions_g_km": 150
            },
            {
                "make": "Subaru",
                "model": "Forester",
                "co2_emissions_g_km": 155
            },
            {
                "make": "Mazda",
                "model": "CX-5",
                "co2_emissions_g_km": 162
            },
            {
                "make": "Mazda",
                "model": "3",
                "co2_emissions_g_km": 120
            },
            {
                "make": "BMW",
                "model": "3 Series",
                "co2_emissions_g_km": 140
            },
            {
                "make": "Mercedes-Benz",
                "model": "C-Class",
                "co2_emissions_g_km": 135
            },
            {
                "make": "Audi",
                "model": "A4",
                "co2_emissions_g_km": 150
            },
            {
                "make": "Tesla",
                "model": "Model 3",
                "co2_emissions_g_km": 54
            },
            {
                "make": "Tesla",
                "model": "Model S",
                "co2_emissions_g_km": 54
            },
            {
                "make": "Nissan",
                "model": "Leaf",
                "co2_emissions_g_km": 54
            },
            {
                "make": "Chevrolet",
                "model": "Bolt",
                "co2_emissions_g_km": 54
            },
            {
                "make": "Volvo",
                "model": "XC60",
                "co2_emissions_g_km": 170
            },
            {
                "make": "Lexus",
                "model": "RX",
                "co2_emissions_g_km": 165
            },
            {
                "make": "Toyota",
                "model": "Highlander",
                "co2_emissions_g_km": 175
            }
        ]

    const select = document.getElementById("carType");
    const treeStats = document.getElementById('trees');
    const carEmission = document.getElementById('carEmissions')
    treeStats.akhi = "200";

    // Populate the select options with car types
    carTypes.forEach(car => {
        const option = document.createElement("option");
        option.value = `${car.co2_emissions_g_km}`;
        option.textContent = `${car.make} ${car.model}`;
        select.appendChild(option);
    });

    var bar = new ProgressBar.Circle('#progress', {
        color: '#494949',
        strokeWidth: 10,
        trailWidth: 3,
        easing: 'easeInOut',
        duration: 1400,
        text: {
            autoStyleContainer: false
        },
        from: {color: '#fa0000', width: 3},
        to: {color: '#CB4793FF', width: 3},
        step: function (state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);
            circle.path.setAttribute('stroke-linecap', 'round');

            var value = Math.round(circle.value() * 100);
            if (value === 0) {
                circle.setText('');
            } else {
                circle.setText('<div class="progress-text"><span class="value-text">' + value + '%</span><a href="#data" style="font-size: 0.9rem; width: 150px; text-align: center">Minder uitstoot door het ov te pakken in plaats van de auto 🛈</span></div>');
            }
        }
    });
    bar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
    bar.text.style.fontSize = '1rem';
    bar.text.style.textAlign = "center";

    bar.animate(({{$km_driven}} * select.value - {{$ovEmissions}}) / ({{$km_driven}} * select.value));  // Number from 0.0 to 1.0
    carEmission.innerHTML = `${Math.round(({{$km_driven}} * select.value) / 1000)}kg Co2`;
    const counters = document.querySelectorAll('.value');
    const speed = 100;

    // Function to animate a specific value on an element by its ID
    function animate(value, elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const data = +element.innerText;
        const difference = value - data;
        const step = difference / speed;

        let current = data;
        const updateValue = () => {
            current += step;
            element.innerText = Math.round(current);

            if ((step > 0 && current < value) || (step < 0 && current > value)) {
                requestAnimationFrame(updateValue);
            } else {
                element.innerText = value;
            }
        };

        updateValue();
    }

    animate(`${Math.round(((select.value * {{$km_driven}}) - {{$ovEmissions}}) / 100)}`, "trees");
    animate(`${Math.round(((select.value * {{$km_driven}}) - {{$ovEmissions}}) / 120)}`, "bags");
    animate(`${Math.round(((select.value * {{$km_driven}}) - {{$ovEmissions}}) / 150)}`, "light");

    function reCalc() {
        bar.animate(({{$km_driven}} * select.value - {{$ovEmissions}}) / ({{$km_driven}} * select.value));
        animate(`${Math.round(((select.value * {{$km_driven}}) - {{$ovEmissions}}) / 27)}`, "trees");
        animate(`${Math.round(((select.value * {{$km_driven}}) - {{$ovEmissions}}) / 87)}`, "bags");
        animate(`${Math.round(((select.value * {{$km_driven}}) - {{$ovEmissions}}) / 50)}`, "light");
        carEmission.innerHTML = `${Math.round(({{$km_driven}} * select.value) / 1000)}kg Co2`;
    }

    function toggleOverlay() {

    }

</script>
</body>
</html>
@endsection
