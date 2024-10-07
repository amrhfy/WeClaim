console.log('form_logic.js loaded and running');

////////////////////////////////////////////////////////////////////////
/////////////////// Google API Distance Calculation ////////////////////
////////////////////////////////////////////////////////////////////////

const totalDistanceElement = document.getElementById("total_distance");
const originInput = document.getElementById("origin");
const destinationInput = document.getElementById("destination");

const alternativeRouteButton = document.getElementById("alternative_route");

const originAutocomplete = new google.maps.places.Autocomplete(originInput);
const destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

document.addEventListener('DOMContentLoaded', function () {
    originInput.value = '';
    destinationInput.value = '';
});


function calculateDefaultRoute() {
    const origin = originInput.value;
    const destination = destinationInput.value;

    if (origin && destination) {
        const service = new google.maps.DistanceMatrixService();

        service.getDistanceMatrix(
            {
                origins: [origin],
                destinations: [destination],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC
            },
            (response, status) => {
                if (status === 'OK') {
                    const results = response.rows[0].elements;
                    if (results[0].status === 'OK') {
                        const distance = results[0].distance.text;

                        const numericDistance = parseFloat(distance.replace(/[^\d.]/g, ''));
                        totalDistanceElement.innerHTML = `${numericDistance.toFixed(2)} KM`;

                        document.getElementById('total_distance_input').value = numericDistance;
                    } else {
                        totalDistanceElement.innerHTML = 'Unable to calculate distance';
                    }
                } else {
                    console.log('Error:', status);
                    totalDistanceElement.innerHTML = 'Error calculating distance';
                }
            }
        );
    } else {
        
    }
}

originInput.addEventListener('change', calculateDefaultRoute);
destinationInput.addEventListener('change', calculateDefaultRoute);


originAutocomplete.addListener('place_changed', calculateDefaultRoute);
destinationAutocomplete.addListener('place_changed', calculateDefaultRoute);


////////////////////////////////////////////////////////////////////////
///////////////////// File Upload Progress Bar /////////////////////////
////////////////////////////////////////////////////////////////////////

const fileUpload = document.getElementById('toll_report');

function handleFileUpload(event) {
    const fileLabel = document.getElementById('file_label');
    const fileName = event.target.files[0] ? event.target.files[0].name : 'No File Selected';

    const progressContainer = document.getElementById('progress_container');
    const progressBar = document.getElementById('progress_bar');
    progressContainer.classList.remove('hidden');

    let progress = 0;

    const interval = setInterval(() => {
        if (progress >= 100) {
            clearInterval(interval);
            progressContainer.classList.add('hidden');
            fileLabel.textContent = fileName;
            
        } else {
            progress += 10;
            progressBar.style.width = `${progress}%`;
        }
    }, 200);
}

fileUpload.addEventListener('change', handleFileUpload);
