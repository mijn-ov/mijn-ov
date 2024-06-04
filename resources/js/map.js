document.addEventListener('DOMContentLoaded', async function () {
    // Retrieve the arrays of origin and destination points from localStorage
    const mapInfo = JSON.parse(localStorage.getItem('mapInfoPoints'));

    const customMarker = document.createElement('img');

    customMarker.src = 'img/marker.png';

    // Define the function to create the map with multiple points
    async function initMap() {
        // The location of Uluru
        const position = { lat: 52.3676, lng: 4.9041 };
        const apiKey = 'AIzaSyCnrZkJw8-k4KJRMFSk7jdIQ7tUYNqvGYY';
        // Load the Google Maps API
        const googleMaps = await new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initializeMap`;
            script.async = true;
            script.defer = true;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });



        // Initialize the map
        function initializeMap() {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: position,
                mapId: 'DEMO_MAP_ID',
            });

            map.setOptions({

            })
            let directionsService = new google.maps.DirectionsService();
            let directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            const origin = mapInfo[0].transit_details.departure_stop.name;
            const lastMapInfoIndex = mapInfo.length - 1;
            const destination = mapInfo[lastMapInfoIndex].transit_details.arrival_stop.name;

            let request = {
                origin:origin,
                destination:destination,
                travelMode:mapInfo[0].travel_mode,
            }
            directionsService.route(request,function(result,status){
                if(status === "OK") {
                    directionsRenderer.setDirections(result);

                }
            })
        }

        initializeMap();
    }

    initMap();
});
