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

            <div class="tab-content tab-content-basic">
                <!-- Home -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <!-- Statistics, can be useful later :3 -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="statistics-title">Page Views</p>
                                    <h3 class="rate-percentage">7,682</h3>
                                    <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                                </div>
                                <div>
                                    <p class="statistics-title">New Sessions</p>
                                    <h3 class="rate-percentage">68.8</h3>
                                    <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                                </div>
                                <div class="d-none d-md-block">
                                    <p class="statistics-title">Avg. Time on Site</p>
                                    <h3 class="rate-percentage">2m:35s</h3>
                                    <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.8%</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    <!-- Events -->
                    <div class="row flex-grow">
                        <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body card-rounded">
                                    <h4 class="card-title  card-title-dash">Recent Events</h4>
                                    <div class="list align-items-center border-bottom py-2">
                                        <div class="wrapper w-100">
                                            <p class="mb-2 font-weight-medium">
                                                Change in Directors
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-calendar text-muted me-1"></i>
                                                    <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list align-items-center border-bottom py-2">
                                        <div class="wrapper w-100">
                                            <p class="mb-2 font-weight-medium">
                                                Other Events
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-calendar text-muted me-1"></i>
                                                    <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list align-items-center border-bottom py-2">
                                        <div class="wrapper w-100">
                                            <p class="mb-2 font-weight-medium">
                                                Quarterly Report
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-calendar text-muted me-1"></i>
                                                    <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list align-items-center border-bottom py-2">
                                        <div class="wrapper w-100">
                                            <p class="mb-2 font-weight-medium">
                                                Change in Directors
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-calendar text-muted me-1"></i>
                                                    <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list align-items-center pt-3">
                                        <div class="wrapper w-100">
                                            <p class="mb-0">
                                                <a href="#" class="fw-bold text-primary">Show all <i
                                                        class="mdi mdi-arrow-right ms-2"></i></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Tracking activities of couriers -->
                        <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h4 class="card-title card-title-dash">Activities</h4>
                                        <p class="mb-0">20 finished, 5 remaining</p>
                                    </div>
                                    <ul class="bullet-line-list">
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Ben Tossell</span> assigned
                                                    you a task</div>
                                                <p>Just now</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Oliver Noah</span> assigned
                                                    you a task</div>
                                                <p>1h</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Jack William</span> assigned
                                                    you a task</div>
                                                <p>1h</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Leo Lucas</span> assigned you
                                                    a task</div>
                                                <p>1h</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Thomas Henry</span> assigned
                                                    you a task</div>
                                                <p>1h</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Ben Tossell</span> assigned
                                                    you a task</div>
                                                <p>1h</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div><span class="text-light-green">Ben Tossell</span> assigned
                                                    you a task</div>
                                                <p>1h</p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list align-items-center pt-3">
                                        <div class="wrapper w-100">
                                            <p class="mb-0">
                                                <a href="#" class="fw-bold text-primary">Show all <i
                                                        class="mdi mdi-arrow-right ms-2"></i></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row flex-grow">

                        <!-- TO-DO -->
                        <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body card-rounded">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="card-title card-title-dash">TO-DO list</h4>
                                                <div class="add-items d-flex mb-0">
                                                    <!-- <input type="text" class="form-control todo-list-input" placeholder="What do you need to do today?"> -->
                                                    <button
                                                        class="add btn btn-icons btn-rounded btn-primary todo-list-add-btn text-white me-0 pl-12p"><i
                                                            class="mdi mdi-plus"></i></button>
                                                </div>
                                            </div>
                                            <div class="list-wrapper">
                                                <ul class="todo-list todo-list-rounded">
                                                    <li class="d-block">
                                                        <div class="form-check w-100">
                                                            <label class="form-check-label">
                                                                <input class="checkbox" type="checkbox"> Lorem
                                                                Ipsum is simply dummy text of the printing <i
                                                                    class="input-helper rounded"></i>
                                                                <i class="input-helper"></i></label>
                                                            <div class="d-flex mt-2">
                                                                <div class="ps-4 text-small me-3">24 June 2020
                                                                </div>
                                                                <div class="badge badge-opacity-warning me-3">
                                                                    Due tomorrow</div>
                                                                <i class="mdi mdi-flag ms-2 flag-color"></i>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="d-block">
                                                        <div class="form-check w-100">
                                                            <label class="form-check-label">
                                                                <input class="checkbox" type="checkbox"> Lorem
                                                                Ipsum is simply dummy text of the printing <i
                                                                    class="input-helper rounded"></i>
                                                                <i class="input-helper"></i></label>
                                                            <div class="d-flex mt-2">
                                                                <div class="ps-4 text-small me-3">23 June 2020
                                                                </div>
                                                                <div class="badge badge-opacity-success me-3">
                                                                    Done</div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="form-check w-100">
                                                            <label class="form-check-label">
                                                                <input class="checkbox" type="checkbox"> Lorem
                                                                Ipsum is simply dummy text of the printing <i
                                                                    class="input-helper rounded"></i>
                                                                <i class="input-helper"></i></label>
                                                            <div class="d-flex mt-2">
                                                                <div class="ps-4 text-small me-3">24 June 2020
                                                                </div>
                                                                <div class="badge badge-opacity-success me-3">
                                                                    Done</div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="border-bottom-0">
                                                        <div class="form-check w-100">
                                                            <label class="form-check-label">
                                                                <input class="checkbox" type="checkbox"> Lorem
                                                                Ipsum is simply dummy text of the printing <i
                                                                    class="input-helper rounded"></i>
                                                                <i class="input-helper"></i></label>
                                                            <div class="d-flex mt-2">
                                                                <div class="ps-4 text-small me-3">24 June 2020
                                                                </div>
                                                                <div class="badge badge-opacity-danger me-3">
                                                                    Expired</div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports  -->
                        <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body d-flex flex-column">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <h4 class="card-title card-title-dash">Leave Report</h4>
                                                </div>
                                                <div>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-secondary dropdown-toggle toggle-dark btn-lg mb-0 me-0"
                                                            type="button" id="dropdownMenuButton3"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            Month Wise
                                                        </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton3">
                                                            <h6 class="dropdown-header">Week Wise</h6>
                                                            <a class="dropdown-item" href="#">Year Wise</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex-grow-1 d-flex align-items-end">
                                        <div class="chartjs-size-monitor"
                                            style="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                            <div class="chartjs-size-monitor-expand"
                                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                <div
                                                    style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                                </div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink"
                                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                <div style="position:absolute;width:100%;height:100%;left:0; top:0">
                                                </div>
                                            </div>
                                        </div>
                                        <canvas id="leaveReport" style="display: block; width: 100%; height: auto;"
                                            width="249%" height="100%" class="chartjs-render-monitor"></canvas>
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

<!-- For About's Counters animation -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('.display-4');
        const aboutSection = document.getElementById('about');
        const targets = {
            'offices-BG': 26,
            'kilometers-covered': 1928,
            'people-team': 6,
            'clients': 9
        };
        const animationDuration = 2000; // Total animation time in milliseconds
        const frameRate = 60; // Updates per second (higher for smoother animation)
        const totalFrames = Math.ceil(animationDuration / (1000 / frameRate)); // Total number of animation frames

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counters.forEach(counter => {
                        const targetId = counter.id;
                        if (targets.hasOwnProperty(targetId)) {
                            const targetValue = targets[targetId];
                            const increment = targetValue / totalFrames; // Calculate increment per frame
                            let currentValue = 0;
                            let frameCount = 0;

                            const interval = setInterval(() => {
                                frameCount++;
                                currentValue += increment;
                                counter.textContent = Math.ceil(currentValue); // Display rounded value

                                if (frameCount >= totalFrames) {
                                    counter.textContent = targetValue; // Ensure final value is exact
                                    clearInterval(interval);
                                }
                            }, 1000 / frameRate);
                        }
                    });
                    observer.unobserve(aboutSection);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(aboutSection);
    });
</script>


<!-- Header Styles -->
<style>
    .price-table thead th {
        background-color: #aaa;
        /* Replace with your desired color */
        color: white;
        /* Optional: change text color for better contrast */
        border: 1px solid #ddd;
        /* Optional: add borders to header cells */
    }

    .price-table,
    .price-table th,
    .price-table td {
        border: 1px solid #ddd;
        /* Add borders to the entire table and its cells */
        border-collapse: collapse;
        /* Optional: collapse borders into a single line */
    }
</style>
<!-- For the table in Price-decision -->
<style>
    .sort-icon {
        display: inline-block;
        margin-left: 5px;
        vertical-align: middle;
        opacity: 0.5;
        /* Slightly faded initially */
    }

    .sort-icon.sort-asc {
        font-size: 0.8em;
        color: #212529;
    }

    .sort-icon.sort-desc {
        font-size: 0.8em;
        color: #212529;
    }

    /* Initially show both arrows, but slightly faded */
    thead th[data-sortable="true"] .sort-asc,
    thead th[data-sortable="true"] .sort-desc {
        display: inline-block;
    }

    /* Highlight the active sort direction and dim the other */
    thead th[data-sort="asc"] .sort-asc {
        opacity: 1;
        font-weight: bold;
        /* Make the active arrow more prominent */
    }

    thead th[data-sort="asc"] .sort-desc {
        opacity: 0.2;
        /* Dim the inactive arrow */
        font-weight: normal;
    }

    thead th[data-sort="desc"] .sort-desc {
        opacity: 1;
        font-weight: bold;
        /* Make the active arrow more prominent */
    }

    thead th[data-sort="desc"] .sort-asc {
        opacity: 0.2;
        /* Dim the inactive arrow */
        font-weight: normal;
    }

    /* Style the header to indicate it's sortable */
    thead th[data-sortable="true"] {
        cursor: pointer;
    }
</style>

<script>
    function sortTableEnhanced(table, columnIndex, ascending = true) {
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const headerCells = table.querySelectorAll("thead th");

        // Identify section headers and group rows
        const sections = [];
        let currentSection = { headerRow: null, contentRows: [] };
        let normalRows = [];

        rows.forEach(row => {
            // Check if this is a section header row (has colspan or th elements)
            const hasTh = row.querySelector("th");
            const hasColspan = Array.from(row.querySelectorAll("td, th")).some(cell =>
                cell.hasAttribute("colspan") && parseInt(cell.getAttribute("colspan")) > 1);

            if (hasTh || hasColspan) {
                // This is a section header
                if (currentSection.contentRows.length > 0 || currentSection.headerRow) {
                    sections.push({ ...currentSection });
                }
                currentSection = { headerRow: row, contentRows: [] };
            } else {
                // This is a content row
                currentSection.contentRows.push(row);
                normalRows.push(row);
            }
        });

        // Add the last section
        if (currentSection.contentRows.length > 0 || currentSection.headerRow) {
            sections.push(currentSection);
        }

        // Sort function for comparing row values
        const compare = (rowA, rowB) => {
            const cellsA = rowA.querySelectorAll("td");
            const cellsB = rowB.querySelectorAll("td");

            if (cellsA.length === 0 || cellsB.length === 0) {
                return 0;
            }

            const cellA = cellsA[columnIndex] ? cellsA[columnIndex].textContent.trim() : '';
            const cellB = cellsB[columnIndex] ? cellsB[columnIndex].textContent.trim() : '';

            // Special handling for weight column (column 0)
            if (columnIndex === 0) {
                // Extract numeric value from weight strings like "Up to 10 kg" or "20 kg - 50 kg"
                const extractWeight = (text) => {
                    if (text.includes("Up to")) {
                        const match = text.match(/Up to (\d+)/);
                        return match ? parseInt(match[1]) : 0;
                    } else if (text.includes("Over")) {
                        const match = text.match(/Over (\d+)/);
                        return match ? parseInt(match[1]) + 1000 : 2000; // Add 1000 to push "Over" items to the end
                    } else if (text.includes("-")) {
                        const match = text.match(/(\d+) kg - (\d+)/);
                        return match ? parseInt(match[1]) : 0;
                    } else if (text.includes("Cash on Delivery")) {
                        return 3000; // Push special items to the very end
                    }
                    return 0;
                };

                const weightA = extractWeight(cellA);
                const weightB = extractWeight(cellB);

                if (weightA !== weightB) {
                    return ascending ? weightA - weightB : weightB - weightA;
                }
            }

            // Special handling for price column (column 1)
            if (columnIndex === 1) {
                // Handle formula-based prices
                const isFormulaA = cellA.includes("K *") || cellA.includes("* K");
                const isFormulaB = cellB.includes("K *") || cellB.includes("* K");

                if (isFormulaA && !isFormulaB) {
                    return ascending ? 1 : -1;
                } else if (!isFormulaA && isFormulaB) {
                    return ascending ? -1 : 1;
                } else if (isFormulaA && isFormulaB) {
                    const prefixA = cellA.match(/^(\d+)\s*\*/);
                    const prefixB = cellB.match(/^(\d+)\s*\*/);
                    if (prefixA && prefixB) {
                        return ascending ?
                            parseInt(prefixA[1]) - parseInt(prefixB[1]) :
                            parseInt(prefixB[1]) - parseInt(prefixA[1]);
                    }
                }

                // Handle special cases for "+" in price
                if (cellA.includes("+") && cellB.includes("+")) {
                    const baseA = parseFloat(cellA.split("+")[0]);
                    const baseB = parseFloat(cellB.split("+")[0]);

                    if (!isNaN(baseA) && !isNaN(baseB) && baseA !== baseB) {
                        return ascending ? baseA - baseB : baseB - baseA;
                    }
                }
            }

            // Default handling for numeric values
            const numA = parseFloat(cellA.replace(/[^0-9.,]/g, '').replace(',', '.'));
            const numB = parseFloat(cellB.replace(/[^0-9.,]/g, '').replace(',', '.'));

            if (!isNaN(numA) && !isNaN(numB)) {
                return ascending ? numA - numB : numB - numA;
            }

            // Fallback to string comparison
            const strA = cellA.toLowerCase();
            const strB = cellB.toLowerCase();

            return ascending ?
                strA.localeCompare(strB) :
                strB.localeCompare(strA);
        };

        // For sorting by weight/dimensions or price, we need to maintain section boundaries
        if (columnIndex === 0 || columnIndex === 1) {
            // Sort rows within each section
            sections.forEach(section => {
                section.contentRows.sort(compare);
            });

            // Clear table and rebuild
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Reconstruct table with sorted sections
            sections.forEach(section => {
                if (section.headerRow) {
                    tbody.appendChild(section.headerRow);
                }
                section.contentRows.forEach(row => {
                    tbody.appendChild(row);
                });
            });
        } else {
            // For other columns, use simpler sorting
            normalRows.sort(compare);

            // Clear table and rebuild
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Add all rows back in sorted order
            normalRows.forEach(row => {
                tbody.appendChild(row);
            });
        }

        // Update sort icons and data-sort attribute
        headerCells.forEach((th, index) => {
            if (th.getAttribute("data-sortable") === "true") {
                if (index === columnIndex) {
                    th.setAttribute("data-sort", ascending ? "asc" : "desc");
                } else {
                    th.removeAttribute("data-sort");
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const priceTable = document.querySelector("#price_decision .table");
        if (priceTable) {
            const sortableHeaders = priceTable.querySelectorAll("thead th[data-sortable='true']");
            sortableHeaders.forEach((th, index) => {
                th.addEventListener("click", () => {
                    const currentSort = th.getAttribute("data-sort");
                    const ascending = currentSort !== "asc";
                    sortTableEnhanced(priceTable, index, ascending);
                });
            });

            // Initial sort (by the first sortable column, ascending)
            if (sortableHeaders.length > 0) {
                sortTableEnhanced(priceTable, 0, true); // Sort by the first column (index 0), ascending
            }
        }
    });
</script>


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

            // Show loading indication
            if (toMarker) {
                toMarker.setPopupContent("Loading route information...");
                toMarker.openPopup();
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Route data received");
                    if (data.routes && data.routes.length > 0) {
                        const route = data.routes[0];
                        const routeGeoJSON = route.geometry;

                        // Create route layer with styling
                        try {
                            currentRouteLayer = L.geoJSON(routeGeoJSON, {
                                style: {
                                    color: "#0066CC",
                                    weight: 5,
                                    opacity: 0.7
                                }
                            }).addTo(officeMap);

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
                        } catch (e) {
                            console.error("Error adding route to map:", e);
                            if (toMarker) {
                                toMarker.setPopupContent("Error displaying route. Please try again.");
                            }
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