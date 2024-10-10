console.log('form.js loaded and running');

////////////////////////////////////////////////////////////////////////
/////////////////////////// Utilities //////////////////////////////////
////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////

const MAP_CONFIG = {
    initialZoom: 14,
    mapId: '9a9938cf82c50ad4',
    initialCenter: { lat: 3.0311070837055487, lng: 101.61629987586117 },
};

const MARKER_COLORS = ['#4285F4', '#DB4437', '#F4B400', '#0F9D58', '#AB47BC', '#00ACC1', '#FF7043', '#9E9E9E'];

////////////////////////////////////////////////////////////////////////

class FormManager {
    
    constructor() {
        this.locationCount = 1;
        this.markers = [];
        this.directionsService = null;
        this.directionsRenderer = null;
        this.geocodeCache = new Map();
        this.locationInputContainer = document.getElementById('location-input-container');
        this.addLocationBtn = document.getElementById('add-location-btn');
        this.removeLocationBtn = document.getElementById('remove-location-btn');
    }

    ///////////////////////////////////////////////////////////////////

    initMap() {
        this.map = new google.maps.Map(document.getElementById('map'), {
            center: MAP_CONFIG.initialCenter,
            zoom: MAP_CONFIG.initialZoom,
            disableDefaultUI: true,
            mapId: MAP_CONFIG.mapId,
        });

        this.directionsService = new google.maps.DirectionsService();
        this.directionsRenderer = new google.maps.DirectionsRenderer({
            polylineOptions: {
                strokeColor: '#4285F4',
                strokeWeight: 5
            },
            suppressMarkers: true,
            preserveViewport: true,
            routeIndex: 0,
        });

        this.directionsRenderer.setMap(this.map);

        this.initMapControls(); // Call this after map is initialized
    }

    ///////////////////////////////////////////////////////////////////

    initMapControls() {
        this.clearRouteBtn = document.createElement('button');
        this.clearRouteBtn.textContent = 'Clear Route';
        this.clearRouteBtn.className = 'btn-primary';
        this.clearRouteBtn.style.margin = '10px';
        this.clearRouteBtn.type = 'button';
        this.clearRouteBtn.disabled = true;
        this.clearRouteBtn.addEventListener('click', () => this.clearRoute());
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(this.clearRouteBtn);
    }

    ///////////////////////////////////////////////////////////////////

    clearInfoWindows() {
        this.infoWindows.forEach(infoWindow => infoWindow.close());
        this.infoWindows = [];
    }

    ///////////////////////////////////////////////////////////////////

    clearRoute() {
        this.directionsRenderer.setDirections({routes: []});
        const inputs = document.querySelectorAll('.location-input');
        inputs.forEach((input, index) => {
            if (index === 0) {
                input.value = '';
            } else {
                input.closest('.wgg-flex-col').remove();
            }
        });
        this.locationCount = 1;
        this.clearMarkers();
        if (this.routeInfoPanel) {
            this.map.controls[google.maps.ControlPosition.TOP_RIGHT].pop();
            this.routeInfoPanel = null;
        }
        this.clearRouteBtn.disabled = true;
        this.updateRemoveButtonState();
    
        this.map.setCenter(MAP_CONFIG.initialCenter);
        this.map.setZoom(MAP_CONFIG.initialZoom);
    }

    ///////////////////////////////////////////////////////////////////

    addMarker(location, number) {
        const color = MARKER_COLORS[number % MARKER_COLORS.length];
        const markerElement = this.createMarkerElement(color, number);

        const advancedMarker = new google.maps.marker.AdvancedMarkerElement({
            map: this.map,
            position: location,
            content: markerElement
        });

        this.markers.push(advancedMarker);
    }

    ///////////////////////////////////////////////////////////////////

    createMarkerElement(color, number) {
        const markerElement = document.createElement('div');
        markerElement.setAttribute('role', 'img');
        markerElement.setAttribute('aria-label', `Location marker ${number}`);
        markerElement.style.backgroundColor = color;
        markerElement.style.borderRadius = '50%';
        markerElement.style.width = '28px';
        markerElement.style.height = '28px';
        markerElement.style.display = 'flex';
        markerElement.style.alignItems = 'center';
        markerElement.style.justifyContent = 'center';
        markerElement.style.color = 'white';
        markerElement.style.fontWeight = 'bold';
        markerElement.textContent = number.toString();
        return markerElement;
    }

    ///////////////////////////////////////////////////////////////////

    clearMarkers() {
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = [];
    }

    ///////////////////////////////////////////////////////////////////

    updateLocationLabels() {
        document.querySelectorAll('.location-input').forEach((input, index) => {
            const label = input.previousElementSibling;
            label.textContent = `Location ${index + 1}`;
        });
    }

    ///////////////////////////////////////////////////////////////////

    makeLocationsDraggable() {
        new Sortable(this.locationInputContainer, {
            animation: 150,
            onEnd: () => {
                this.updateLocationLabels();
                this.updateMap();
            }
        });
    }

    ///////////////////////////////////////////////////////////////////

    attachInputListeners() {
        document.querySelectorAll('.location-input').forEach(input => {
            input.addEventListener('change', this.debounce(() => this.updateMap(), 500));
            input.addEventListener('input', () => this.validateLocationInput(input));
        });
    }

    ///////////////////////////////////////////////////////////////////

    async updateMap() {
        this.showLoading();
        this.clearMarkers();
        this.directionsRenderer.setDirections({routes: []});

        const inputs = document.querySelectorAll('.location-input');
        const waypoints = [];
        const bounds = new google.maps.LatLngBounds();

        let origin = null;
        let destination = null;

        try {
            for (let i = 0; i < inputs.length; i++) {
                const input = inputs[i];
                if (input.value) {
                    const location = await this.geocodeAddress(input.value);
                    if (location) {
                        this.addMarker(location, i + 1);
                        bounds.extend(location);

                        if (i === 0) {
                            origin = location;
                        } else if (i === inputs.length - 1) {
                            destination = location;
                        } else {
                            waypoints.push({
                                location: location,
                                stopover: true
                            });
                        }
                    }
                }
            }

            if (origin && destination) {
                const request = {
                    origin: origin,
                    destination: destination,
                    waypoints: waypoints,
                    travelMode: 'DRIVING'
                };

                const result = await this.directionsService.route(request);
                this.directionsRenderer.setDirections(result);
                this.map.fitBounds(bounds);
                this.addSegmentInfoBoxes(result.routes[0]);
                this.clearRouteBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error updating map:', error);
            this.showError('An error occurred while updating the map. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    ///////////////////////////////////////////////////////////////////

    addSegmentInfoBoxes(route) {
        if (this.routeInfoPanel) {
            this.map.controls[google.maps.ControlPosition.TOP_RIGHT].pop();
        }
        this.routeInfoPanel = this.createRouteInfoPanel(route);
        this.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(this.routeInfoPanel);
    }

    ///////////////////////////////////////////////////////////////////

    createRouteInfoPanel(route) {
        const panel = document.createElement('div');
        panel.className = 'route-info-panel';
        panel.style.backgroundColor = 'white';
        panel.style.margin = '10px';
        panel.style.padding = '10px';
        panel.style.borderRadius = '2px';
        panel.style.maxHeight = '300px';
        panel.style.overflowY = 'auto';
    
        for (let i = 0; i < route.legs.length; i++) {
            const leg = route.legs[i];
            const segment = document.createElement('div');
            segment.style.marginBottom = '10px';
            segment.innerHTML = `
                <strong>Point ${i + 1} - Point ${i + 2}</strong><br>
                Distance: ${leg.distance.text}<br>
                Duration: ${leg.duration.text}
            `;
            panel.appendChild(segment);
        }
    
        // Add total distance at the bottom
        const totalDistance = route.legs.reduce((total, leg) => total + leg.distance.value, 0);
        const totalDistanceKm = (totalDistance / 1000).toFixed(1);
        const totalDuration = route.legs.reduce((total, leg) => total + leg.duration.value, 0);
        const totalHours = Math.floor(totalDuration / 3600);
        const totalMinutes = Math.floor((totalDuration % 3600) / 60);
    
        const totalInfo = document.createElement('div');
        totalInfo.style.borderTop = '1px solid #ccc';
        totalInfo.style.paddingTop = '10px';
        totalInfo.style.marginTop = '10px';
        totalInfo.innerHTML = `
            <strong>Total Distance:</strong> ${totalDistanceKm} km<br>
            <strong>Total Duration:</strong> ${totalHours}h ${totalMinutes}m
        `;
        panel.appendChild(totalInfo);
    
        return panel;
    }

    ///////////////////////////////////////////////////////////////////

    createInfoBox(leg) {
        const distanceKm = (leg.distance.value / 1000).toFixed(1);
        const duration = leg.duration.text;
        
        const infoBox = document.createElement('div');
        infoBox.className = 'segment-info';
        infoBox.innerHTML = `
          <strong>${distanceKm} km</strong><br>
          ${duration}
        `;
        return infoBox;
    }
    
    ///////////////////////////////////////////////////////////////////

    getMidpoint(start, end) {
        return new google.maps.LatLng(
            (start.lat() + end.lat()) / 2,
            (start.lng() + end.lng()) / 2
        );
    }

    ///////////////////////////////////////////////////////////////////

    async geocodeAddress(address) {
        if (this.geocodeCache.has(address)) {
            return this.geocodeCache.get(address);
        }
        
        return new Promise((resolve) => {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    const location = results[0].geometry.location;
                    this.geocodeCache.set(address, location);
                    resolve(location);
                } else {
                    resolve(null);
                }
            });
        });
    }

    ///////////////////////////////////////////////////////////////////

    addLocation() {
        this.locationCount++;
        const newInput = document.createElement('div');
        newInput.classList.add(`location-${this.locationCount}`, 'wgg-flex-col', 'gap-2');
        newInput.innerHTML = `
            <label class="form-label cursor-grab">Location ${this.locationCount}</label>
            <input type="text" name="location[]" class="form-input location-input" placeholder="">
        `;
        this.locationInputContainer.appendChild(newInput);

        const newLocationInput = newInput.querySelector('.location-input');
        new google.maps.places.Autocomplete(newLocationInput);

        this.updateRemoveButtonState();
        this.attachInputListeners();
        this.updateMap();
    }

    ///////////////////////////////////////////////////////////////////

    removeLocation() {
        if (this.locationCount > 1) {
            this.locationInputContainer.removeChild(this.locationInputContainer.lastChild);
            this.locationCount--;
            this.updateRemoveButtonState();
            this.attachInputListeners();
            this.updateMap();
        }
    }

    ///////////////////////////////////////////////////////////////////

    updateRemoveButtonState() {
        if (this.locationCount > 1) {
            this.removeLocationBtn.disabled = false;
            this.removeLocationBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed', 'disabled:bg-gray-300');
        } else {
            this.removeLocationBtn.disabled = true;
            this.removeLocationBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed', 'disabled:bg-gray-300');
        }
    }

    ///////////////////////////////////////////////////////////////////

    showLoading() {
        const loadingElement = document.createElement('div');
        loadingElement.id = 'loading-indicator';
        loadingElement.textContent = 'Loading...';
        document.body.appendChild(loadingElement);
    }

    ///////////////////////////////////////////////////////////////////

    hideLoading() {
        const loadingElement = document.getElementById('loading-indicator');
        if (loadingElement) {
            loadingElement.remove();
        }
    }

    ///////////////////////////////////////////////////////////////////

    showError(message) {
        const errorElement = document.createElement('div');
        errorElement.id = 'error-message';
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        document.body.appendChild(errorElement);
        setTimeout(() => errorElement.remove(), 5000);
    }

    ///////////////////////////////////////////////////////////////////

    validateLocationInput(input) {
        if (input.value.trim() === '') {
            input.setCustomValidity('Please enter a location');
            return false;
        }
        input.setCustomValidity('');
        return true;
    }

    ///////////////////////////////////////////////////////////////////

    debounce(func, delay) {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }

    ///////////////////////////////////////////////////////////////////

    init() {
        this.initMap();
        this.makeLocationsDraggable();
        this.attachInputListeners();
        this.updateMap();

        this.addLocationBtn.addEventListener('click', () => this.addLocation());
        this.removeLocationBtn.addEventListener('click', () => this.removeLocation());

        const firstLocationInput = document.querySelector('.location-input');
        if (firstLocationInput) {
            new google.maps.places.Autocomplete(firstLocationInput);
        }
    }

    ///////////////////////////////////////////////////////////////////

}

///////////////////////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', () => {
    const formManager = new FormManager();
    formManager.init();
});

///////////////////////////////////////////////////////////////////////

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
