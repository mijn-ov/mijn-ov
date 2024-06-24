const legType = {
    "car": {
        emission: 153
    },
    "bus": {
        emission: 96
    },
    "tram": {
        emission: 35
    },
    "metro": {
        emission: 43
    },
    "trein": {
        emission: 17
    },
    "disclaimer":{
        text: "Onze data komt uit onderzoeken van het AD en data uit Google Maps"
    }
};

function getCO2(LegDurationInKM, type) {
    if (legType.hasOwnProperty(type)) {
        const emission = legType[type].emission;
        return LegDurationInKM * emission;
    } else {
        console.error('Invalid leg type');
        return 0;
    }
}

export const carTypes =
    [
        {
            "make": "Toyota",
            "model": "Camry",
            "co2_emissions_g_km": 125
        },
        {
            "make": "Toyota",
            "model": "Corolla",
            "co2_emissions_g_km": 120
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
            "co2_emissions_g_km": 0
        },
        {
            "make": "Tesla",
            "model": "Model S",
            "co2_emissions_g_km": 0
        },
        {
            "make": "Nissan",
            "model": "Leaf",
            "co2_emissions_g_km": 0
        },
        {
            "make": "Chevrolet",
            "model": "Bolt",
            "co2_emissions_g_km": 0
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


console.log(getCO2(254, "trein"));
