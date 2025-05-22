<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                           aria-controls="overview" aria-selected="true">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tracking-tab" data-bs-toggle="tab" href="#tracking" role="tab"
                           aria-selected="false">Courier Tracking</a>
                    </li>
                </ul>
            </div>

            <!-- Tracking Status Panel -->
            <?php if ($_SESSION['user']['role'] === 'courier'): ?>
                <div class="tracking-status-panel mt-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Your Tracking Status</h6>
                            <div class="d-flex align-items-center gap-3">
                                <div id="tracking-indicator" class="badge <?= isset($isTrackingEnabled) && $isTrackingEnabled ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= isset($isTrackingEnabled) && $isTrackingEnabled ? 'Tracking Enabled' : 'Tracking Disabled' ?>
                                </div>
                                <button id="toggle-tracking" class="btn <?= isset($isTrackingEnabled) && $isTrackingEnabled ? 'btn-danger' : 'btn-success' ?>">
                                    <i class="mdi mdi-crosshairs-gps me-1"></i>
                                    <?= isset($isTrackingEnabled) && $isTrackingEnabled ? 'Stop Tracking' : 'Start Tracking' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="tab-content tab-content-basic">
                <!-- Home -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <!-- Office Locations -->
                    <div class="card shadow-sm grid-margin stretch-card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Office Locations</h5>
                            <div class="row">
                                <div class="col-md-4 d-flex flex-column">
                                    <div class="card shadow-sm mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">From Office</h5>
                                            <select class="form-select" id="fromOffice">
                                                <option value="">Select Office</option>
                                                <?php
                                                $offices = [
                                                    ["city" => "Sofia", "lat" => 42.6977, "lng" => 23.3219],
                                                    ["city" => "Plovdiv", "lat" => 42.1482, "lng" => 24.7494],
                                                    ["city" => "Varna", "lat" => 43.2141, "lng" => 27.9147],
                                                    ["city" => "Burgas", "lat" => 42.5061, "lng" => 27.4678],
                                                    ["city" => "Ruse", "lat" => 43.8545, "lng" => 25.9681],
                                                    ["city" => "Stara Zagora", "lat" => 42.4226, "lng" => 25.6347],
                                                    ["city" => "Pleven", "lat" => 43.4114, "lng" => 24.6158],
                                                    ["city" => "Sliven", "lat" => 42.6784, "lng" => 26.3245],
                                                    ["city" => "Yambol", "lat" => 42.4854, "lng" => 26.5060],
                                                    ["city" => "Haskovo", "lat" => 41.9341, "lng" => 25.5560],
                                                    ["city" => "Shumen", "lat" => 43.2761, "lng" => 26.9350],
                                                    ["city" => "Pernik", "lat" => 42.6038, "lng" => 23.0342],
                                                    ["city" => "Dobrich", "lat" => 43.5606, "lng" => 27.8284],
                                                    ["city" => "Pazardzhik", "lat" => 42.1994, "lng" => 24.3317],
                                                    ["city" => "Blagoevgrad", "lat" => 42.0227, "lng" => 23.0906],
                                                    ["city" => "Veliko Tarnovo", "lat" => 43.0757, "lng" => 25.6172],
                                                    ["city" => "Gabrovo", "lat" => 42.8764, "lng" => 25.3259],
                                                    ["city" => "Vratsa", "lat" => 43.2048, "lng" => 23.5510],
                                                    ["city" => "Kazanlak", "lat" => 42.6205, "lng" => 25.4093],
                                                    ["city" => "Vidin", "lat" => 43.9935, "lng" => 22.8724],
                                                    ["city" => "Montana", "lat" => 43.4127, "lng" => 23.2357],
                                                    ["city" => "Kardzhali", "lat" => 41.6446, "lng" => 25.3649],
                                                    ["city" => "Lovech", "lat" => 43.1304, "lng" => 24.7153],
                                                    ["city" => "Silistra", "lat" => 44.1189, "lng" => 27.2758],
                                                    ["city" => "Targovishte", "lat" => 43.2500, "lng" => 26.5700],
                                                    ["city" => "Razgrad", "lat" => 43.5333, "lng" => 26.5167]
                                                ];
                                                foreach ($offices as $office):
                                                    ?>
                                                    <option value="<?= $office['lat'] . ',' . $office['lng'] ?>">
                                                        <?= htmlspecialchars($office['city']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card shadow-sm mt-auto">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">To Office</h5>
                                            <select class="form-select" id="toOffice">
                                                <option value="">Select Office</option>
                                                <?php foreach ($offices as $office): ?>
                                                    <option value="<?= $office['lat'] . ',' . $office['lng'] ?>">
                                                        <?= htmlspecialchars($office['city']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">Office Locations Map</h5>
                                            <!-- Load Leaflet CSS -->
                                            <link rel="stylesheet"
                                                  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                                                  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
                                                  crossorigin="" />

                                            <!-- Map container -->
                                            <div id="map-container" style="height: 400px; width: 100%;">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading map...</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Load Leaflet JS -->
                                            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                                                    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                                            crossorigin=""></script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courier's Current Location Map (only for couriers) -->
                    <?php if ($_SESSION['user']['role'] === 'courier'): ?>
                        <div class="card shadow-sm grid-margin stretch-card mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Your Current Location</h5>
                                <p class="card-description">This shows your real-time location when tracking is enabled</p>

                                <!-- Dedicated courier location map -->
                                <div id="courier-location-map" style="height: 400px; width: 100%; border-radius: 8px; margin-top: 15px;">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading map...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 text-center">
                                    <p id="location-status">Waiting for location data...</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tracking Tab -->
                <div class="tab-pane fade" id="tracking" role="tabpanel" aria-labelledby="tracking-tab">
                    <div class="card shadow-sm grid-margin stretch-card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Courier Tracking</h5>

                            <!-- Courier/Order Selection (for admins/dispatchers) -->
                            <?php if ($_SESSION['user']['role'] !== 'courier'): ?>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Courier</label>
                                        <select class="form-select" id="courier-select">
                                            <option value="">Select Courier</option>
                                            <?php if (isset($couriers) && is_array($couriers)): ?>
                                                <?php foreach ($couriers as $courier): ?>
                                                    <option value="<?= $courier['id'] ?>"><?= htmlspecialchars($courier['name']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Select Order</label>
                                        <select class="form-select" id="order-select">
                                            <option value="">Select Order</option>
                                            <?php if (isset($activeOrders) && is_array($activeOrders)): ?>
                                                <?php foreach ($activeOrders as $order): ?>
                                                    <option value="<?= $order['id'] ?>"><?= htmlspecialchars($order['product_name']) ?> (<?= $order['tracking_number'] ?>)</option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- For couriers - show their active orders -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Your Active Order</label>
                                        <select class="form-select" id="order-select">
                                            <option value="">Select Order</option>
                                            <?php if (isset($activeOrders) && is_array($activeOrders)): ?>
                                                <?php foreach ($activeOrders as $order): ?>
                                                    <option value="<?= $order['id'] ?>"><?= htmlspecialchars($order['product_name']) ?> (<?= $order['tracking_number'] ?>)</option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Tracking info display -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-title">Tracking Information</h6>
                                            <div id="tracking-info">
                                                <div class="alert alert-info">
                                                    Select an order to see tracking information
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-title">Tracking Map</h6>
                                            <!-- Tracking Map container -->
                                            <div id="tracking-map-container" style="height: 400px; width: 100%;">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <div class="alert alert-info m-0">
                                                        Select an order to display the tracking map
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No Active Orders Panel for Couriers -->
                            <?php if ($_SESSION['user']['role'] === 'courier' && empty($activeOrders)): ?>
                                <div class="card shadow-sm mb-4 mt-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Tracking Status</h6>
                                        <div class="alert alert-info">
                                            <p><i class="mdi mdi-information-outline me-2"></i> You don't have any active orders to track. Your location will still be tracked, but is not associated with any deliveries.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Office Locations -->
<script>
    // office-map.js - Custom map functionality
    (function () {
        // Wait for both document and Leaflet library to be ready
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Leaflet is loaded
            if (typeof L === 'undefined') {
                console.error("Leaflet library not loaded yet!");

                // Set an interval to check if Leaflet becomes available
                const leafletCheckInterval = setInterval(function () {
                    if (typeof L !== 'undefined') {
                        clearInterval(leafletCheckInterval);
                        console.log("Leaflet found, initializing map...");
                        initializeMap();
                    }
                }, 100);

                // Set a timeout to give up after 5 seconds
                setTimeout(function () {
                    clearInterval(leafletCheckInterval);
                    const mapContainer = document.getElementById('map-container');
                    if (mapContainer) {
                        mapContainer.innerHTML = '<div class="alert alert-danger">Failed to initialize map: Leaflet library could not be loaded. Please check your internet connection and refresh the page.</div>';
                    }
                }, 5000);
            } else {
                // Leaflet already available, initialize immediately
                setTimeout(initializeMap, 100);
            }
        });

        // Global variables to track state
        let officeMap = null;
        let currentRouteLayer = null;
        let fromMarker = null;
        let toMarker = null;

        function initializeMap() {
            console.log("Initializing map...");
            const mapContainer = document.getElementById('map-container');

            if (!mapContainer) {
                console.error("Map container not found!");
                return;
            }

            // Clear any existing content
            mapContainer.innerHTML = '';

            // Make sure the container is visible and sized correctly
            mapContainer.style.height = '400px';
            mapContainer.style.width = '100%';
            mapContainer.style.display = 'block';
            mapContainer.style.position = 'relative'; // Ensure proper positioning
            mapContainer.style.zIndex = '1'; // Ensure proper stacking

            try {
                // Create map instance
                officeMap = L.map('map-container', {
                    zoomControl: true,
                    attributionControl: true,
                    minZoom: 5,
                    maxZoom: 18
                }).setView([42.7339, 25.4858], 7);

                // Add OpenStreetMap tiles with explicit parameters
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    subdomains: 'abc', // Specifying explicit subdomains
                    tileSize: 256,
                    maxZoom: 19
                }).addTo(officeMap);

                // Force a redraw of the map
                setTimeout(function () {
                    officeMap.invalidateSize(true);
                    console.log("Map size invalidated (forced refresh)");

                    // After invalidating size, show all offices
                    showAllOffices();
                }, 200);

                console.log("Map initialized successfully");

                // Set up event listeners for dropdowns
                setupEventListeners();

            } catch (e) {
                console.error("Error during map initialization:", e);
                mapContainer.innerHTML = '<div class="alert alert-danger">Failed to initialize map: ' + e.message + '</div>';
            }
        }

        function setupEventListeners() {
            const fromOfficeEl = document.getElementById('fromOffice');
            const toOfficeEl = document.getElementById('toOffice');

            if (fromOfficeEl) {
                fromOfficeEl.addEventListener('change', generateMap);
            }

            if (toOfficeEl) {
                toOfficeEl.addEventListener('change', generateMap);
            }

            // Handle window resize
            window.addEventListener('resize', function () {
                if (officeMap) {
                    officeMap.invalidateSize(true);
                }
            });
        }

        // Function to display all office locations on the map
        function showAllOffices() {
            console.log("Showing all offices");
            const fromOfficeSelect = document.getElementById('fromOffice');
            if (!fromOfficeSelect || !officeMap) {
                console.error("fromOffice select or map not found");
                return;
            }

            const markers = [];

            // Loop through all office options (skipping the first placeholder)
            for (let i = 1; i < fromOfficeSelect.options.length; i++) {
                const option = fromOfficeSelect.options[i];
                const [lat, lng] = option.value.split(',').map(parseFloat);
                const cityName = option.text;

                // Add marker for each office
                try {
                    const marker = L.marker([lat, lng])
                            .addTo(officeMap)
                            .bindPopup(cityName);

                    markers.push(marker);
                } catch (e) {
                    console.error("Error adding marker for " + cityName + ":", e);
                }
            }

            // Auto-zoom to fit all markers
            if (markers.length > 0) {
                try {
                    const group = new L.featureGroup(markers);
                    officeMap.fitBounds(group.getBounds(), {
                        padding: [30, 30], // Add some padding
                        maxZoom: 10        // Limit max zoom level
                    });
                } catch (e) {
                    console.error("Error fitting bounds:", e);
                }
            }
        }

        function generateMap() {
            console.log("generateMap called");

            // Check if map is initialized
            if (!officeMap) {
                console.error("Map not initialized when selections changed");
                initializeMap();
                // Set a timeout and try again after initialization
                setTimeout(generateMap, 500);
                return;
            }

            const fromOfficeSelect = document.getElementById('fromOffice');
            const toOfficeSelect = document.getElementById('toOffice');
            const fromOffice = fromOfficeSelect.value;
            const toOffice = toOfficeSelect.value;

            // Force a redraw of the map first
            officeMap.invalidateSize(true);

            // Remove previous markers and route
            try {
                if (fromMarker)
                    officeMap.removeLayer(fromMarker);
                if (toMarker)
                    officeMap.removeLayer(toMarker);
                if (currentRouteLayer)
                    officeMap.removeLayer(currentRouteLayer);

                // Clear all existing markers
                officeMap.eachLayer(function (layer) {
                    if (layer instanceof L.Marker) {
                        officeMap.removeLayer(layer);
                    }
                });
            } catch (e) {
                console.error("Error clearing previous markers:", e);
            }

            if (fromOffice && toOffice) {
                console.log("Both offices selected:", fromOffice, "to", toOffice);
                const [fromLat, fromLng] = fromOffice.split(',').map(parseFloat);
                const [toLat, toLng] = toOffice.split(',').map(parseFloat);

                try {
                    // Add markers for selected offices
                    fromMarker = L.marker([fromLat, fromLng], {
                        icon: L.icon({
                            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                            shadowSize: [41, 41]
                        })
                    }).addTo(officeMap)
                            .bindPopup("From: " + fromOfficeSelect.options[fromOfficeSelect.selectedIndex].text)
                            .openPopup();

                    toMarker = L.marker([toLat, toLng], {
                        icon: L.icon({
                            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                            shadowSize: [41, 41]
                        })
                    }).addTo(officeMap)
                            .bindPopup("To: " + toOfficeSelect.options[toOfficeSelect.selectedIndex].text);

                    // Fit map to show both markers with padding
                    const bounds = L.latLngBounds([
                        [fromLat, fromLng],
                        [toLat, toLng]
                    ]);
                    officeMap.fitBounds(bounds, {
                        padding: [50, 50],
                        maxZoom: 10
                    });

                    // Calculate and display route using OSRM
                    getRoute(fromLat, fromLng, toLat, toLng);
                } catch (e) {
                    console.error("Error adding markers or adjusting view:", e);
                }
            } else if (!fromOffice && !toOffice) {
                console.log("No offices selected, showing all");
                // If both selections are cleared, show all offices again
                showAllOffices();
            }
        }

        // Function to get and display route between two points
        function getRoute(fromLat, fromLng, toLat, toLng) {
            console.log("Getting route between points");
            // Using OSRM demo server
            const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLat}?overview=full&geometries=geojson`;

            console.log("OSRM URL:", url); // Debug: Log the OSRM URL

            // Show loading indication
            if (toMarker) {
                toMarker.setPopupContent("Loading route information...");
                toMarker.openPopup();
            }

            fetch(url)
                    .then(response => {
                        console.log("OSRM Response:", response); // Debug: Log the response
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.dir("Route data received:", data); // Debug: Log the data
                        if (data.routes && data.routes.length > 0) {
                            const route = data.routes[0];
                            const routeGeoJSON = route.geometry;

                            console.dir("Route GeoJSON:", routeGeoJSON); // Debug: Log GeoJSON

                            // Create route layer with styling
                            try {
                                // Remove previous route layer if it exists
                                if (currentRouteLayer) {
                                    officeMap.removeLayer(currentRouteLayer);
                                }

                                currentRouteLayer = L.geoJSON(routeGeoJSON, {
                                    style: {
                                        color: "blue",
                                        weight: 5,
                                        opacity: 0.7
                                    }
                                }).addTo(officeMap);

                                console.log("Route layer added to map"); // Debug: Log layer addition

                                // Add distance and duration info
                                const distance = (route.distance / 1000).toFixed(1); // km
                                const duration = Math.round(route.duration / 60); // minutes

                                // Update popup content with route info
                                if (toMarker) {
                                    toMarker.setPopupContent(`To: ${document.getElementById('toOffice').options[document.getElementById('toOffice').selectedIndex].text}<br>
                                                  Distance: ${distance} km<br>
                                                  Estimated time: ${duration} min`);
                                    toMarker.openPopup();
                                }

                                // Ensure the map covers the whole route
                                officeMap.fitBounds(currentRouteLayer.getBounds(), {
                                    padding: [50, 50],
                                    maxZoom: 10
                                });
                                console.log("Map bounds updated to fit route"); // Debug: Log bounds update

                            } catch (e) {
                                console.error("Error adding route to map:", e);
                                if (toMarker) {
                                    toMarker.setPopupContent(`To: ${document.getElementById('toOffice').options[document.getElementById('toOffice').selectedIndex].text}<br>
                                                  Distance: ${distance} km <br>
                                Estimated time: ${duration} min` + "\nError displaying route. Please try again.");
                                }
                            }
                        } else {
                            console.warn("No routes found in OSRM response"); // Debug: Warn if no routes
                            if (toMarker) {
                                toMarker.setPopupContent("No route found between the selected points.");
                            }
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching route:", error);
                        if (toMarker) {
                            toMarker.setPopupContent("Error fetching route. Please try again.");
                        }
                    });
        }
    })();
</script>

<!-- Courier Tracking JavaScript -->
<script>
    (function () {
        // Variables to track state
        let trackingMap = null;
        let courierMarker = null;
        let startMarker = null;
        let endMarker = null;
        let routeLayer = null;
        let trackingInterval = null;
        let isTrackingEnabled = <?= isset($isTrackingEnabled) && $isTrackingEnabled ? 'true' : 'false' ?>;
        let watchPositionId = null;
        let selectedOrderId = null;
        let pathHistory = [];

        // DOM elements
        const toggleTrackingBtn = document.getElementById('toggle-tracking');
        const trackingIndicator = document.getElementById('tracking-indicator');
        const courierSelect = document.getElementById('courier-select');
        const orderSelect = document.getElementById('order-select');
        const trackingInfoEl = document.getElementById('tracking-info');

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tracking feature
            initTracking();

            // Set up event listeners
            if (toggleTrackingBtn) {
                toggleTrackingBtn.addEventListener('click', toggleTracking);
            }

            if (courierSelect) {
                courierSelect.addEventListener('change', handleCourierChange);
            }

            if (orderSelect) {
                orderSelect.addEventListener('change', handleOrderChange);
            }

            // Initialize the tracking map if not already done in initTracking
            if ('<?= $_SESSION['user']['role'] ?>' !== 'courier') {
                initTrackingMap();
            }

            // ADD SNIPPET 5 HERE - Special case for couriers without orders
            if ('<?= $_SESSION['user']['role'] ?>' === 'courier' && <?= empty($activeOrders) ? 'true' : 'false' ?>) {
                const courierLocationMap = document.getElementById('courier-location-map');
                if (courierLocationMap) {
                    // Initialize a special map just for courier location
                    trackingMap = L.map('courier-location-map', {
                        zoomControl: true,
                        attributionControl: true,
                        minZoom: 5,
                        maxZoom: 18
                    }).setView([42.7339, 25.4858], 7); // Default view of Bulgaria

                    // Add OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                        subdomains: 'abc',
                        maxZoom: 19
                    }).addTo(trackingMap);

                    // Fix map rendering
                    setTimeout(() => {
                        trackingMap.invalidateSize(true);
                        loadCourierCurrentLocation();
                    }, 200);
                }
            }
        });

        // Initialize tracking map
        function initTrackingMap() {
            const mapContainer = document.getElementById('tracking-map-container');
            if (!mapContainer)
                return;

            // Clear container
            mapContainer.innerHTML = '';

            // Create map
            try {
                trackingMap = L.map('tracking-map-container', {
                    zoomControl: true,
                    attributionControl: true,
                    minZoom: 5,
                    maxZoom: 18
                }).setView([42.7339, 25.4858], 7); // Default view of Bulgaria

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    subdomains: 'abc',
                    maxZoom: 19
                }).addTo(trackingMap);

                // Fix map rendering
                setTimeout(() => {
                    trackingMap.invalidateSize(true);
                }, 200);
            } catch (e) {
                console.error('Error initializing tracking map:', e);
                mapContainer.innerHTML = '<div class="alert alert-danger">Failed to initialize map: ' + e.message + '</div>';
            }
        }

        // Handle courier selection change
        function handleCourierChange() {
            if (!courierSelect || !courierSelect.value)
                return;

            // If tracking a previous order, stop it
            stopTracking();

            // Get courier location
            fetchCourierLocation(courierSelect.value);
        }

        // Handle order selection change
        function handleOrderChange() {
            if (!orderSelect || !orderSelect.value)
                return;

            selectedOrderId = orderSelect.value;

            // Clear previous tracking
            stopTracking();

            // Start tracking the selected order
            fetchTrackingData(selectedOrderId);
            startTrackingInterval();
        }

        // Fetch courier location
        function fetchCourierLocation(courierId) {
            fetch(`index.php?controller=CourierTracking&action=getCourierLocation&courier_id=${courierId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update the map with courier's location
                            updateCourierMarker(data.location.lat, data.location.lng);

                            // If courier has an active order, select it
                            if (data.location.order_id && orderSelect) {
                                orderSelect.value = data.location.order_id;
                                selectedOrderId = data.location.order_id;
                                fetchTrackingData(selectedOrderId);
                                startTrackingInterval();
                            }
                        } else {
                            console.error('Error fetching courier location:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching courier location:', error);
                    });
        }

        // Fetch tracking data for an order
        function fetchTrackingData(orderId) {
            fetch(`index.php?controller=CourierTracking&action=getTrackingData&order_id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update tracking info display
                            updateTrackingInfo(data.tracking);

                            // Update map with tracking data
                            updateTrackingMap(data.tracking);

                            // Fetch location history
                            fetchLocationHistory(orderId);
                        } else {
                            trackingInfoEl.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching tracking data:', error);
                        trackingInfoEl.innerHTML = '<div class="alert alert-danger">Error fetching tracking data. Please try again.</div>';
                    });
        }

        // Fetch location history
        function fetchLocationHistory(orderId) {
            fetch(`index.php?controller=CourierTracking&action=getLocationHistory&order_id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Draw the path on the map
                            drawLocationHistory(data.history);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching location history:', error);
                    });
        }

        // Update tracking info display
        function updateTrackingInfo(tracking) {
            if (!trackingInfoEl)
                return;

            const lastUpdated = new Date(tracking.last_updated).toLocaleString();
            const estimatedArrival = new Date(tracking.estimated_arrival).toLocaleString();

            trackingInfoEl.innerHTML = `
            <div class="mb-3">
                <strong>Courier:</strong> ${tracking.courier_name}
            </div>
            <div class="mb-3">
                <strong>Order Number:</strong> ${tracking.order_tracking_number}
            </div>
            <div class="mb-3">
                <strong>Last Updated:</strong> ${lastUpdated}
            </div>
            <div class="mb-3">
                <strong>Estimated Arrival:</strong> ${estimatedArrival}
            </div>
            <div class="mb-3">
                <strong>Distance Remaining:</strong> ${tracking.distance_remaining} km
            </div>
            <div class="mb-3">
                <strong>Total Distance:</strong> ${tracking.total_distance} km
            </div>
            <div class="progress mb-3">
                <div class="progress-bar bg-success" role="progressbar" style="width: ${tracking.percent_complete}%" 
                    aria-valuenow="${tracking.percent_complete}" aria-valuemin="0" aria-valuemax="100">
                    ${Math.round(tracking.percent_complete)}% Complete
                </div>
            </div>
        `;
        }

        // Update tracking map with data
        function updateTrackingMap(tracking) {
            if (!trackingMap)
                return;

            // Clear previous layers
            if (courierMarker)
                trackingMap.removeLayer(courierMarker);
            if (startMarker)
                trackingMap.removeLayer(startMarker);
            if (endMarker)
                trackingMap.removeLayer(endMarker);
            if (routeLayer)
                trackingMap.removeLayer(routeLayer);

            // Add courier marker with custom icon
            // Add courier marker with custom icon
            courierMarker = L.marker([tracking.current_location.lat, tracking.current_location.lng], {
                icon: L.divIcon({
                    className: 'courier-tracker-icon',
                    html: '<div class="courier-marker"><span class="pulse"></span><i class="fa fa-truck"></i></div>',
                    iconSize: [32, 32],
                    iconAnchor: [16, 24],
                    popupAnchor: [0, -16]
                })
            }).addTo(trackingMap)
                    .bindPopup(`<strong>Courier:</strong> ${tracking.courier_name}<br><strong>Last updated:</strong> ${new Date(tracking.last_updated).toLocaleTimeString()}`);

            // Add start and end markers
            startMarker = L.marker([tracking.start_point.lat, tracking.start_point.lng], {
                icon: L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                })
            }).addTo(trackingMap)
                    .bindPopup('Start Location');

            endMarker = L.marker([tracking.end_destination.lat, tracking.end_destination.lng], {
                icon: L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                })
            }).addTo(trackingMap)
                    .bindPopup('Destination');

            // Get route between points
            getDeliveryRoute(
                    tracking.start_point.lat,
                    tracking.start_point.lng,
                    tracking.end_destination.lat,
                    tracking.end_destination.lng
                    );

            // Fit map to show all markers
            const bounds = L.latLngBounds(
                    [tracking.start_point.lat, tracking.start_point.lng],
                    [tracking.end_destination.lat, tracking.end_destination.lng],
                    [tracking.current_location.lat, tracking.current_location.lng]
                    );
            trackingMap.fitBounds(bounds, {padding: [50, 50]});
        }

        // Draw location history path on map
        function drawLocationHistory(history) {
            if (!trackingMap || !history || history.length < 2)
                return;

            // Create line points
            const points = history.map(point => [point.lat, point.lng]);

            // Create and add polyline for the travel history
            const historyPath = L.polyline(points, {
                color: 'blue',
                weight: 3,
                opacity: 0.7,
                dashArray: '5, 10'
            }).addTo(trackingMap);

            // Store path for later removal
            pathHistory.push(historyPath);
        }

        // Get delivery route using OSRM
        function getDeliveryRoute(startLat, startLng, endLat, endLng) {
            const url = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${endLng},${endLat}?overview=full&geometries=geojson`;

            fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.routes && data.routes.length > 0) {
                            const route = data.routes[0];
                            const routeGeoJSON = route.geometry;

                            // Add route to map
                            routeLayer = L.geoJSON(routeGeoJSON, {
                                style: {
                                    color: "green",
                                    weight: 5,
                                    opacity: 0.6
                                }
                            }).addTo(trackingMap);

                            // Make sure route is in the background
                            if (routeLayer && routeLayer.bringToBack) {
                                routeLayer.bringToBack();
                            }
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching route:", error);
                    });
        }

        // Initialize tracking functionality
        function initTracking() {
            // Update UI based on initial tracking state
            updateTrackingUI();

            // If tracking is already enabled (for couriers), start the location updates
            if (isTrackingEnabled && selectedOrderId && navigator.geolocation) {
                startLocationTracking();
            }
        }

        // Toggle tracking state
        function toggleTracking() {
            isTrackingEnabled = !isTrackingEnabled;

            // Call API to update tracking permission in the database
            fetch('index.php?controller=CourierTracking&action=toggleTracking', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'tracking_enabled=' + (isTrackingEnabled ? 1 : 0)
            })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            isTrackingEnabled = data.tracking_enabled;
                            updateTrackingUI();

                            if (isTrackingEnabled) {
                                startLocationTracking();
                            } else {
                                stopLocationTracking();
                            }
                        } else {
                            console.error('Error toggling tracking:', data.message);
                            // Revert UI change if there was an error
                            isTrackingEnabled = !isTrackingEnabled;
                            updateTrackingUI();
                        }
                    })
                    .catch(error => {
                        console.error('Error toggling tracking:', error);
                        // Revert UI change if there was an error
                        isTrackingEnabled = !isTrackingEnabled;
                        updateTrackingUI();
                    });
        }

        // Update tracking UI elements
        function updateTrackingUI() {
            if (toggleTrackingBtn) {
                toggleTrackingBtn.className = isTrackingEnabled ? 'btn btn-danger' : 'btn btn-success';
                toggleTrackingBtn.innerHTML = isTrackingEnabled ?
                        '<i class="mdi mdi-crosshairs-gps me-1"></i> Stop Tracking' :
                        '<i class="mdi mdi-crosshairs-gps me-1"></i> Start Tracking';
            }

            if (trackingIndicator) {
                trackingIndicator.className = isTrackingEnabled ? 'badge bg-success' : 'badge bg-secondary';
                trackingIndicator.textContent = isTrackingEnabled ? 'Tracking Enabled' : 'Tracking Disabled';
            }
        }

        // Start tracking location using geolocation API
        function startLocationTracking() {
            if (!navigator.geolocation) {
                console.error('Geolocation is not supported by this browser.');
                return;
            }

            // Get the currently selected order
            if (!selectedOrderId && orderSelect) {
                selectedOrderId = orderSelect.value;
            }

            if (!selectedOrderId) {
                console.error('No order selected for tracking');
                return;
            }

            // Start watching position
            watchPositionId = navigator.geolocation.watchPosition(
                    // Success callback
                            function (position) {
                                const {latitude, longitude} = position.coords;

                                // Update UI with position
                                updateCourierMarker(latitude, longitude);

                                // Send position to server
                                updateLocationOnServer(latitude, longitude, selectedOrderId);
                            },
                            // Error callback
                                    function (error) {
                                        console.error('Geolocation error:', error);
                                        stopLocationTracking();

                                        // Show error to user
                                        if (trackingIndicator) {
                                            trackingIndicator.className = 'badge bg-danger';
                                            trackingIndicator.textContent = 'Tracking Failed';
                                        }
                                    },
                                    // Options
                                            {
                                                enableHighAccuracy: true,
                                                timeout: 10000,
                                                maximumAge: 0
                                            }
                                    );
                                }

                        // Stop tracking location
                        function stopLocationTracking() {
                            if (watchPositionId !== null) {
                                navigator.geolocation.clearWatch(watchPositionId);
                                watchPositionId = null;
                            }
                        }

                        // Add this function to your JavaScript to create a default courier icon
                        function createCourierIcon() {
                            return L.divIcon({
                                className: 'courier-location-icon',
                                html: '<div style="background-color: #007bff; width: 15px; height: 15px; border-radius: 50%; border: 2px solid white;"></div>',
                                iconSize: [15, 15],
                                iconAnchor: [7, 7],
                                popupAnchor: [0, -7]
                            });
                        }

// Then update the updateCourierMarker function:
                        function updateCourierMarker(lat, lng) {
                            if (!trackingMap)
                                return;

                            // If marker exists, update its position
                            if (courierMarker) {
                                courierMarker.setLatLng([lat, lng]);
                            } else {
                                // Create new marker with a div icon (no image required)
                                courierMarker = L.marker([lat, lng], {
                                    icon: createCourierIcon()
                                }).addTo(trackingMap)
                                        .bindPopup('Current Location');
                            }

                            // Open popup to make it more visible
                            courierMarker.openPopup();

                            // Center map on courier with zoom
                            trackingMap.setView([lat, lng], 15);

                            console.log("Updated courier marker position:", lat, lng);
                        }

                        // Send location update to server
                        // Replace the updateLocationOnServer function with this version
                        function updateLocationOnServer(latitude, longitude, orderId) {
                            // If no order ID, use the updateCurrentLocation endpoint
                            const endpoint = orderId ?
                                    'index.php?controller=CourierTracking&action=updateLocation' :
                                    'index.php?controller=CourierTracking&action=updateCurrentLocation';

                            const formData = orderId ?
                                    `latitude=${latitude}&longitude=${longitude}&order_id=${orderId}` :
                                    `latitude=${latitude}&longitude=${longitude}`;

                            fetch(endpoint, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: formData
                            })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            console.log('Location updated successfully');

                                            // If destination reached (only applies when tracking an order)
                                            if (data.destination_reached) {
                                                showDestinationReachedNotification();
                                                stopLocationTracking();
                                                isTrackingEnabled = false;
                                                updateTrackingUI();
                                            }
                                        } else {
                                            console.error('Error updating location:', data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error updating location:', error);
                                    });
                        }

// Modify startLocationTracking function to work without an order
                        function startLocationTracking() {
                            if (!navigator.geolocation) {
                                console.error('Geolocation is not supported by this browser.');
                                return;
                            }

                            // Get the currently selected order if available
                            if (orderSelect) {
                                selectedOrderId = orderSelect.value || null;
                            }

                            // Start watching position
                            watchPositionId = navigator.geolocation.watchPosition(
                                    // Success callback
                                            function (position) {
                                                const {latitude, longitude} = position.coords;

                                                // Update UI with position
                                                updateCourierMarker(latitude, longitude);

                                                // Send position to server (with or without order ID)
                                                updateLocationOnServer(latitude, longitude, selectedOrderId);
                                            },
                                            // Error callback
                                                    function (error) {
                                                        console.error('Geolocation error:', error);
                                                        stopLocationTracking();

                                                        // Show error to user
                                                        if (trackingIndicator) {
                                                            trackingIndicator.className = 'badge bg-danger';
                                                            trackingIndicator.textContent = 'Tracking Failed';
                                                        }
                                                    },
                                                    // Options
                                                            {
                                                                enableHighAccuracy: true,
                                                                timeout: 10000,
                                                                maximumAge: 0
                                                            }
                                                    );
                                                }

// Add this function to load the courier's current location
                                        function loadCourierCurrentLocation() {
                                            if (!trackingMap)
                                                return;

                                            fetch('index.php?controller=CourierTracking&action=getCurrentLocation')
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.status === 'success') {
                                                            updateCourierMarker(data.location.lat, data.location.lng);

                                                            // Center map on courier's location
                                                            trackingMap.setView([data.location.lat, data.location.lng], 12);

                                                            // Update tracking status if needed
                                                            if (data.tracking_enabled !== isTrackingEnabled) {
                                                                isTrackingEnabled = data.tracking_enabled;
                                                                updateTrackingUI();
                                                            }
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error loading courier location:', error);
                                                    });
                                        }

// Modify initTracking to always load current location for couriers
                                        function initTracking() {
                                            // Update UI based on initial tracking state
                                            updateTrackingUI();

                                            // For couriers, always try to load their current location
                                            if ('<?= $_SESSION['user']['role'] ?>' === 'courier') {
                                                // Initialize tracking map
                                                initTrackingMap();

                                                // Load current location
                                                setTimeout(loadCourierCurrentLocation, 1000);

                                                // If tracking is already enabled, start the location updates
                                                if (isTrackingEnabled && navigator.geolocation) {
                                                    startLocationTracking();
                                                }
                                            }
                                        }

                                        // Start interval for tracking updates (for viewers)
                                        function startTrackingInterval() {
                                            // Stop any existing interval
                                            stopTracking();

                                            // Start new interval to update tracking data every 10 seconds
                                            trackingInterval = setInterval(() => {
                                                if (selectedOrderId) {
                                                    fetchTrackingData(selectedOrderId);
                                                }
                                            }, 10000); // 10 seconds
                                        }

                                        // Stop tracking interval
                                        function stopTracking() {
                                            if (trackingInterval) {
                                                clearInterval(trackingInterval);
                                                trackingInterval = null;
                                            }

                                            // Clear path history
                                            if (pathHistory.length > 0 && trackingMap) {
                                                pathHistory.forEach(path => trackingMap.removeLayer(path));
                                                pathHistory = [];
                                            }
                                        }

                                        // Show notification when destination is reached
                                        function showDestinationReachedNotification() {
                                            // Create notification element
                                            const notification = document.createElement('div');
                                            notification.className = 'alert alert-success alert-dismissible fade show';
                                            notification.setAttribute('role', 'alert');
                                            notification.innerHTML = `
            <strong>Destination Reached!</strong> Order has been marked as delivered.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

                                            // Add to page
                                            document.querySelector('.container-fluid').prepend(notification);

                                            // Auto-close after 10 seconds
                                            setTimeout(() => {
                                                notification.classList.remove('show');
                                                setTimeout(() => notification.remove(), 500);
                                            }, 10000);
                                        }

                                        // Handle page unload - cleanup resources
                                        window.addEventListener('beforeunload', function () {
                                            stopTracking();
                                            stopLocationTracking();
                                        });
                                    })();
</script>