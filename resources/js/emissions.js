const legType = {
    "car": {
        emission: 153
    },
    "bus": {
        emission: 116
    },
    "tram": {
        emission: 96
    },
    "metro": {
        emission: 96
    },
    "trein": {
        emission: 2
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


console.log(getCO2(254, "trein"));
