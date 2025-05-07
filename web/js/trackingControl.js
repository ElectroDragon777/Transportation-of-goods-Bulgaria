class TrackingStateManager {
    static KEY = 'courier_tracking_state';
    static setTracking(isTracking) {
        localStorage.setItem(this.KEY, JSON.stringify({
            isTracking: isTracking,
            timestamp: Date.now()
        }));
    }

    static isTracking() {
        const state = localStorage.getItem(this.KEY);
        if (state) {
            const {isTracking, timestamp} = JSON.parse(state);
            // Consider tracking active if started within last 24 hours
            if (Date.now() - timestamp < 24 * 60 * 60 * 1000) {
                return isTracking;
            }
        }
        return false;
    }

    static clearTracking() {
        localStorage.removeItem(this.KEY);
    }
}

class EnhancedTrackingControl {
    constructor() {
        this.map = null;
        this.currentMarker = null;
        this.destinationMarker = null;
        this.routeLayer = null;
        this.isTracking = TrackingStateManager.isTracking();
        this.watchId = null;
        this.updateInterval = null;
        this.activeOrders = [];
        this.currentDestination = null;
        
        this.init();
        this.updateGlobalIndicator();
    }

    init() {
        console.log('Initializing enhanced tracking control...');

        // Check if we're on the tracking page
        const isTrackingPage = document.getElementById('tracking-map') !== null;

        if (isTrackingPage) {
            this.fetchActiveOrders();
            this.initializeMap();
            this.initializeTrackingButton();
        } else {
            // For other pages, just initialize tracking button
            this.initializeTrackingButton();
        }

        // If tracking was active, restart it
        if (this.isTracking) {
            this.startTracking(true);
        }

        // Add page unload handler
        window.addEventListener('beforeunload', () => {
            // Only save tracking state if still tracking
            if (this.isTracking) {
                TrackingStateManager.setTracking(true);
            }
        });
    }

    fetchActiveOrders() {
        // Get active orders from the page if they exist in data attributes
        const orderContainer = document.getElementById('active-orders');
        if (orderContainer) {
            try {
                this.activeOrders = JSON.parse(orderContainer.dataset.orders || '[]');
                console.log('Active orders:', this.activeOrders);
                
                if (this.activeOrders.length > 0) {
                    this.currentDestination = this.activeOrders[0].end_destination;
                }
            } catch (e) {
                console.error('Error parsing active orders:', e);
                this.activeOrders = [];
            }
        }
    }

    initializeMap() {
        // Load Leaflet CSS
        <link rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin="" />
        console.log('Initializing map...');
        const mapElement = document.getElementById('tracking-map');

        //Load Leaflet JS
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

        //Load our custom map script AFTER Leaflet
        src="js/office-map.js";
        
        if (!mapElement) {
            console.error('Map element not found');
            return;
        }
        
        this.map = L.map('tracking-map').setView([42.7339, 25.4858], 7); // Center of Bulgaria
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.map);
        
        // Initialize with current position if available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    console.log('Got initial position:', position);
                    const {latitude, longitude} = position.coords;
                    this.map.setView([latitude, longitude], 13);
                    this.updateMarker(latitude, longitude);
                    
                    // If we have a destination, add it and calculate route
                    if (this.currentDestination) {
                        this.addDestinationMarker(this.currentDestination);
                        this.calculateAndDisplayRoute(latitude, longitude, this.currentDestination);
                    }
                },
                (error) => this.handleError('Error getting initial position: ' + error.message)
            );
        }
    }

    initializeTrackingButton() {
        const toggleButton = document.getElementById('toggle-tracking');
        const indicator = document.getElementById('tracking-indicator');
        
        if (toggleButton && indicator) {
            // Update initial button state
            this.updateTrackingUI(this.isTracking);

            toggleButton.addEventListener('click', () => {
                console.log('Toggle button clicked');
                this.toggleTracking();
            });
        }
    }

    updateTrackingUI(isTracking) {
        const toggleButton = document.getElementById('toggle-tracking');
        const indicator = document.getElementById('tracking-indicator');
        
        if (!toggleButton || !indicator) return;
        
        if (isTracking) {
            toggleButton.innerHTML = '<i class="mdi mdi-stop me-1"></i>Stop Tracking';
            toggleButton.classList.remove('btn-primary');
            toggleButton.classList.add('btn-danger');
            indicator.classList.remove('bg-secondary');
            indicator.classList.add('bg-success');
            indicator.textContent = 'Tracking Active';
        } else {
            toggleButton.innerHTML = '<i class="mdi mdi-crosshairs-gps me-1"></i>Start Tracking';
            toggleButton.classList.remove('btn-danger');
            toggleButton.classList.add('btn-primary');
            indicator.classList.remove('bg-success');
            indicator.classList.add('bg-secondary');
            indicator.textContent = 'Not tracking';
        }
    }

    startTracking(isRestore = false) {
        console.log('Starting tracking...');
        if (!navigator.geolocation) {
            this.handleError("Geolocation is not supported by your browser.");
            return;
        }

        this.watchId = navigator.geolocation.watchPosition(
            (position) => {
                const {latitude, longitude} = position.coords;

                // Only update marker if we're on the tracking page
                if (this.map && document.getElementById('tracking-map')) {
                    this.updateMarker(latitude, longitude);
                    
                    // If we have a destination, recalculate route
                    if (this.currentDestination) {
                        this.calculateAndDisplayRoute(latitude, longitude, this.currentDestination);
                    }
                }

                this.updateServerLocation(latitude, longitude);
            },
            (error) => this.handleError(error.message),
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );

        this.isTracking = true;

        // Update server tracking status
        this.updateTrackingStatus(true);

        if (!isRestore) {
            TrackingStateManager.setTracking(true);
        }

        this.updateGlobalIndicator();
        this.updateTrackingUI(true);
    }

    stopTracking() {
        console.log('Stopping tracking...');
        if (this.watchId) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
        }

        this.isTracking = false;
        
        // Update server tracking status
        this.updateTrackingStatus(false);
        
        TrackingStateManager.clearTracking();
        this.updateGlobalIndicator();
        this.updateTrackingUI(false);
    }

    toggleTracking() {
        console.log('Toggling tracking. Current state:', this.isTracking);
        
        if (!this.isTracking) {
            this.startTracking();
        } else {
            this.stopTracking();
        }
    }

    updateMarker(lat, lng) {
        console.log('Updating marker position:', {lat, lng});
        if (!this.map) return;
        
        if (!this.currentMarker) {
            this.currentMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<i class="mdi mdi-truck-fast text-primary" style="font-size: 24px;"></i>',
                    className: 'courier-marker',
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                })
            }).addTo(this.map);
        } else {
            this.currentMarker.setLatLng([lat, lng]);
        }
        this.map.setView([lat, lng], this.map.getZoom());
    }

    addDestinationMarker(destinationString) {
        if (!this.map) return;
        
        // Parse destination coordinates
        const destCoords = destinationString.split(',');
        if (destCoords.length !== 2) return;
        
        const destLat = parseFloat(destCoords[0]);
        const destLng = parseFloat(destCoords[1]);
        
        if (isNaN(destLat) || isNaN(destLng)) return;
        
        if (!this.destinationMarker) {
            this.destinationMarker = L.marker([destLat, destLng], {
                icon: L.divIcon({
                    html: '<i class="mdi mdi-map-marker text-danger" style="font-size: 24px;"></i>',
                    className: 'destination-marker',
                    iconSize: [24, 24],
                    iconAnchor: [12, 24]
                })
            }).addTo(this.map)
              .bindPopup("Destination");
        } else {
            this.destinationMarker.setLatLng([destLat, destLng]);
        }
    }

    calculateAndDisplayRoute(fromLat, fromLng, toDestination) {
        // Parse destination coordinates
        const destCoords = toDestination.split(',');
        if (destCoords.length !== 2) return;
        
        const toLat = parseFloat(destCoords[0]);
        const toLng = parseFloat(destCoords[1]);
        
        if (isNaN(toLat) || isNaN(toLng)) return;
        
        // Calculate route using OSRM
        const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLat}?overview=full&geometries=geojson`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const routeGeoJSON = route.geometry;
                    
                    // Remove previous route if exists
                    if (this.routeLayer) {
                        this.map.removeLayer(this.routeLayer);
                    }
                    
                    // Add new route
                    this.routeLayer = L.geoJSON(routeGeoJSON, {
                        style: {
                            color: "blue",
                            weight: 5,
                            opacity: 0.7
                        }
                    }).addTo(this.map);
                    
                    // Calculate distance and time
                    const distance = (route.distance / 1000).toFixed(1); // km
                    const duration = Math.round(route.duration / 60); // minutes
                    
                    // Update destination popup
                    if (this.destinationMarker) {
                        this.destinationMarker.setPopupContent(`Destination<br>
                            Distance: ${distance} km<br>
                            Estimated time: ${duration} min`);
                        this.destinationMarker.openPopup();
                    }
                    
                    // Fit map to show the entire route
                    this.map.fitBounds(this.routeLayer.getBounds(), {
                        padding: [50, 50]
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching route:", error);
            });
    }

    updateServerLocation(latitude, longitude) {
        console.log('Sending location update to server:', {latitude, longitude});

        $.ajax({
            url: 'index.php?controller=Courier&action=updateLocation',
            type: 'POST',
            dataType: 'json',
            data: {
                latitude: latitude,
                longitude: longitude
            },
            success: (data) => {
                console.log('Server response:', data);
                
                // Check if we've reached the destination
                if (data.destination_reached) {
                    this.handleDestinationReached(data.order_id);
                }
                
                if (data.status === 'error') {
                    this.handleError('Error updating location: ' + data.message);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('AJAX Error:', textStatus, errorThrown);
                this.handleError(`Error sending location update: ${textStatus} - ${errorThrown}`);
            }
        });
    }
    
    updateTrackingStatus(isTracking) {
        $.ajax({
            url: 'index.php?controller=Courier&action=toggleTracking',
            type: 'POST',
            dataType: 'json',
            data: {
                tracking: isTracking ? 1 : 0
            },
            success: (data) => {
                console.log('Tracking status update response:', data);
                
                if (data.status === 'error') {
                    this.handleError('Error updating tracking status: ' + data.message);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('AJAX Error:', textStatus, errorThrown);
                this.handleError(`Error updating tracking status: ${textStatus} - ${errorThrown}`);
            }
        });
    }

    handleDestinationReached(orderId) {
        console.log('Destination reached for order:', orderId);
        
        // Show notification
        alert('You have reached your destination! Order marked as delivered.');
        
        // Optionally refresh the page to update the order list
        window.location.reload();
    }

    handleError(message) {
        console.error('Error:', message);
        alert(message);
    }

    updateGlobalIndicator() {
        const globalIndicator = document.getElementById('global-tracking-indicator');
        if (globalIndicator) {
            if (this.isTracking) {
                if (globalIndicator.style.display !== 'flex') {
                    globalIndicator.style.display = 'flex';
                }
                globalIndicator.style.opacity = '1';
            } else {
                globalIndicator.style.opacity = '0';
                setTimeout(() => {
                    if (!this.isTracking) { // Check again in case it changed during timeout
                        globalIndicator.style.display = 'none';
                    }
                }, 300);
            }
        }
    }
}

// Initialize tracking control when document is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('Document ready, initializing enhanced tracking control...');
    // Always create the tracking control to handle tracking state
    const trackingControl = new EnhancedTrackingControl();

    const signOutElement = document.getElementById('sign-out');
    if (signOutElement) {
        signOutElement.addEventListener('click', (event) => {
            console.log('#sign-out clicked. Stopping tracking...');
            // Call the stopTracking method on the instance we created earlier
            trackingControl.stopTracking();
        });
    }
});