<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo INSTALL_URL; ?>?controller=Order&action=list">Order
                            List</a>
                    </li>
                    <li class="nav-item">
                        <!-- ps-0 is no-spacing, set to ps-3! -->
                        <a class="nav-link active ps-3"
                            href="<?php echo INSTALL_URL; ?>?controller=Order&action=create">Create Order</a>
                    </li>
                </ul>
            </div>

            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <h4 class="card-title">Create New Order</h4>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form method="POST" id="booking-frm-id"
                        action="<?php echo INSTALL_URL; ?>?controller=Order&action=create">
                        <input type="hidden" name="send" value="1" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer" class="form-label">Customer</label>
                                    <select name="user_id" id="userId" class="form-select" required>
                                        <option value=''>---</option>
                                        <?php
                                        foreach ($tpl['users'] as $user) {
                                            echo "<option value=\"{$user['id']}\" 
                                                data-address=\"" . htmlspecialchars($user['address']) . "\" 
                                                data-region=\"" . htmlspecialchars($user['region']) . "\">
                                                {$user['name']}</option>";
                                        }
                                        ?>
                                        <!-- Not required -->
                                        <!-- <?php
                                        foreach ($tpl['users'] as $user) {
                                            echo "<option value=\"{$user['id']}\" 
                                                data-address=\"" . htmlspecialchars($user['address']) . "\" 
                                                data-region=\"" . htmlspecialchars($user['region']) . "\">
                                                {$user['name']}</option>";
                                        }
                                        ?> -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="courierId" class="form-label">Courier</label>
                                    <select name="courier_id" id="courierId" class="form-select" required>
                                        <option value=''>---</option>
                                        <?php
                                        foreach ($tpl['couriers'] as $courier) {
                                            echo "<option value=\"{$courier['id']}\">{$courier['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="deliveryDate" class="form-label">Date of Delivery</label>
                                    <input type="date" class="form-control" id="deliveryDate" name="delivery_date"
                                        value="">
                                </div>
                            </div>
                            <form id="orderCreationForm" action="process_order.php" method="post">
                                <!-- Start Destination Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5>Start Destination</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="startLocationType"
                                                    id="startOffice" value="office" checked>
                                                <label class="form-check-label" for="startOffice">Office</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="startLocationType"
                                                    id="startAddress" value="address">
                                                <label class="form-check-label" for="startAddress">Address</label>
                                            </div>
                                        </div>

                                        <!-- Office Selection -->
                                        <div class="form-group mb-3" id="startOfficeGroup">
                                            <label for="startOfficeSelect">Select Office:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control office-autocomplete"
                                                    id="startOfficeInput" placeholder="Type to search offices">
                                                <select class="form-control d-none" id="startOfficeSelect"
                                                    name="startOffice" required>
                                                    <option value="">Select an office</option>
                                                </select>
                                                <input type="hidden" id="startOfficeCoords" name="startOfficeCoords">
                                            </div>
                                            <div id="startOfficeAutocomplete" class="autocomplete-results"></div>
                                        </div>

                                        <!-- Address Input -->
                                        <div class="form-group mb-3 d-none" id="startAddressGroup">
                                            <label for="startAddressInput">Enter Address:</label>
                                            <input type="text" class="form-control" id="startAddressInput"
                                                name="startAddress" placeholder="Enter full address">
                                            <input type="hidden" id="startAddressCoords" name="startAddressCoords">
                                            <div class="invalid-feedback">Please enter a valid address</div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                                id="validateStartAddress">Validate Address</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- End Destination Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-success text-white">
                                        <h5>End Destination</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="endLocationType"
                                                    id="endOffice" value="office" checked>
                                                <label class="form-check-label" for="endOffice">Office</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="endLocationType"
                                                    id="endAddress" value="address">
                                                <label class="form-check-label" for="endAddress">Address</label>
                                            </div>
                                        </div>

                                        <!-- Office Selection -->
                                        <div class="form-group mb-3" id="endOfficeGroup">
                                            <label for="endOfficeSelect">Select Office:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control office-autocomplete"
                                                    id="endOfficeInput" placeholder="Type to search offices">
                                                <select class="form-control d-none" id="endOfficeSelect"
                                                    name="endOffice" required>
                                                    <option value="">Select an office</option>
                                                </select>
                                                <input type="hidden" id="endOfficeCoords" name="endOfficeCoords">
                                            </div>
                                            <div id="endOfficeAutocomplete" class="autocomplete-results"></div>
                                        </div>

                                        <!-- Address Input -->
                                        <div class="form-group mb-3 d-none" id="endAddressGroup">
                                            <label for="endAddressInput">Enter Address:</label>
                                            <input type="text" class="form-control" id="endAddressInput"
                                                name="endAddress" placeholder="Enter full address">
                                            <input type="hidden" id="endAddressCoords" name="endAddressCoords">
                                            <div class="invalid-feedback">Please enter a valid address</div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                                id="validateEndAddress">Validate Address</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map preview -->
                                <div class="card mb-4">
                                    <div class="card-header bg-info text-white">
                                        <h5>Route Preview</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="map-container" class="map-loading">
                                            <div class="map-loading-indicator">
                                                <link rel="stylesheet"
                                                    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                                                    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
                                                    crossorigin="" />
                                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                                                    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                                                    crossorigin=""></script>
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading map...</span>
                                                </div>
                                                <p class="mt-2">Loading map...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="submitOrder">Create Order</button>
                                </div>
                            </form>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Product Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                        <input type="text" class="form-control" id="productPrice" name="product_price"
                                            readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="tax" class="form-label">Tax</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                        <input type="text" class="form-control" id="tax" name="tax" required readonly>
                                    </div>
                                </div>

                                <!-- Unneeded. -->
                                <!-- <div class="mb-3">
                                    <label for="shippingPrice" class="form-label">Shipping Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"></* ?php echo $tpl['currency']; */ ?></span>
                                        <input type="text" class="form-control" id="shippingPrice"  name="shipping_price" required readonly>
                                    </div>
                                </div>  -->

                                <div class="mb-3">
                                    <label for="totalPrice" class="form-label">Total Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                        <input type="text" class="form-control" id="totalPrice" name="total_price"
                                            readonly>
                                    </div>
                                </div>

                                <!-- Admins set orders to started or pending automatically, depending on delivery date. Write code later. -->
                                <!-- <div class="mb-3">
                                    <label for="status" class="form-label">Order Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value=''>---</option>
                                        </*?php
                                        foreach (Utility::$order_status as $k => $v) {
                                            ?>
                                            <option value="</*?php echo $k; ?>"></*?php echo $v; */?></option>
                                            </*?php
                                        }
                                        */?>
                                    </select>
                                </div> -->
                                <div id="parcelRows">
                                    <div class="row align-items-end mb-3 parcel-row">
                                        <div class="col-md-12">
                                            <label for="parcelIds" class="form-label">Pallet</label>
                                            <select name="parcel_id[]" id="parcelIds" class="form-select" required>
                                                <option value="">---</option>
                                                <?php
                                                foreach ($tpl['pallets'] as $item) {
                                                    echo "<option value=\"{$item['id']}\" data-max-quantity=\"{$item['stock']}\">{$item['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Not a plan yet, but can be enhanced for pallets vvv -->
                                        <!-- <div class="col-md-4">
                                            <label for="quantities" class="form-label">Quantity</label>
                                            <input type="number" step="1" min="1" class="form-control" id="quantities"
                                                   name="quantity[]" required>
                                        </div> -->

                                        <!-- <div class="col-md-1 text-center d-flex justify-content-center align-items-center">
                                            <button type="button" class="btn btn-light d-flex justify-content-center align-items-center rounded-circle add-row" style="width: 36px; height: 36px;">+</button>
                                        </div> -->
                                        <!-- Not a plan yet, but can be enhanced for pallets ^^^ -->
                                    </div>
                                </div>

                                <!-- Time of Delivery -->
                                <div class="mb-3">
                                    <label for="timeOfDelivery" class="form-label">Time of Delivery: <span
                                            id="currentTimeLabel"></span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-clock text-primary"></i></span>
                                        <input type="text" class="form-control" id="timeOfDelivery"
                                            name="time_of_delivery" readonly autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mt-3">
                                <strong>Please note:</strong><br>
                                <span class="note-indicator"></span>
                                If delivery is far from current date (04/08/2025), e.g., in 7 days, we will notify via
                                email and in-site messages 3 days prior that your delivery will be processed soon. On
                                the day of the delivery, we will notify again that the palette is on its way.
                                <br>
                                <span class="note-indicator"></span>
                                If you see your time differing from the order delivery time, it is that you are making
                                it out of working hours.
                                <span id="dynamic-late-note"></span>
                                <br>
                                <strong>Reminder:</strong> You can keep track of couriers if you feel anxious (for just
                                curious), via the map tracking!
                            </p>
                        </div>
                        <!-- Reminder for paragraph, add link once map tracking is done. ^^^  -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-white me-0">Create Order</button>
                                <a href="javascript:" id="calculate-price-btn-id"
                                    class="btn btn-primary text-white me-0">Calculate Price</a>
                                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"
                                    class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visual appeal for notes -->
<style>
    .note-indicator {
        display: inline-block;
        /* Allows us to control width and height */
        width: 30px;
        /* Adjust the length of the line */
        height: 2px;
        /* Adjust the thickness of the line */
        background-color: #007bff;
        /* Choose your desired color (Bootstrap primary color used as an example) */
        vertical-align: middle;
        /* Align the line with the text */
        margin-right: 8px;
        /* Add some spacing between the line and the text */
    }
</style>

<!-- Get Time Dynamically -->
<!-- Removing default arrow formatting (shows an arrow above if this is gone) -->
<style>
    /* For Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* For Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script>
    function updateTimeOfDelivery() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();

        // Format with leading zeros
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        const currentTimeWithSeconds = `${hours}:${minutes}:${seconds}`;
        const currentTimeWithMinutes = `${hours}:${minutes}`;

        document.getElementById('currentTimeLabel').textContent = `(Current Time: ${currentTimeWithSeconds})`;
        document.getElementById('timeOfDelivery').value = currentTimeWithMinutes;
    }

    // Update the time immediately
    updateTimeOfDelivery();

    // Update the time every second
    setInterval(updateTimeOfDelivery, 1000);
</script>

<!-- Time messages, based on local time -->
<script>
    function updateLateNightNote() {
        const now = new Date();
        const currentHour = now.getHours();
        const dynamicNoteElement = document.getElementById('dynamic-late-note');
        let message = "";

        if (currentHour >= 18 && currentHour < 21) {
            message = " You are a bit late, we schedule it for tomorrow, do not worry!";
        } else if (currentHour >= 21 || currentHour < 1) { // 21:00 to 00:59
            message = " It is a quiet evening, wishing you that if not! Order is automatically scheduled for tomorrow!";
        } else if (currentHour >= 1 && currentHour < 5.5) { // 01:00 to 05:29
            message = " Assuming you are local, get some sleep! What are you doing this late here? Order is automatically scheduled for later!";
        } else if (currentHour >= 5.5 && currentHour < 8) { // 05:30 to 07:59
            message = " Close to be open, you are eager to order! Order is automatically scheduled for later!";
        }

        dynamicNoteElement.textContent = message;
    }

    // Update the note immediately and then every minute (no need for second-level precision for this)
    updateLateNightNote();
    setInterval(updateLateNightNote, 60000); // 60000 milliseconds = 1 minute
</script>

<!-- Office and map related JavaScript -->
<script>
    // Office locations directly in the JavaScript
    const officeLocations = [
        { "city": "Sofia", "lat": 42.6977, "lng": 23.3219 },
        { "city": "Plovdiv", "lat": 42.1482, "lng": 24.7494 },
        { "city": "Varna", "lat": 43.2141, "lng": 27.9147 },
        { "city": "Burgas", "lat": 42.5061, "lng": 27.4678 },
        { "city": "Ruse", "lat": 43.8545, "lng": 25.9681 },
        { "city": "Stara Zagora", "lat": 42.4226, "lng": 25.6347 },
        { "city": "Pleven", "lat": 43.4114, "lng": 24.6158 },
        { "city": "Sliven", "lat": 42.6784, "lng": 26.3245 },
        { "city": "Yambol", "lat": 42.4854, "lng": 26.5060 },
        { "city": "Haskovo", "lat": 41.9341, "lng": 25.5560 },
        { "city": "Shumen", "lat": 43.2761, "lng": 26.9350 },
        { "city": "Pernik", "lat": 42.6038, "lng": 23.0342 },
        { "city": "Dobrich", "lat": 43.5606, "lng": 27.8284 },
        { "city": "Pazardzhik", "lat": 42.1994, "lng": 24.3317 },
        { "city": "Blagoevgrad", "lat": 42.0227, "lng": 23.0906 },
        { "city": "Veliko Tarnovo", "lat": 43.0757, "lng": 25.6172 },
        { "city": "Gabrovo", "lat": 42.8764, "lng": 25.3259 },
        { "city": "Vratsa", "lat": 43.2048, "lng": 23.5510 },
        { "city": "Kazanlak", "lat": 42.6205, "lng": 25.4093 },
        { "city": "Vidin", "lat": 43.9935, "lng": 22.8724 },
        { "city": "Montana", "lat": 43.4127, "lng": 23.2357 },
        { "city": "Kardzhali", "lat": 41.6446, "lng": 25.3649 },
        { "city": "Lovech", "lat": 43.1304, "lng": 24.7153 },
        { "city": "Silistra", "lat": 44.1189, "lng": 27.2758 },
        { "city": "Targovishte", "lat": 43.2500, "lng": 26.5700 },
        { "city": "Razgrad", "lat": 43.5333, "lng": 26.5167 }
    ];

    // Global variables for map and markers
    let orderMap = null;
    let startMarker = null;
    let endMarker = null;
    let routeLayer = null;

    // Initialize when the document is loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Populate office select options
        populateOfficeOptions();

        // Set up event listeners for radio buttons
        setupRadioListeners();

        // Set up office autocomplete
        setupOfficeAutocomplete();

        // Set up address validation
        setupAddressValidation();

        // Initialize map
        initializeOrderMap();

        // Set up form validation
        setupFormValidation();
    });

    // Initialize the map
    function initializeOrderMap() {
        // Make sure Leaflet is loaded
        if (typeof L !== 'undefined') {
            orderMap = L.map('map-container').setView([42.7339, 25.4858], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(orderMap);

            // Force a map redraw
            setTimeout(function () {
                orderMap.invalidateSize(true);
            }, 200);
        } else {
            console.error("Leaflet library not loaded!");
        }
    }

    // Populate office select dropdowns with the data
    function populateOfficeOptions() {
        const startSelect = document.getElementById('startOfficeSelect');
        const endSelect = document.getElementById('endOfficeSelect');

        officeLocations.forEach(office => {
            const option = document.createElement('option');
            option.value = `${office.lat},${office.lng}`;
            option.textContent = office.city;

            // Clone the option for both selects
            startSelect.appendChild(option);
            const endOption = option.cloneNode(true);
            endSelect.appendChild(endOption);
        });
    }

    // Set up event listeners for radio buttons
    function setupRadioListeners() {
        // Start location radio buttons
        document.getElementById('startOffice').addEventListener('change', function () {
            document.getElementById('startOfficeGroup').classList.remove('d-none');
            document.getElementById('startAddressGroup').classList.add('d-none');
            updateMap();
        });

        document.getElementById('startAddress').addEventListener('change', function () {
            document.getElementById('startOfficeGroup').classList.add('d-none');
            document.getElementById('startAddressGroup').classList.remove('d-none');
            updateMap();
        });

        // End location radio buttons
        document.getElementById('endOffice').addEventListener('change', function () {
            document.getElementById('endOfficeGroup').classList.remove('d-none');
            document.getElementById('endAddressGroup').classList.add('d-none');
            updateMap();
        });

        document.getElementById('endAddress').addEventListener('change', function () {
            document.getElementById('endOfficeGroup').classList.add('d-none');
            document.getElementById('endAddressGroup').classList.remove('d-none');
            updateMap();
        });
    }

    // Setup office autocomplete functionality
    function setupOfficeAutocomplete() {
        const startInput = document.getElementById('startOfficeInput');
        const endInput = document.getElementById('endOfficeInput');

        // Start office autocomplete
        startInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const results = officeLocations.filter(office =>
                office.city.toLowerCase().includes(query)
            );

            showAutocompleteResults('startOfficeAutocomplete', results, 'startOfficeInput', 'startOfficeSelect', 'startOfficeCoords');
        });

        // End office autocomplete
        endInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const results = officeLocations.filter(office =>
                office.city.toLowerCase().includes(query)
            );

            showAutocompleteResults('endOfficeAutocomplete', results, 'endOfficeInput', 'endOfficeSelect', 'endOfficeCoords');
        });
    }

    // Display autocomplete results
    function showAutocompleteResults(containerId, results, inputId, selectId, coordsId) {
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);
        const select = document.getElementById(selectId);
        const coords = document.getElementById(coordsId);

        // Clear previous results
        container.innerHTML = '';

        if (results.length === 0) {
            container.classList.add('d-none');
            return;
        }

        // Create results list
        const ul = document.createElement('ul');
        ul.className = 'list-group';

        results.forEach(office => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = office.city;

            li.addEventListener('click', function () {
                input.value = office.city;
                coords.value = `${office.lat},${office.lng}`;

                // Set the select value for form submission
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].textContent === office.city) {
                        select.selectedIndex = i;
                        break;
                    }
                }

                container.classList.add('d-none');
                updateMap();
            });

            ul.appendChild(li);
        });

        container.appendChild(ul);
        container.classList.remove('d-none');
    }

    // Setup address validation with OSM Nominatim
    function setupAddressValidation() {
        document.getElementById('validateStartAddress').addEventListener('click', function () {
            validateAddress('startAddressInput', 'startAddressCoords');
        });

        document.getElementById('validateEndAddress').addEventListener('click', function () {
            validateAddress('endAddressInput', 'endAddressCoords');
        });
    }

    // Validate address using OSM Nominatim
    function validateAddress(inputId, coordsId) {
        const input = document.getElementById(inputId);
        const coords = document.getElementById(coordsId);
        const address = input.value.trim();

        // Minimum address length requirement (at least 5 characters)
        if (!address || address.length < 5) {
            input.classList.add('is-invalid');
            input.nextElementSibling.textContent = "Address is too short. Please enter a complete address.";
            return;
        }

        // Add "Bulgaria" to the address if not already included
        let searchAddress = address;
        if (!searchAddress.toLowerCase().includes('bulgaria') &&
            !searchAddress.toLowerCase().includes('българия')) {
            searchAddress += ', Bulgaria';
        }

        // Use OSM Nominatim for geocoding with specific parameters
        const nominatimUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchAddress)}&limit=1&countrycodes=bg&addressdetails=1`;

        // Show loading state
        input.classList.remove('is-invalid');
        input.classList.remove('is-valid');
        input.classList.add('is-validating');
        input.nextElementSibling.textContent = "Validating address...";

        fetch(nominatimUrl)
            .then(response => response.json())
            .then(data => {
                input.classList.remove('is-validating');

                if (data && data.length > 0) {
                    const result = data[0];

                    // Check if the result is actually in Bulgaria
                    if (result.address &&
                        (result.address.country === "Bulgaria" || result.address.country === "България")) {

                        // Check if the address has enough detail (street or road or neighborhood)
                        const hasStreetLevel = result.address.road ||
                            result.address.street ||
                            result.address.neighbourhood ||
                            result.address.suburb;

                        if (hasStreetLevel) {
                            coords.value = `${result.lat},${result.lon}`;
                            input.classList.add('is-valid');
                            input.classList.remove('is-invalid');
                            input.nextElementSibling.textContent = "";

                            // Show the validated address in a more readable format
                            const formattedAddress = result.display_name;
                            input.value = formattedAddress;

                            // Update map with the new location
                            updateMap();
                        } else {
                            input.classList.add('is-invalid');
                            coords.value = '';
                            input.nextElementSibling.textContent = "Please provide more specific address details (include street name).";
                        }
                    } else {
                        input.classList.add('is-invalid');
                        coords.value = '';
                        input.nextElementSibling.textContent = "Address must be located in Bulgaria.";
                    }
                } else {
                    input.classList.add('is-invalid');
                    coords.value = '';
                    input.nextElementSibling.textContent = "Address not found. Please check for typos or provide more details.";
                }
            })
            .catch(error => {
                console.error('Error validating address:', error);
                input.classList.remove('is-validating');
                input.classList.add('is-invalid');
                input.nextElementSibling.textContent = "Error validating address. Please try again.";
            });
    }

    // Update the map with current selections
    function updateMap() {
        if (!orderMap) return;

        // Clear existing markers and route
        if (startMarker) orderMap.removeLayer(startMarker);
        if (endMarker) orderMap.removeLayer(endMarker);
        if (routeLayer) orderMap.removeLayer(routeLayer);

        let startCoords = null;
        let endCoords = null;

        // Get start coordinates
        if (document.getElementById('startOffice').checked) {
            const coords = document.getElementById('startOfficeCoords').value;
            if (coords) {
                const [lat, lng] = coords.split(',').map(parseFloat);
                startCoords = [lat, lng];
            }
        } else {
            const coords = document.getElementById('startAddressCoords').value;
            if (coords) {
                const [lat, lng] = coords.split(',').map(parseFloat);
                startCoords = [lat, lng];
            }
        }

        // Get end coordinates
        if (document.getElementById('endOffice').checked) {
            const coords = document.getElementById('endOfficeCoords').value;
            if (coords) {
                const [lat, lng] = coords.split(',').map(parseFloat);
                endCoords = [lat, lng];
            }
        } else {
            const coords = document.getElementById('endAddressCoords').value;
            if (coords) {
                const [lat, lng] = coords.split(',').map(parseFloat);
                endCoords = [lat, lng];
            }
        }

        // Add markers if coordinates are available
        if (startCoords) {
            startMarker = L.marker(startCoords)
                .addTo(orderMap)
                .bindPopup('Start Location');
        }

        if (endCoords) {
            endMarker = L.marker(endCoords)
                .addTo(orderMap)
                .bindPopup('End Location');
        }

        // If both coordinates are available, calculate and display route
        if (startCoords && endCoords) {
            getRoute(startCoords[0], startCoords[1], endCoords[0], endCoords[1]);

            // Fit map to show both markers
            const bounds = L.latLngBounds([startCoords, endCoords]);
            orderMap.fitBounds(bounds, { padding: [50, 50] });
        } else if (startCoords) {
            orderMap.setView(startCoords, 12);
        } else if (endCoords) {
            orderMap.setView(endCoords, 12);
        }
    }

    // Get and display route between two points
    function getRoute(fromLat, fromLng, toLat, toLng) {
        // Use OSRM for routing
        const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLat}?overview=full&geometries=geojson`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const routeGeoJSON = route.geometry;

                    // Create route layer
                    routeLayer = L.geoJSON(routeGeoJSON, {
                        style: {
                            color: "blue",
                            weight: 5,
                            opacity: 0.7
                        }
                    }).addTo(orderMap);

                    // Calculate distance and duration
                    const distance = (route.distance / 1000).toFixed(1); // km
                    const duration = Math.round(route.duration / 60); // minutes

                    // Update end marker popup with route info
                    if (endMarker) {
                        endMarker.setPopupContent(`End Location<br>
                        Distance: ${distance} km<br>
                        Estimated time: ${duration} min`);
                        endMarker.openPopup();
                    }
                }
            })
            .catch(error => console.error('Error fetching route:', error));
    }

    // Form validation before submission
    function setupFormValidation() {
        document.getElementById('orderCreationForm').addEventListener('submit', function (event) {
            let isValid = true;

            // Validate start location
            if (document.getElementById('startOffice').checked) {
                if (!document.getElementById('startOfficeCoords').value) {
                    document.getElementById('startOfficeInput').classList.add('is-invalid');
                    isValid = false;
                }
            } else {
                if (!document.getElementById('startAddressCoords').value) {
                    document.getElementById('startAddressInput').classList.add('is-invalid');
                    isValid = false;
                }
            }

            // Validate end location
            if (document.getElementById('endOffice').checked) {
                if (!document.getElementById('endOfficeCoords').value) {
                    document.getElementById('endOfficeInput').classList.add('is-invalid');
                    isValid = false;
                }
            } else {
                if (!document.getElementById('endAddressCoords').value) {
                    document.getElementById('endAddressInput').classList.add('is-invalid');
                    isValid = false;
                }
            }

            if (!isValid) {
                event.preventDefault();
                alert('Please make sure all locations are valid before submitting the order.');
            }
        });
    }
</script>

<style>
    /* Order Creation Form Styles */
    .autocomplete-results {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        border: 1px solid #ced4da;
        border-top: none;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .autocomplete-results ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .autocomplete-results li {
        padding: 10px 15px;
        cursor: pointer;
    }

    .autocomplete-results li:hover {
        background-color: #f8f9fa;
    }

    /* Hide the standard select */
    .d-none {
        display: none !important;
    }

    /* Style for validated fields */
    .is-valid {
        border-color: #28a745;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }

    .is-invalid~.invalid-feedback {
        display: block;
    }

    /* Map loading state styling */
    .map-loading {
        position: relative;
        min-height: 400px;
        width: 100%;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .map-loading-indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 0;
    }

    /* Ensure the map container has proper dimensions */
    #map-container {
        height: 400px !important;
        width: 100% !important;
        min-height: 400px !important;
        position: relative !important;
        z-index: 1 !important;
    }

    /* Fix for Leaflet controls */
    .leaflet-control-container {
        position: absolute;
        z-index: 1000;
    }

    /* Ensure Leaflet attribution is visible */
    .leaflet-control-attribution {
        background-color: rgba(255, 255, 255, 0.8) !important;
        padding: 0 5px !important;
    }

    /* Leaflet popup fixes */
    .leaflet-popup-content {
        margin: 13px 19px;
        line-height: 1.4;
    }

    /* Fix for any potential z-index issues */
    .leaflet-map-pane {
        z-index: 2 !important;
    }

    .leaflet-top,
    .leaflet-bottom {
        z-index: 1000 !important;
    }
</style>