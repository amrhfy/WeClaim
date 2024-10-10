console.log('form.js loaded and running');

////////////////////////////////////////////////////////////////////////
/////////////////////////// Utilities //////////////////////////////////
////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////

let locationCount = 1;
let map;
let markers = [];
let directionsService;
let directionsRenderer;

////////////////////////////////////////////////////////////////////////

function debounce(func, delay) {
    let timeoutId;

    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

////////////////////////////////////////////////////////////////////////

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 3.072751,
            lng: 101.423454
        },
        zoom: 8,
        disableDefaultUI: true,
    });
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
}

////////////////////////////////////////////////////////////////////////

function addMarker(location) {
    const marker = new google.maps.Marker({
        position: location,
        map: map,
    });
    markers.push(marker);
}

////////////////////////////////////////////////////////////////////////

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

////////////////////////////////////////////////////////////////////////

function attachInputListeners() {
    document.querySelectorAll('.location-input').forEach(input => {
        input.addEventListener('change', debounce(updateMap, 500));
    });
}

////////////////////////////////////////////////////////////////////////

function updateMap() {

    clearMarkers();
    directionsRenderer.setDirections({routes: []});

    ////////////////////////////////////////////////////////////////////

    const inputs = document.querySelectorAll('.location-input');
    const waypoints = [];
    const bounds = new google.maps.LatLngBounds();

    let origin = null;
    let destination = null;

    ////////////////////////////////////////////////////////////////////

    const geocodePromises = Array.from(inputs).map((input, index) => {
        if (input.value) {
            return new Promise((resolve) => {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ address: input.value }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        const location = results[0].geometry.location;
                        addMarker(location);
                        bounds.extend(location);

                        if (index === 0) {
                            origin = location;
                        } else if (index === inputs.length - 1) {
                            destination = location;
                        } else {
                            waypoints.push({
                                location: location,
                                stopover: true
                            });
                        }
                    }
                    resolve();
                });
            });
        }
    });

    ////////////////////////////////////////////////////////////////////

    Promise.all(geocodePromises).then(() => {
        if (origin && destination) {
            const request = {
                origin: origin,
                destination: destination,
                waypoints: waypoints,
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                }
            });
        }
        map.fitBounds(bounds);
    });
}

////////////////////////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function() {
    initMap();
    markers = [];

    const addLocationBtn = document.getElementById('add-location-btn');
    const locationInputContainer = document.getElementById('location-input-container');
    const removeLocationBtn = document.getElementById('remove-location-btn');

    ////////////////////////////////////////////////////////////////////

    function updateRemoveButtonState() {
        if (locationCount > 1) {
            removeLocationBtn.disabled = false;
            removeLocationBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed', 'disabled:bg-gray-300');
        } else {
            removeLocationBtn.disabled = true;
            removeLocationBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed', 'disabled:bg-gray-300');
        }
    }

    ////////////////////////////////////////////////////////////////////

    addLocationBtn.addEventListener('click', function() {
        locationCount++;
        const newInput = document.createElement('div');
        newInput.classList.add(`location-${locationCount}`, 'wgg-flex-col', 'gap-2');
        newInput.innerHTML = `
            <label class="form-label">Location ${locationCount}</label>
            <input type="text" name="location[]" class="form-input location-input" placeholder="">
        `;
        locationInputContainer.appendChild(newInput);

        // Google Maps Auto Complete
        const newLocationInput = newInput.querySelector('.location-input');
        const autoCompleteNew = new google.maps.places.Autocomplete(newLocationInput);

        // Continue
        updateRemoveButtonState();
        attachInputListeners();
        updateMap();
    });

    ////////////////////////////////////////////////////////////////////

    document.querySelectorAll('.location-input').forEach(input => {
        input.addEventListener('change', updateMap);
    })

    ////////////////////////////////////////////////////////////////////

    removeLocationBtn.addEventListener('click', function(event) {
        if (locationCount > 1) {
            locationInputContainer.removeChild(locationInputContainer.lastChild);
            locationCount--;
            updateRemoveButtonState();
        }

        attachInputListeners();
        updateMap();
    });

    ////////////////////////////////////////////////////////////////////

    const firstLocationInput = document.querySelector('.location-input');
    if (firstLocationInput) {
        const autoComplete = new google.maps.places.Autocomplete(firstLocationInput);
    }

    ////////////////////////////////////////////////////////////////////

    attachInputListeners();
    updateMap();

    ////////////////////////////////////////////////////////////////////

});

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
