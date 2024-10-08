console.log('form_logic.js loaded and running');

////////////////////////////////////////////////////////////////////////
/////////////// Google API Places & Distance Calculation ///////////////
////////////////////////////////////////////////////////////////////////

let map;
let directionsService;
let directionsRenderer;
let currentRouteIndex = 0;

const totalDistanceElement = document.getElementById("total_distance");
const originInput = document.getElementById("origin");
const destinationInput = document.getElementById("destination");

const alternativeRouteButton = document.getElementById("alternative_route");

const originAutocomplete = new google.maps.places.Autocomplete(originInput);
const destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

document.addEventListener('DOMContentLoaded', function () {
    originInput.value = '';
    destinationInput.value = '';
    initMap();
});

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 4.2105, lng: 101.9758 }, // Center on Malaysia
        zoom: 7,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
}

function calculateDefaultRoute() {
    const origin = originInput.value;
    const destination = destinationInput.value;

    if (origin && destination) {
        directionsService.route(
            {
                origin: origin,
                destination: destination,
                travelMode: 'DRIVING',
            },
            (response, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    const route = response.routes[0];
                    const distanceInMeters = route.legs[0].distance.value;
                    const distanceInKm = (distanceInMeters / 1000).toFixed(2);
                    totalDistanceElement.innerHTML = `${distanceInKm} KM`;
                    document.getElementById('total_distance_input').value = distanceInKm;
                } else {
                    console.log('Error:', status);
                    totalDistanceElement.innerHTML = 'Error calculating distance';
                }
            }
        );
    }
}

function calculateAlternativeRoute() {
    const origin = originInput.value;
    const destination = destinationInput.value;

    if (origin && destination) {
        const service = new google.maps.DirectionsService();

        service.route(
            {
                origin: origin,
                destination: destination,
                travelMode: 'DRIVING',
                provideRouteAlternatives: true
            },
            (response, status) => {
                if (status === 'OK') {
                    // Increment the route index and loop back to 0 if we've reached the end
                    currentRouteIndex = (currentRouteIndex + 1) % response.routes.length;
                    
                    const selectedRoute = response.routes[currentRouteIndex];
                    
                    directionsRenderer.setDirections(response);
                    directionsRenderer.setRouteIndex(currentRouteIndex);

                    const distance = selectedRoute.legs[0].distance.text;
                    const numericDistance = parseFloat(distance.replace(/[^\d.]/g, ''));
                    
                    totalDistanceElement.innerHTML = `${numericDistance.toFixed(2)} KM`;
                    document.getElementById('total_distance_input').value = numericDistance;
                } else {
                    console.log('Error:', status);
                    totalDistanceElement.innerHTML = 'Error calculating alternative route';
                }
            }
        );
    }
}

originInput.addEventListener('change', calculateDefaultRoute);
destinationInput.addEventListener('change', calculateDefaultRoute);

originAutocomplete.addListener('place_changed', calculateDefaultRoute);
destinationAutocomplete.addListener('place_changed', calculateDefaultRoute);

alternativeRouteButton.addEventListener('click', calculateAlternativeRoute);

////////////////////////////////////////////////////////////////////////
////////////////// File Uploading Progress Bar Dummy ///////////////////
////////////////////////////////////////////////////////////////////////

function handleFileUpload(event, progressContainerId, progressBarId, fileLabelId) {
    const fileLabel = document.getElementById(fileLabelId);
    const fileName = event.target.files[0] ? event.target.files[0].name : 'No File Selected';

    const progressContainer = document.getElementById(progressContainerId);
    const progressBar = document.getElementById(progressBarId);
    progressContainer.classList.remove('hidden');

    // Simulate progress for demonstration purposes
    progressBar.style.width = '100%';
    setTimeout(() => {
        progressContainer.classList.add('hidden');
        fileLabel.textContent = fileName;
    }, 1000);
}

document.getElementById('toll_report').addEventListener('change', (event) => {
    handleFileUpload(event, 'toll_progress_container', 'toll_progress_bar', 'toll_file_label');
});

document.getElementById('email_report').addEventListener('change', (event) => {
    handleFileUpload(event, 'email_progress_container', 'email_progress_bar', 'email_file_label');
});
