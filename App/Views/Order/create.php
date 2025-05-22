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
                        <div class="row" style="background: linear-gradient(rgba(255, 255, 255, 0.47), rgba(85, 85, 85, 0.23)), url('Extras/Controllers/bluebackground_order_creation.jpg');
                             background-repeat: no-repeat;
                             background-attachment: fixed;
                             background-size: cover; border-radius: 25px;">
                            <div class="col-md-6">
                                <!-- Replace Customer Dropdown with Current User Info -->
                                <div class="mb-3">
                                    <label class="form-label">Customer</label>
                                    <p class="form-control-static">
                                        Ordering as: <strong><?php echo $_SESSION['user']['name']; ?></strong>
                                        <input type="hidden" name="user_id"
                                            value="<?php echo $_SESSION['user']['id']; ?>">
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label for="courierId" class="form-label">Courier</label>
                                    <select name="courier_id" id="courierId" class="form-select" required>
                                        <option value=''>---</option>
                                        <?php
                                        foreach ($tpl['couriers'] as $courier) {
                                            $disabledAttribute = '';
                                            if (isset($courier['is_busy']) && $courier['is_busy'] == 1) {
                                                $disabledAttribute = 'disabled';
                                            }
                                            echo "<option value=\"" . htmlspecialchars($courier['user_id']) . "\" " . $disabledAttribute . ">" . htmlspecialchars($courier['name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!--  Parcel Selection -->
                            <div id="parcelRows">
                                <div class="row align-items-end mb-12 parcel-row">
                                    <div class="col-md-12" id="parcelRows">
                                        <div class="row align-items-end mb-3 parcel-row">
                                            <div class="col-md-6">
                                                <label for="parcelIds" class="form-label">Parcel</label>
                                                <select name="parcel_id[]" id="parcelIds" class="form-select" required>
                                                    <option value="">---</option>
                                                    <?php
                                                    foreach ($tpl['pallets'] as $item) {
                                                        // Only show products with stock greater than zero
                                                        if ($item['stock'] > 0) {
                                                            ?>
                                                            <option value="<?php echo $item['id']; ?>"
                                                                data-max-quantity="<?php echo $item['stock']; ?>">
                                                                <?php echo htmlspecialchars($item['name']); ?>
                                                                (Available:
                                                                <?php echo $item['stock']; ?>)
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <div class="invalid-feedback">Please select a product with
                                                    available
                                                    stock.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="quantities" class="form-label">Quantity</label>
                                                <input type="number" step="1" min="1" class="form-control"
                                                    id="quantities" name="quantity[]" required>
                                                <div class="invalid-feedback">Please enter a valid quantity
                                                </div>
                                            </div>
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
                            </div>
                        </div>

                        <!-- Payment Method Styling Enhancements -->
                        <div class="payment-methods card mb-4">
                            <div class="mb-6">
                                <div class="card-header bg-warning text-white">
                                    <h5>Payment Method</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="paymentOnline" value="online">
                                        <label class="form-check-label" for="paymentOnline">
                                            Online Payment (PayPal)
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="paymentCash" value="cash">
                                        <label class="form-check-label" for="paymentCash">
                                            Cash on Delivery (+1.5% fee)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Start Destination Section -->
                        <div class="card mb-4" style="background: linear-gradient(rgba(255, 255, 255, 0.58), rgba(85, 85, 85, 0.58)), url('Extras/Controllers/BulgarianMap.png');
                             background-repeat: no-repeat;
                             background-attachment: fixed;
                             background-size: cover; border-radius: 25px; color: white;">
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
                                    <div class="invalid-feedback">Please select a start location.</div>
                                    <!-- Add this line ^ -->
                                </div>

                                <!-- Office Selection -->
                                <div class="form-group mb-3" id="startOfficeGroup">
                                    <label for="startOfficeSelect">Select Office:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control office-autocomplete"
                                            id="startOfficeInput" placeholder="Type to search offices">
                                        <select class="form-control d-none" id="startOfficeSelect" name="startOffice">
                                            <option value="">Select an office</option>
                                        </select>
                                        <input type="hidden" id="startOfficeCoords" name="startOfficeCoords">
                                        <input type="hidden" id="startOfficeName" name="startOfficeName">
                                    </div>
                                    <div id="startOfficeAutocomplete" class="autocomplete-results"></div>
                                </div>

                                <!-- Address Input -->
                                <div class="form-group mb-3 d-none" id="startAddressGroup">
                                    <label for="startAddressInput">Enter Address:</label>
                                    <input type="text" class="form-control" id="startAddressInput" name="startAddress"
                                        placeholder="Enter full address">
                                    <input type="hidden" id="startAddressCoords" name="startAddressCoords">
                                    <input type="hidden" id="startAddressName" name="startAddressName">
                                    <div class="invalid-feedback">Please enter a valid address</div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                        id="validateStartAddress">Validate Address</button>
                                </div>
                            </div>
                        </div>

                        <!-- End Destination Section -->
                        <div class="card mb-4" style="background: linear-gradient(rgba(255, 255, 255, 0.58), rgba(85, 85, 85, 0.58)), url('Extras/Controllers/BulgarianMap.png');
                             background-repeat: no-repeat;
                             background-attachment: fixed;
                             background-size: cover; border-radius: 25px; color: white;">
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
                                    <div class="invalid-feedback">Please select a start location.</div>
                                    <!-- Add this line ^ -->
                                </div>

                                <!-- Office Selection -->
                                <div class="form-group mb-3" id="endOfficeGroup">
                                    <label for="endOfficeSelect">Select Office:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control office-autocomplete" id="endOfficeInput"
                                            placeholder="Type to search offices">
                                        <select class="form-control d-none" id="endOfficeSelect" name="endOffice">
                                            <option value="">Select an office</option>
                                        </select>
                                        <input type="hidden" id="endOfficeCoords" name="endOfficeCoords">
                                        <input type="hidden" id="endOfficeName" name="endOfficeName">
                                    </div>
                                    <div id="endOfficeAutocomplete" class="autocomplete-results"></div>
                                </div>

                                <!-- Address Input -->
                                <div class="form-group mb-3 d-none" id="endAddressGroup">
                                    <label for="endAddressInput">Enter Address:</label>
                                    <input type="text" class="form-control" id="endAddressInput" name="endAddress"
                                        placeholder="Enter full address">
                                    <input type="hidden" id="endAddressCoords" name="endAddressCoords">
                                    <input type="hidden" id="endAddressName" name="endAddressName">
                                    <div class="invalid-feedback">Please enter a valid address</div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                        id="validateEndAddress">Validate Address</button>
                                </div>
                            </div>
                        </div>

                        <!-- Map preview: DEBUG -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5>Route Preview</h5>
                            </div>
                            <div class="card-body">
                                <div id="map-container" class="map-loading">
                                    <div class="map-loading-indicator">
                                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
                                            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
                                            crossorigin="" />
                                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                                            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                                            crossorigin=""></script>
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading map...</span>
                                        </div>
                                        <p class="mt-2">Loading map...</p>
                                        <input type="hidden" id="deliveryTimeHours" name="delivery_time_hours" value="">
                                        <input type="hidden" id="deliveryTimeRemMins" name="delivery_time_minutes"
                                            value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Map preview: DEBUG ^^^ -->

                        <!-- Delivery Information Section -->
                        <div class="col-md-12">
                            <div class="row align-items-end mb-3 ">
                                <div class="col-md-6">
                                    <label for="deliveryDate" class="form-label">Date of Delivery</label>
                                    <input type="text" class="form-control" id="deliveryDate" name="delivery_date"
                                        autocomplete="off">
                                    <span id="dynamic-late-note-2"></span>
                                </div>

                                <!-- Time of Delivery --> <!-- Broken -->
                                <div class="col-md-6">
                                    <!-- <label for="timeOfDelivery" class="form-label">Time of Delivery: <span
                                            id="currentTimeLabel"></span></label> -->
                                    <label for="timeOfDelivery" class="form-label"><span
                                            id="currentTimeLabel"></span></label>
                                    <!-- <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-clock text-primary"></i></span>
                                        <input type="text" class="form-control" id="timeOfDelivery"
                                            name="time_of_delivery" readonly autocomplete="off">
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- Payment Column -->
                        <div class="col-md-12">
                            <!-- Unneeded. -->
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Product Price</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                    <input type="text" class="form-control" id="productPrice" name="product_price"
                                        readonly>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted mt-3">
                                    <strong>Please note:</strong><br>
                                    <span class="note-indicator"></span>
                                    If delivery is far from current date
                                    (<?php echo date($tpl['date_format']) ?>), e.g., in 7 days, we will
                                    notify via
                                    email and in-site messages 3 days prior that your delivery will be processed
                                    soon.
                                    On
                                    the day of the delivery, we will notify again that the palette is on its
                                    way.
                                    <br>
                                    <span class="note-indicator"></span>
                                    If you see your time differing from the order delivery time, it is that you
                                    are
                                    making
                                    it out of working hours.
                                    <span id="dynamic-late-note"></span>
                                    <br>
                                    <strong>Reminder:</strong> You can keep track of couriers if you feel
                                    anxious (or
                                    just
                                    curious), via the map tracking!
                                </p>
                            </div>
                            <!-- Reminder for paragraph, add link once map tracking is done. ^^^  -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary text-white me-0">Create
                                        Order</button>
                                    <a href="javascript:" id="calculate-price-btn-id"
                                        class="btn btn-primary text-white me-0">Calculate Price</a>
                                    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"
                                        class="btn btn-outline-dark">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Footer -->
            <?php
            $userModel = new App\Models\User();
            $root = $userModel->getFirstBy(['role' => 'root']);
            $root_name = $root['name'];
            $root_phone = $root['phone_number'];
            $root_email = $root['email'];
            ?>
            <footer class="footer">
                <div class="container">
                    <div class="footer-content">
                        <div class="footer-section">
                            <h3 class="footer-title">Elec-Transport</h3>
                            <p>Providing quality transportation services nationwide since 2016.</p>
                        </div>
                        <div class="footer-section">
                            <h3 class="footer-title">Contact Us</h3>
                            <ul class="footer-list">
                                <li>Varna 9020 - Boul. Yanosh Huniadi 192</li>
                                <li><?php echo $root_phone ?></li>
                                <li><?php echo $root_email ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="copyright">
                        <p>&copy; 2025 Elec-Transport. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>


<!-- Visual appeal for notes -->
<style>
    .payment-error {
        color: #dc3545;
        margin-top: 0.5rem;
    }

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
    function updateCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();

        // Format with leading zeros
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        const currentTimeWithSeconds = `${hours}:${minutes}:${seconds}`;
        // const currentTimeWithMinutes = `${hours}:${minutes}`;

        document.getElementById('currentTimeLabel').textContent = `(Current Time: ${currentTimeWithSeconds})`;
        // document.getElementById('timeOfDelivery').value = currentTimeWithMinutes;
    }

    // Update the time immediately
    updateCurrentTime();

    // Update the time every second
    setInterval(updateCurrentTime, 1000);
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
                document.getElementById(coordsId.replace('Coords', 'Name')).value = office.city;

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
                            document.getElementById(coordsId.replace('Coords', 'Name')).value = input.value;

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
        if (!orderMap)
            return;

        // Clear existing markers and route
        if (startMarker)
            orderMap.removeLayer(startMarker);
        if (endMarker)
            orderMap.removeLayer(endMarker);
        if (routeLayer)
            orderMap.removeLayer(routeLayer);

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

                    // Calculate delivery time (example - you might need to adjust this)
                    let durationInHours = Math.floor(duration / 60); // Convert seconds to hours
                    let remaining_minutes = duration % 60; // Remaining minutes

                    // Set the values of the hidden inputs
                    document.getElementById('deliveryTimeHours').value = durationInHours;
                    document.getElementById('deliveryTimeRemMins').value = remaining_minutes;

                    // Update end marker popup with route info
                    if (endMarker) {
                        endMarker.setPopupContent(`End Location<br>
                            Distance: ${distance} km<br>
                            Estimated time: ${durationInHours}h ${remaining_minutes}min`);
                        endMarker.openPopup();
                    }
                }
            })
            .catch(error => console.error('Error fetching route:', error));
    }

    // Form validation before submission
    function setupFormValidation() {
        document.getElementById('booking-frm-id').addEventListener('submit', function (event) {
            let isValid = true;

            // Debug to see values
            console.log('Start Office Name:', document.getElementById('startOfficeName').value);
            console.log('Start Address Name:', document.getElementById('startAddressName').value);
            console.log('End Office Name:', document.getElementById('endOfficeName').value);
            console.log('End Address Name:', document.getElementById('endAddressName').value);

            // Validate start location
            if (document.getElementById('startOffice').checked) {
                if (!document.getElementById('startOfficeCoords').value) {
                    document.getElementById('startOfficeInput').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('startOfficeInput').classList.remove('is-invalid');
                }
            } else {
                if (!document.getElementById('startAddressCoords').value) {
                    document.getElementById('startAddressInput').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('startAddressInput').classList.remove('is-invalid');
                }
            }

            // Validate end location
            if (document.getElementById('endOffice').checked) {
                if (!document.getElementById('endOfficeCoords').value) {
                    document.getElementById('endOfficeInput').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('endOfficeInput').classList.remove('is-invalid');
                }
            } else {
                if (!document.getElementById('endAddressCoords').value) {
                    document.getElementById('endAddressInput').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('endAddressInput').classList.remove('is-invalid');
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

<!-- Payment -->
<script>
    // Payment method handling
    document.addEventListener('DOMContentLoaded', function () {
        // Get payment method radio buttons
        const paymentOnline = document.getElementById('paymentOnline');
        const paymentCash = document.getElementById('paymentCash');

        // Quantity change event listener
        const quantityListener = document.getElementById('quantities');

        //Add event listeners to payment method radios
        if (paymentOnline || paymentCash || quantityListener) {
            paymentOnline.addEventListener('change', calculatePrice);
            paymentCash.addEventListener('change', calculatePrice);
            quantityListener.addEventListener('change', calculatePrice);
        }

        // Get the form and add submit event listener
        const orderForm = document.getElementById('booking-frm-id');

        if (orderForm) {
            orderForm.addEventListener('submit', function (event) {
                // Always prevent default initially
                event.preventDefault();

                // Run all validations
                if (validateFullForm()) {
                    // Calculate price before showing popup
                    calculatePrice();

                    // Show payment popup based on selected method
                    const isOnlinePayment = document.getElementById('paymentOnline').checked;
                    if (isOnlinePayment) {
                        showPaymentPopup();
                    } else {
                        showCashConfirmationPopup();
                    }
                }
            });
        }

        // // Set up the calculate price button
        const calculatePriceBtn = document.getElementById('calculate-price-btn-id');
        if (calculatePriceBtn) {
            calculatePriceBtn.addEventListener('click', calculatePrice);
        }
    });

    function calculatePrice() {
        const form = document.getElementById('booking-frm-id');
        const formData = new FormData(form);

        fetch('<?php echo INSTALL_URL; ?>?controller=Order&action=calculatePrice', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById('productPrice').value = data.product_price;
                    // document.getElementById('totalPrice').value = data.total;
                }
            })
            .catch(error => {
                console.error('Error calculating price:', error);
            });
    }

    // Comprehensive form validation function
    function validateFullForm() {
        let isValid = true;

        // Validate start location
        let startLocationValid = true;
        let startLocationGroup = document.querySelector('input[name="startLocationType"]:checked');
        let startLocationFeedback;

        if (!startLocationGroup) {
            startLocationValid = false;
            // Target the invalid feedback directly under the radio buttons
            startLocationFeedback = document.querySelector('.card-body > .form-group > .invalid-feedback');
            if (startLocationFeedback) {
                startLocationFeedback.style.display = 'block'; // Show the error message
                startLocationFeedback.textContent = "Please select a start location type.";
            }
        } else {
            if (document.getElementById('startOffice').checked) {
                if (!document.getElementById('startOfficeCoords').value) {
                    document.getElementById('startOfficeInput').classList.add('is-invalid');
                    const feedback = document.querySelector('#startOfficeGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.textContent = "Please select a start office.";
                    }
                    startLocationValid = false;
                } else {
                    document.getElementById('startOfficeInput').classList.remove('is-invalid');
                    const feedback = document.querySelector('#startOfficeGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                }
            } else if (document.getElementById('startAddress').checked) {
                if (!document.getElementById('startAddressCoords').value) {
                    document.getElementById('startAddressInput').classList.add('is-invalid');
                    const feedback = document.querySelector('#startAddressGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.textContent = "Please enter a start address.";
                    }
                    startLocationValid = false;
                } else {
                    document.getElementById('startAddressInput').classList.remove('is-invalid');
                    const feedback = document.querySelector('#startAddressGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                }
            } else {
                startLocationValid = false;
            }
        }

        if (!startLocationValid) {
            isValid = false;
        }

        // Validate end location
        let endLocationValid = true;
        let endLocationGroup = document.querySelector('input[name="endLocationType"]:checked');
        let endLocationFeedback = document.querySelector('.card-body > .form-group > .invalid-feedback');

        if (!endLocationGroup) {
            endLocationValid = false;
            if (endLocationFeedback) {
                endLocationFeedback.style.display = 'block'; // Show the error message
                endLocationFeedback.textContent = "Please select an end location type.";
            }
        } else {
            if (document.getElementById('endOffice').checked) {
                if (!document.getElementById('endOfficeCoords').value) {
                    document.getElementById('endOfficeInput').classList.add('is-invalid');
                    const feedback = document.querySelector('#endOfficeGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.textContent = "Please select an end office.";
                    }
                    endLocationValid = false;
                } else {
                    document.getElementById('endOfficeInput').classList.remove('is-invalid');
                    const feedback = document.querySelector('#endOfficeGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                }
            } else if (document.getElementById('endAddress').checked) {
                if (!document.getElementById('endAddressCoords').value) {
                    document.getElementById('endAddressInput').classList.add('is-invalid');
                    const feedback = document.querySelector('#endAddressGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.textContent = "Please enter an end address.";
                    }
                    endLocationValid = false;
                } else {
                    document.getElementById('endAddressInput').classList.remove('is-invalid');
                    const feedback = document.querySelector('#endAddressGroup .invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                }
            } else {
                endLocationValid = false;
            }
        }

        if (!endLocationValid) {
            isValid = false;
        }

        // Validate courier selection
        const courierSelect = document.getElementById('courierId');
        if (courierSelect.value === '') {
            courierSelect.classList.add('is-invalid');
            isValid = false;
        } else {
            courierSelect.classList.remove('is-invalid');
        }

        // Validate parcel and quantity
        const parcelSelect = document.getElementById('parcelIds');
        const quantityInput = document.getElementById('quantities');

        if (parcelSelect.value === '') {
            parcelSelect.classList.add('is-invalid');
            isValid = false;
        } else {
            parcelSelect.classList.remove('is-invalid');

            // Only validate quantity if parcel is selected
            if (quantityInput.value === '' || isNaN(parseInt(quantityInput.value)) || parseInt(quantityInput.value) <= 0) {
                quantityInput.classList.add('is-invalid');
                isValid = false;
            } else {
                const maxQuantity = parseInt(parcelSelect.options[parcelSelect.selectedIndex].getAttribute('data-max-quantity'));
                if (parseInt(quantityInput.value) > maxQuantity) {
                    quantityInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    quantityInput.classList.remove('is-invalid');
                }
            }
        }

        // Add this to your validateFullForm function
        // Validate payment method
        const paymentOnline = document.getElementById('paymentOnline');
        const paymentCash = document.getElementById('paymentCash');
        if (!paymentOnline.checked && !paymentCash.checked) {
            // Find the payment method container and add an error message
            const paymentMethodContainer = document.querySelector('.payment-methods .card-body');
            if (paymentMethodContainer) {
                // Create an error message element if it doesn't exist
                let errorMessage = paymentMethodContainer.querySelector('.payment-error');
                if (!errorMessage) {
                    errorMessage = document.createElement('div');
                    errorMessage.className = 'invalid-feedback payment-error d-block';
                    paymentMethodContainer.appendChild(errorMessage);
                }
                errorMessage.textContent = 'Please select a payment method.';
                errorMessage.style.display = 'block';
            }
            isValid = false;
        } else {
            // Remove error message if it exists
            const errorMessage = document.querySelector('.payment-methods .card-body .payment-error');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }

        // Validate delivery date
        const deliveryDate = document.getElementById('deliveryDate');
        if (!deliveryDate.value) {
            deliveryDate.classList.add('is-invalid');
            isValid = false;
        } else {
            deliveryDate.classList.remove('is-invalid');
        }

        // If any validation failed, show alert
        if (!isValid) {
            alert('Please complete all required fields before proceeding.');
        }

        return isValid;
    }

    // Handle form submission
    function handleFormSubmit(event) {
        event.preventDefault();

        let isValid = true;

        // Validate start location
        let startLocationValid = true;
        let startLocationGroup = document.querySelector('input[name="startLocationType"]:checked');
        let startLocationFeedback = document.querySelector('#startOfficeGroup .invalid-feedback');

        if (!startLocationGroup) {
            startLocationValid = false;
            startLocationFeedback.style.display = 'block'; // Show the error message
            startLocationFeedback.textContent = "Please select a start location type.";
        } else {
            if (document.getElementById('startOffice').checked) {
                if (!document.getElementById('startOfficeCoords').value) {
                    document.getElementById('startOfficeInput').classList.add('is-invalid');
                    document.querySelector('#startOfficeGroup .invalid-feedback').style.display = 'block';
                    document.querySelector('#startOfficeGroup .invalid-feedback').textContent = "Please select a start office.";
                    startLocationValid = false;
                } else {
                    document.getElementById('startOfficeInput').classList.remove('is-invalid');
                    document.querySelector('#startOfficeGroup .invalid-feedback').style.display = 'none';
                    startLocationFeedback.style.display = 'none'; // Hide the error message
                }
            } else if (document.getElementById('startAddress').checked) {
                if (!document.getElementById('startAddressCoords').value) {
                    document.getElementById('startAddressInput').classList.add('is-invalid');
                    document.querySelector('#startAddressGroup .invalid-feedback').style.display = 'block';
                    document.querySelector('#startAddressGroup .invalid-feedback').textContent = "Please enter a start address.";
                    startLocationValid = false;
                } else {
                    document.getElementById('startAddressInput').classList.remove('is-invalid');
                    document.querySelector('#startAddressGroup .invalid-feedback').style.display = 'none';
                    startLocationFeedback.style.display = 'none'; // Hide the error message
                }
            } else {
                startLocationValid = false;
            }
        }

        // Validate end location
        let endLocationValid = true;
        let endLocationGroup = document.querySelector('input[name="endLocationType"]:checked');
        let endLocationFeedback = document.querySelector('#endOfficeGroup .invalid-feedback');

        if (!endLocationGroup) {
            endLocationValid = false;
            endLocationFeedback.style.display = 'block'; // Show the error message
            endLocationFeedback.textContent = "Please select an end location type.";
        } else {
            endLocationFeedback.style.display = 'none'; // Hide the error message
            if (document.getElementById('endOffice').checked) {
                if (!document.getElementById('endOfficeCoords').value) {
                    document.getElementById('endOfficeInput').classList.add('is-invalid');
                    document.querySelector('#endOfficeGroup .invalid-feedback').style.display = 'block';
                    document.querySelector('#endOfficeGroup .invalid-feedback').textContent = "Please select an end office.";
                    endLocationValid = false;
                } else {
                    document.getElementById('endOfficeInput').classList.remove('is-invalid');
                    document.querySelector('#endOfficeGroup .invalid-feedback').style.display = 'none';
                }
            } else if (document.getElementById('endAddress').checked) {
                if (!document.getElementById('endAddressCoords').value) {
                    document.getElementById('endAddressInput').classList.add('is-invalid');
                    document.querySelector('#endAddressGroup .invalid-feedback').style.display = 'block';
                    document.querySelector('#endAddressGroup .invalid-feedback').textContent = "Please enter an end address.";
                    endLocationValid = false;
                } else {
                    document.getElementById('endAddressInput').classList.remove('is-invalid');
                    document.querySelector('#endAddressGroup .invalid-feedback').style.display = 'none';
                }
            } else {
                endLocationValid = false;
            }
        }

        if (!endLocationValid) {
            isValid = false;
        }

        // Validate courier selection
        const courierSelect = document.getElementById('courierId');
        if (courierSelect.value === '') {
            courierSelect.classList.add('is-invalid');
            isValid = false;
        } else {
            courierSelect.classList.remove('is-invalid');
        }

        if (!isValid) {
            alert('Please make sure all locations and courier are valid before proceeding.');
            return;
        }

        // Perform form validation (you can add your own validation logic here)
        if (!validateOrderForm()) {
            return;
        }

        // Check which payment method is selected
        const isOnlinePayment = document.getElementById('paymentOnline').checked;
        const isCashPayment = document.getElementById('paymentCash').checked;

        if (isOnlinePayment) {
            // Show online payment popup
            showPaymentPopup();
        } else {
            // Show cash confirmation popup
            showCashConfirmationPopup();
        }
    }
    // Show online payment popup
    function showPaymentPopup() {
        // Create the payment popup overlay
        const overlay = document.createElement('div');
        overlay.className = 'payment-overlay';

        // Create popup content
        const popup = document.createElement('div');
        popup.className = 'payment-popup';
        popup.innerHTML = `
        <div class="payment-popup-header">
            <h3>Online Payment</h3>
            <button type="button" class="close-popup">&times;</button>
        </div>
        <div class="payment-popup-body">
            <div class="mb-3">
                <label for="cardNumber" class="form-label">Card Number</label>
                <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="expDate" class="form-label">Expiration Date</label>
                    <input type="text" class="form-control" id="expDate" placeholder="MM/YY">
                </div>
                <div class="col-md-6">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" placeholder="123">
                </div>
            </div>
            <div class="mb-3">
                <label for="cardName" class="form-label">Cardholder Name</label>
                <input type="text" class="form-control" id="cardName" placeholder="John Doe">
            </div>
            <div class="payment-amount">
                <strong>Total Amount: </strong>
                <span>${document.getElementById('productPrice').value} <?php echo $tpl['currency']; ?></span>
            </div>
        </div>
        <div class="payment-popup-footer">
            <button type="button" class="btn btn-secondary cancel-payment">Cancel</button>
            <button type="button" class="btn btn-primary process-payment">Process Payment</button>
        </div>
    `;

        // Add popup to overlay
        overlay.appendChild(popup);

        // Add overlay to document
        document.body.appendChild(overlay);

        // Add event listeners to buttons
        const closeButton = overlay.querySelector('.close-popup');
        const cancelButton = overlay.querySelector('.cancel-payment');
        const processButton = overlay.querySelector('.process-payment');

        closeButton.addEventListener('click', () => {
            document.body.removeChild(overlay);
        });

        cancelButton.addEventListener('click', () => {
            document.body.removeChild(overlay);
        });

        processButton.addEventListener('click', () => {
            // Here you would normally process the payment
            // For this example, we'll just show a success message and redirect
            alert('Payment processed successfully!');
            document.body.removeChild(overlay);

            // Submit the form and redirect to homepage
            const form = document.getElementById('booking-frm-id');
            form.removeEventListener('submit', handleFormSubmit); // Remove event listener to avoid recursion
            form.submit();
            // This is where you would normally redirect to homepage after form submission
            // window.location.href = "index.php";
        });
    }

    // Show cash confirmation popup
    function showCashConfirmationPopup() {
        // Create the confirmation overlay
        const overlay = document.createElement('div');
        overlay.className = 'payment-overlay';

        // Create popup content
        const popup = document.createElement('div');
        popup.className = 'payment-popup';
        popup.innerHTML = `
        <div class="payment-popup-header">
            <h3>Cash Payment Confirmation</h3>
            <button type="button" class="close-popup">&times;</button>
        </div>
        <div class="payment-popup-body">
            <p>You have selected Cash on Delivery as your payment method.</p>
            <p>A 1.5% fee has been added to your total amount.</p>
            <div class="payment-amount">
                <strong>Total Amount to Pay on Delivery: </strong>
                <span>${document.getElementById('productPrice').value}  <?php echo $tpl['currency']; ?></span>
            </div>
        </div>
        <div class="payment-popup-footer">
            <button type="button" class="btn btn-secondary cancel-payment">Cancel</button>
            <button type="button" class="btn btn-primary confirm-order">Confirm Order</button>
        </div>
    `;

        // Add popup to overlay
        overlay.appendChild(popup);

        // Add overlay to document
        document.body.appendChild(overlay);

        // Add event listeners to buttons
        const closeButton = overlay.querySelector('.close-popup');
        const cancelButton = overlay.querySelector('.cancel-payment');
        const confirmButton = overlay.querySelector('.confirm-order');

        closeButton.addEventListener('click', () => {
            document.body.removeChild(overlay);
        });

        cancelButton.addEventListener('click', () => {
            document.body.removeChild(overlay);
        });

        confirmButton.addEventListener('click', () => {
            // Submit the form directly for cash payment
            document.body.removeChild(overlay);

            // Submit the form and redirect to homepage
            const form = document.getElementById('booking-frm-id');
            form.removeEventListener('submit', handleFormSubmit); // Remove event listener to avoid recursion
            form.submit();
            // This is where you would normally redirect to homepage after form submission
            // window.location.href = "index.php";
        });
    }
</script>
<style>
    /* Payment Popup Styles */
    .payment-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .payment-popup {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 500px;
        overflow: hidden;
    }

    .payment-popup-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .payment-popup-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 500;
    }

    .close-popup {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .payment-popup-body {
        padding: 20px;
    }

    .payment-popup-footer {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .payment-amount {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 5px;
        margin-top: 15px;
    }
</style>

<!-- Quantity and Parcels -->
<script>
    // Improved quantity validation with consistent error styling
    document.addEventListener('DOMContentLoaded', function () {
        const parcelSelect = document.getElementById('parcelIds');
        const quantityInput = document.getElementById('quantities');
        const quantityContainer = quantityInput.parentElement;

        // Add invalid feedback div if it doesn't exist
        let invalidFeedback = quantityContainer.querySelector('.invalid-feedback');
        if (!invalidFeedback) {
            invalidFeedback = document.createElement('div');
            invalidFeedback.className = 'invalid-feedback';
            quantityContainer.appendChild(invalidFeedback);
        }

        // Set max quantity when product is selected
        if (parcelSelect && quantityInput) {
            parcelSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];

                // Reset validation state
                quantityInput.classList.remove('is-invalid');
                quantityInput.classList.remove('is-valid');

                if (selectedOption && selectedOption.value !== '') {
                    const maxQuantity = selectedOption.getAttribute('data-max-quantity');

                    // Set max attribute and clear current value
                    quantityInput.max = maxQuantity;
                    quantityInput.value = '1'; // Default to 1

                    // Add placeholder showing max available quantity
                    quantityInput.placeholder = `Max: ${maxQuantity}`;

                    // Make sure the input is no longer disabled
                    quantityInput.disabled = false;
                } else {
                    // No product selected, disable quantity input
                    quantityInput.disabled = true;
                    quantityInput.value = '';
                    quantityInput.placeholder = 'Select a product first!';
                }
            });

            // Validate input on change and input events
            quantityInput.addEventListener('input', validateQuantity);
            quantityInput.addEventListener('change', validateQuantity);

            // Initially disable quantity input until a product is selected
            quantityInput.disabled = true;
            quantityInput.placeholder = 'Select a product first!';
        }

        // Validation function
        function validateQuantity() {
            const maxQuantity = parseInt(this.max, 10);
            const value = parseInt(this.value, 10);

            // Remove previous validation state
            this.classList.remove('is-invalid');
            this.classList.remove('is-valid');

            if (isNaN(value) || value === '') {
                // Empty or not a number
                this.classList.add('is-invalid');
                invalidFeedback.textContent = 'Please enter a valid quantity';
                return false;
            } else if (value <= 0) {
                // Negative or zero
                this.classList.add('is-invalid');
                invalidFeedback.textContent = 'Quantity must be greater than zero';
                this.value = '';
                return false;
            } else if (value > maxQuantity) {
                // Exceeds maximum
                this.classList.add('is-invalid');
                invalidFeedback.textContent = `Maximum available quantity is ${maxQuantity}`;
                this.value = maxQuantity;
                return false;
            } else {
                // Valid
                this.classList.add('is-valid');
                return true;
            }
        }

        // Add validation to the form submission
        const orderForm = document.getElementById('booking-frm-id');
        if (orderForm) {
            const originalValidateOrderForm = validateOrderForm || function () {
                return true;
            };

            validateOrderForm = function () {
                let isValid = originalValidateOrderForm();

                // Check if product is selected
                if (parcelSelect.value === '') {
                    parcelSelect.classList.add('is-invalid');
                    isValid = false;
                }

                // Check quantity if product is selected
                if (parcelSelect.value !== '' && quantityInput.disabled === false) {
                    if (!validateQuantity.call(quantityInput)) {
                        isValid = false;
                    }
                }

                return isValid;
            };
        }
    });
</script>

<!-- Date of Delivery stuff -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the date and time input elements
        const deliveryDateInput = document.getElementById('deliveryDate');
        const deliveryTimeHoursInput = document.getElementById('deliveryTimeHours');
        const deliveryTimeMinutesInput = document.getElementById('deliveryTimeRemMins');
        const estimatedArrivalDisplay = document.getElementById('estimatedArrival'); // To display estimated arrival
        const timeOfDeliveryInput = document.getElementById('timeOfDelivery'); // The input for time of delivery
        const dynamicLateNote = document.getElementById('dynamic-late-note-2');

        let settings; // Declare settings globally to access in event listeners
        let dateFormat;

        // Initialize the date picker
        initializeDatePicker();

        function initializeDatePicker() {
            // Get today's date
            const today = new Date();

            // Fetch the delivery date information from the server
            fetch('<?php echo INSTALL_URL; ?>?controller=Order&action=checkDeliveryTime', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'current_time=' + today.getHours() + ':' + today.getMinutes()
            })
                .then(response => response.json())
                .then(data => {
                    // Store settings and date format for later use
                    settings = data.settings;
                    dateFormat = data.dateFormat || 'Y-m-d';

                    // Set up datepicker with the correct format
                    $(deliveryDateInput).datepicker({
                        format: convertPhpToDatepickerFormat(dateFormat),
                        startDate: today,
                        autoclose: true,
                        todayHighlight: true
                    }).on('changeDate', function (e) {
                        validateDeliveryDate(e.date);
                    });

                    // Set initial date - use the date from server or default to today
                    const initialDate = data.estimatedDeliveryDateHtml ?
                        new Date(data.estimatedDeliveryDateHtml) :
                        today;

                    // Update the datepicker with proper date
                    $(deliveryDateInput).datepicker('update', initialDate);

                    // Set the value directly for the display format
                    if (data.estimatedDeliveryDateFormatted) {
                        deliveryDateInput.value = data.estimatedDeliveryDateFormatted;
                    }

                    // Update time of delivery initially
                    updateTimeOfDelivery();

                })
                .catch(error => {
                    console.error('Error initializing delivery date:', error);
                    // Fallback to today's date
                    $(deliveryDateInput).datepicker('update', today);
                });
        }

        function convertPhpToDatepickerFormat(phpFormat) {
            // Map of PHP date format to Bootstrap datepicker format
            const formatMap = {
                'd': 'dd', // Day of the month, 2 digits with leading zeros
                'j': 'd', // Day of the month without leading zeros
                'm': 'mm', // Month, 2 digits with leading zeros
                'n': 'm', // Month without leading zeros
                'Y': 'yyyy', // Year, 4 digits
                'y': 'yy', // Year, 2 digits
                'F': 'MM', // Month name, long
                'M': 'M'      // Month name, short
            };

            // Simple conversion for common formats
            const commonFormats = {
                'd-m-Y': 'dd-mm-yyyy',
                'Y-m-d': 'yyyy-mm-dd',
                'm/d/Y': 'mm/dd/yyyy',
                'd/m/Y': 'dd/mm/yyyy',
                'Y/m/d': 'yyyy/mm/dd',
                'M d, Y': 'M d, Y'
            };

            // Check if it's a common format
            if (commonFormats[phpFormat]) {
                return commonFormats[phpFormat];
            }

            // Otherwise, convert character by character
            let datepickerFormat = phpFormat;
            for (const [phpChar, pickerChar] of Object.entries(formatMap)) {
                // Use regex with global flag to replace all occurrences
                const regex = new RegExp(phpChar, 'g');
                datepickerFormat = datepickerFormat.replace(regex, pickerChar);
            }

            return datepickerFormat;
        }

        function validateDeliveryDate(selectedDate) {
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time part for proper comparison

            // Reset to today if past date is selected
            if (selectedDate < today) {
                $(deliveryDateInput).datepicker('update', today);
                alert("You cannot select a past date. Today's date has been set.");
                return;
            }

            updateTimeOfDelivery();
        }

        function updateTimeOfDelivery() {
            if (!settings)
                return; // Ensure settings are loaded

            let deliveryHours = parseInt(document.getElementById('deliveryTimeHours').value) || 0;
            let deliveryMinutes = parseInt(document.getElementById('deliveryTimeRemMins').value) || 0;

            // If we don't have route information yet, use default values
            if (deliveryHours === 0 && deliveryMinutes === 0) {
                deliveryHours = 2; // Default to 2 hours
                deliveryMinutes = 0; // Default to 0 minutes
            }

            // Get the selected date and current time
            let selectedDateInput = deliveryDateInput.value;
            let selectedDate = selectedDateInput ? new Date(selectedDateInput) : new Date();
            // Fix time zone issue by ensuring the date is correctly set
            // This addresses the issue where the date might show as "two hours behind"
            if (selectedDate.toString() === "Invalid Date") {
                // Try to parse using the date format from the server
                let parts = selectedDateInput.split(/[-\/]/);
                if (parts.length === 3) {
                    // Determine the format based on the dateFormat
                    if (dateFormat.indexOf('Y') === 0) {
                        // Format is Y-m-d or Y/m/d
                        selectedDate = new Date(parts[0], parts[1] - 1, parts[2]);
                    } else if (dateFormat.indexOf('d') === 0) {
                        // Format is d-m-Y or d/m/Y
                        selectedDate = new Date(parts[2], parts[1] - 1, parts[0]);
                    } else {
                        // Format is m/d/Y or similar
                        selectedDate = new Date(parts[2], parts[0] - 1, parts[1]);
                    }
                }
            }

            let currentTime = Math.floor(selectedDate.getTime() / 1000); // Current timestamp in seconds

            function calculateArrivalTime(currentTime, deliveryHours, deliveryMinutes, settings) {
                // Get current timestamp and date
                let deliveryTimestamp = (deliveryHours * 3600) + (deliveryMinutes * 60);
                let now = new Date(); // Current actual time

                // Extract the selected date from the datepicker
                let selectedDateStr = document.getElementById('deliveryDate').value;
                let selectedDate = new Date(selectedDateStr);

                // Format to ensure proper date comparison
                let selectedDateFormatted = formatDate(selectedDate, 'Y-m-d');
                let currentDateFormatted = formatDate(now, 'Y-m-d');

                // Get business hours
                let effectiveOpeningTime = parseTime(settings.opening_time);
                let effectiveClosingTime = parseTime(settings.closing_time);

                // Check if the selected date is different from today
                if (selectedDateFormatted !== currentDateFormatted) {
                    console.log("Future date selected:", selectedDateFormatted);

                    // Get day of week for the selected delivery date
                    let deliveryDayOfWeek = selectedDate.getDay() === 0 ? 7 : selectedDate.getDay(); // 1 (Monday) to 7 (Sunday)
                    let isWeekend = (deliveryDayOfWeek >= 6); // Saturday or Sunday

                    // Check weekend operation
                    if (isWeekend && settings.weekend_operation == 0) {
                        // Weekend delivery requested but weekend operations are disabled
                        // Find the next Monday
                        let daysUntilMonday = (8 - deliveryDayOfWeek) % 7;
                        let nextMonday = new Date(selectedDate);
                        nextMonday.setDate(selectedDate.getDate() + daysUntilMonday);

                        // Use opening time of the next Monday
                        let nextMondayOpening = new Date(nextMonday);
                        nextMondayOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                        let adjustedTimestamp = nextMondayOpening.getTime() / 1000 + deliveryTimestamp;

                        return formatDate(new Date(adjustedTimestamp * 1000), dateFormat) + ' ' +
                            formatTime(new Date(adjustedTimestamp * 1000));
                    } else {
                        // Use the appropriate opening/closing times based on whether it's a weekend
                        if (isWeekend && settings.weekend_operation == 1) {
                            effectiveOpeningTime = parseTime(settings.weekend_opening_time);
                            effectiveClosingTime = parseTime(settings.weekend_closing_time);
                        }

                        // Use the opening time of the selected date plus delivery time
                        let selectedDateOpening = new Date(selectedDate);
                        selectedDateOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                        let scheduledTimestamp = selectedDateOpening.getTime() / 1000 + deliveryTimestamp;

                        return formatDate(new Date(scheduledTimestamp * 1000), dateFormat) + ' ' +
                            formatTime(new Date(scheduledTimestamp * 1000));
                    }
                }

                // For same-day delivery (original logic)
                let dayOfWeek = now.getDay() === 0 ? 7 : now.getDay(); // 1 for Monday, 7 for Sunday
                let currentHourMinute = formatTime(now);

                // Handle weekend operation for current day
                if (settings.weekend_operation == 1) {
                    if (dayOfWeek >= 6) { // Saturday or Sunday
                        effectiveOpeningTime = parseTime(settings.weekend_opening_time);
                        effectiveClosingTime = parseTime(settings.weekend_closing_time);
                    }
                } else {
                    if (dayOfWeek >= 6) { // Saturday or Sunday
                        // Find the next Monday
                        let daysUntilMonday = (8 - dayOfWeek) % 7;
                        let nextMonday = new Date(now);
                        nextMonday.setDate(now.getDate() + daysUntilMonday);
                        let nextMondayOpening = new Date(nextMonday);
                        nextMondayOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                        let arrivalTimestamp = nextMondayOpening.getTime() / 1000 + deliveryTimestamp;

                        return formatDate(new Date(arrivalTimestamp * 1000), dateFormat) + ' ' +
                            formatTime(new Date(arrivalTimestamp * 1000));
                    }
                }

                // Calculate arrival time for current day
                let arrivalTimestamp = currentTime + deliveryTimestamp;
                let arrivalDate = new Date(arrivalTimestamp * 1000);
                let arrivalHourMinute = formatTime(arrivalDate);

                // Check if arrival time is after closing time
                if (timeToMinutes(arrivalHourMinute) > timeToMinutes(formatTime(new Date(effectiveClosingTime)))) {
                    // Schedule for next day opening
                    let nextDay = new Date(now);
                    nextDay.setDate(now.getDate() + 1);
                    let nextDayOpening = new Date(nextDay);
                    nextDayOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                    arrivalTimestamp = nextDayOpening.getTime() / 1000 + deliveryTimestamp;

                    return formatDate(new Date(arrivalTimestamp * 1000), dateFormat) + ' ' +
                        formatTime(new Date(arrivalTimestamp * 1000));
                }
                // Check if current time is before opening time
                else if (timeToMinutes(currentHourMinute) < timeToMinutes(formatTime(new Date(effectiveOpeningTime)))) {
                    // Start from today's opening time
                    let todayOpening = new Date(now);
                    todayOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                    arrivalTimestamp = todayOpening.getTime() / 1000 + deliveryTimestamp;

                    return formatDate(new Date(arrivalTimestamp * 1000), dateFormat) + ' ' +
                        formatTime(new Date(arrivalTimestamp * 1000));
                }
                // Check if current time is after cut-off time
                else if (timeToMinutes(currentHourMinute) > timeToMinutes(settings.order_cut_off_time)) {
                    // Schedule for next business day
                    let nextDay = new Date(now);
                    nextDay.setDate(now.getDate() + 1);
                    let nextDayOfWeek = nextDay.getDay() === 0 ? 7 : nextDay.getDay();

                    if (settings.weekend_operation == 0 && nextDayOfWeek >= 6) {
                        // If weekend operation is off and next day is weekend, find next Monday
                        let daysUntilMonday = (8 - nextDayOfWeek) % 7;
                        let nextMonday = new Date(nextDay);
                        nextMonday.setDate(nextDay.getDate() + daysUntilMonday);
                        let nextMondayOpening = new Date(nextMonday);
                        nextMondayOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                        arrivalTimestamp = nextMondayOpening.getTime() / 1000 + deliveryTimestamp;

                        return formatDate(new Date(arrivalTimestamp * 1000), dateFormat) + ' ' +
                            formatTime(new Date(arrivalTimestamp * 1000));
                    } else {
                        // Next day is a business day
                        let nextBusinessDayOpening = new Date(nextDay);
                        nextBusinessDayOpening.setHours(effectiveOpeningTime.getHours(), effectiveOpeningTime.getMinutes(), 0);
                        arrivalTimestamp = nextBusinessDayOpening.getTime() / 1000 + deliveryTimestamp;

                        return formatDate(new Date(arrivalTimestamp * 1000), dateFormat) + ' ' +
                            formatTime(new Date(arrivalTimestamp * 1000));
                    }
                } else {
                    // Within business hours, use calculated arrival time
                    return formatDate(new Date(arrivalTimestamp * 1000), dateFormat) + ' ' +
                        formatTime(new Date(arrivalTimestamp * 1000));
                }
            }
            function parseTime(timeString) {
                const [hours, minutes] = timeString.split(':').map(Number);
                const date = new Date();
                date.setHours(hours);
                date.setMinutes(minutes);
                date.setSeconds(0);
                date.setMilliseconds(0);
                return date;
            }

            function timeToMinutes(timeString) {
                const [hours, minutes] = timeString.split(':').map(Number);
                return hours * 60 + minutes;
            }

            function formatDate(date, format) {
                // Get date components
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                // Create a copy of the format string to avoid modifying the original
                let formattedDate = format;

                // Replace format tokens with actual values
                // Use specific replacement approach to avoid multiple replacements
                formattedDate = formattedDate.replace(/Y+/g, year);
                formattedDate = formattedDate.replace(/m+/g, month);
                formattedDate = formattedDate.replace(/d+/g, day);

                return formattedDate;
            }

            function formatTime(date) {
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${hours}:${minutes}`;
            }

            let estimatedArrival = calculateArrivalTime(currentTime, deliveryHours, deliveryMinutes, settings);

            console.log("Estimated Arrival:", estimatedArrival); // ADD THIS LINE

            if (estimatedArrivalDisplay) {
                estimatedArrivalDisplay.textContent = "Estimated Arrival: " + estimatedArrival;
            }

            if (timeOfDeliveryInput) {
                timeOfDeliveryInput.value = estimatedArrival; // Update timeOfDelivery input
            }

            // Show notification if needed
            if (settings.order_cut_off_time && formatTime(new Date()) > settings.order_cut_off_time) {
                showLateNotification();
            }
        }

        function showLateNotification() {
            if (dynamicLateNote) {
                dynamicLateNote.textContent = " It's too late for delivery today. Your order has been scheduled for the next available day.";
                dynamicLateNote.style.color = "#dc3545";

                // Fade out the notification after 5 seconds
                setTimeout(() => {
                    dynamicLateNote.style.transition = "opacity 1s";
                    dynamicLateNote.style.opacity = 0;
                    setTimeout(() => {
                        dynamicLateNote.textContent = "";
                        dynamicLateNote.style.opacity = 1;
                        dynamicLateNote.style.transition = "";
                    }, 1000);
                }, 5000);
            }
        }

        // Event listeners for date and time changes
        if (deliveryDateInput) {
            deliveryDateInput.addEventListener('change', updateTimeOfDelivery);
        }
        if (deliveryTimeHoursInput) {
            deliveryTimeHoursInput.addEventListener('change', updateTimeOfDelivery);
        }
        if (deliveryTimeMinutesInput) {
            deliveryTimeMinutesInput.addEventListener('change', updateTimeOfDelivery);
        }

    });
</script>

<!-- For Footer -->
<style>
    /* General Styles */
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --accent-color: #e74c3c;
        --light-bg: #f8f9fa;
        --dark-bg: #343a40;
        --text-color: #333;
        --light-text: #f8f9fa;
    }

    body {
        font-family: 'Roboto', Arial, sans-serif;
        color: var(--text-color);
        line-height: 1.6;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        background-color: rgba(245, 245, 245, 0);
    }

    /* Override Bootstrap white boxes to make transparent */
    .card,
    .card-body {
        background-color: transparent !important;
        border: none !important;
    }

    /* Hero Section Styles */
    .hero-section {
        background: linear-gradient(rgba(44, 62, 80, 0.7), rgba(44, 62, 80, 0.7)), url('https://images.unsplash.com/photo-1592838064575-70ed626d3a0e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
        background-size: cover;
        border-radius: 25px;
        color: #fff;
        text-align: center;
        padding: 100px 0;
        margin-bottom: 50px;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: rgb(25, 13, 190);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-subtitle {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: rgb(255, 255, 255);
    }

    .hero-description {
        font-size: 1.2rem;
        max-width: 800px;
        margin: 0 auto;
        color: rgb(255, 255, 255);
    }

    /* Feature Cards Styles */
    .feature-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 50px;
    }

    .feature-card {
        flex: 0 0 calc(33.333% - 20px);
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 20px rgba(0, 0, 0, 0.2);
    }

    .feature-card-bg {
        height: 200px;
        background-size: cover;
        background-position: center;
    }

    .feature-card-content {
        padding: 20px;
        background-color: #fff;
        text-align: center;
    }

    .feature-card-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--secondary-color);
    }

    /* Quote Section Styles */
    .quote-section {
        background: linear-gradient(rgba(52, 152, 219, 0.8), rgba(52, 152, 219, 0.8)), url('https://images.unsplash.com/photo-1567501077737-4a931a4e5e7c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
        background-size: cover;
        color: #fff;
        text-align: center;
        padding: 70px 0;
        margin: 50px 0;
    }

    .quote-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .quote-heading {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .quote-phone {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #fff;
    }

    .quote-text {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto 15px;
    }

    /* Footer Styles */
    .footer {
        background-color: var(--dark-bg);
        color: var(--light-text);
        padding: 50px 0 20px;
        border-radius: 25px;
    }

    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .footer-section {
        flex: 0 0 calc(50.000% - 30px);
        margin-bottom: 30px;
    }

    .footer-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--primary-color);
    }

    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        margin-bottom: 10px;
    }

    .footer-list a {
        color: var(--light-text);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-list a:hover {
        color: var(--primary-color);
    }

    .copyright {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* About Page Specific Styles */
    #about {
        padding: 20px 0;
    }

    .card-title-dash {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 20px;
    }

    .card-description {
        margin-bottom: 20px;
        line-height: 1.7;
    }

    .card-rounded {
        border-radius: 10px;
        overflow: hidden;
    }

    .border-primary,
    .border-success,
    .border-info {
        border-width: 2px !important;
    }

    /* .courier-showcase {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    } */

    /* .courier-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        flex: 0 0 calc(50% - 10px);
        display: flex;
        transition: transform 0.3s ease;
    } */

    .courier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    /* .courier-image,
    .courier-image-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f5f5;
    } */

    .courier-info {
        padding: 15px;
        flex: 1;
    }

    .courier-name {
        margin: 0 0 10px;
        color: var(--secondary-color);
    }

    .courier-description {
        margin: 0 0 10px;
        font-size: 0.9rem;
        color: #666;
    }

    .courier-phone {
        margin: 0;
        font-size: 0.9rem;
        color: var(--primary-color);
    }

    .courier-phone i {
        margin-right: 5px;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        .feature-card {
            flex: 0 0 calc(50% - 15px);
        }

        .footer-section {
            flex: 0 0 calc(50% - 15px);
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.5rem;
        }

        .feature-card {
            flex: 0 0 100%;
        }

        .footer-section {
            flex: 0 0 100%;
        }

        .quote-phone {
            font-size: 1.8rem;
        }
    }

    /* Additional styles for counters in About page */
    .text-center .display-4 {
        transition: all 0.5s ease;
    }

    /* Custom backgrounds for sections */
    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Enhance buttons */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
</style>