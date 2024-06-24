document.addEventListener('DOMContentLoaded', async function () {
    // Retrieve the arrays of origin and destination points from localStorage
    const mapInfo = JSON.parse(localStorage.getItem('mapInfoPoints'));


    const customMarker = document.createElement('img');
    customMarker.src = 'img/marker.png';

    // Define the function to create the map with multiple points
    async function initMap() {
        // The default location (in case Geolocation is not supported or denied)
        const defaultPosition = { lat: 52.3676, lng: 4.9041 };
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
        function initializeMap(position) {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: position,
                mapId: 'DEMO_MAP_ID',
            });

            map.setOptions({
                // Add any additional map options here
            });

            let directionsService = new google.maps.DirectionsService();
            let directionsRenderer = new google.maps.DirectionsRenderer({
                polylineOptions: {
                    strokeColor: '#FF0000', // Custom color
                    strokeOpacity: 0.7, // Custom opacity
                    strokeWeight: 4, // Custom thickness
                }
            });

            directionsRenderer.setMap(map);

            // Add a marker for the current location
            new google.maps.Marker({
                position: position,
                map: map,
                title: 'Current Location',
            });

            const origin = mapInfo[0].transit_details.departure_stop.name;
            const lastMapInfoIndex = mapInfo.length - 1;
            const destination = mapInfo[lastMapInfoIndex].transit_details.arrival_stop.name;

            let request = {
                origin: origin,
                destination: destination,
                travelMode: mapInfo[0].travel_mode,
            };

            directionsService.route(request, function (result, status) {
                if (status === "OK") {
                    directionsRenderer.setDirections(result);

                    // Custom markers for origin and destination
                    const originMarker = new google.maps.Marker({
                        position: result.routes[0].legs[0].start_location,
                        map: map,
                        icon: {
                            url: '../../img/mapMarker.svg',

                        }, // Change this to your custom SVG or image URL
                        title: 'Origin',
                    });

                    const destinationMarker = new google.maps.Marker({
                        position: result.routes[0].legs[0].end_location,
                        map: map,
                        icon: {
                            url: '../../img/mapMarker.svg',

                        }, // Change this to your custom SVG or image URL
                        title: 'Destination',
                    });
                }
            });
        }

        // Get the user's current position using the Geolocation API
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userPosition = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    initializeMap(userPosition);
                },
                () => {
                    // If geolocation fails, initialize the map with the default position
                    initializeMap(defaultPosition);
                }
            );
        } else {
            // If the browser doesn't support Geolocation, initialize the map with the default position
            initializeMap(defaultPosition);
        }
    }

    initMap();
});
