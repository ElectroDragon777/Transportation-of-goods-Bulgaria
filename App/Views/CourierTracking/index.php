<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="true">Home</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="about-tab" data-bs-toggle="tab" href="#about" role="tab"
                            aria-selected="false">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="price_decision-tab" data-bs-toggle="tab" href="#price_decision"
                            role="tab" aria-selected="false">Price-decision</a>
                    </li> -->
                </ul>
                <div>
                    <!-- <div class="btn-wrapper">
                        <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
                        <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                        <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
                    </div> -->
                </div>
            </div>

            <!-- <div class="tracking-status-panel mb-4">
                <h6>Tracking Status</h6>
                <div class="d-flex align-items-center gap-3">
                    <div id="tracking-indicator" class="badge bg-secondary">Not tracking</div>
                    <button id="toggle-tracking" class="btn btn-primary">
                        <i class="mdi mdi-crosshairs-gps me-1"></i>
                        Start Tracking
                    </button>
                </div>
            </div> -->


            <div class="tab-content tab-content-basic">
                <!-- Home -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <!-- Office -->
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
                                                foreach ($offices as $office): ?>
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
                                            <<link rel="stylesheet"
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

                                            <!-- Load our custom map script AFTER Leaflet -->
                                            <script src="js/office-map.js"></script>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                if (fromMarker) officeMap.removeLayer(fromMarker);
                if (toMarker) officeMap.removeLayer(toMarker);
                if (currentRouteLayer) officeMap.removeLayer(currentRouteLayer);

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