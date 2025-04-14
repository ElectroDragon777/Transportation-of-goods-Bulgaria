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

                    <div class="container ">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">From Office</h5>
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
                                                    <?= $office['city'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">To Office</h5>
                                        <select class="form-select" id="toOffice">
                                            <option value="">Select Office</option>
                                            <?php foreach ($offices as $office): ?>
                                                <option value="<?= $office['lat'] . ',' . $office['lng'] ?>">
                                                    <?= $office['city'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="map-container">
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
        function generateMap() {
            const fromOfficeSelect = document.getElementById('fromOffice');
            const toOfficeSelect = document.getElementById('toOffice');
            const mapContainer = document.getElementById('map-container');

            const fromOffice = fromOfficeSelect.value;
            const toOffice = toOfficeSelect.value;

            if (fromOffice && toOffice) {
                const [fromLat, fromLng] = fromOffice.split(',').map(parseFloat);
                const [toLat, toLng] = toOffice.split(',').map(parseFloat);

                const mapEmbedUrl = `https://www.google.com/maps/embed/v1/dir/?origin=${fromLat},${fromLng}&destination=${toLat},${toLng}`;

                const iframe = document.createElement('iframe');
                iframe.src = mapEmbedUrl;
                iframe.width = 600;  // Or whatever width you want, nya~
                iframe.height = 450; // And the height, nya~
                iframe.style.border = '0';
                iframe.allowFullscreen = true;
                iframe.loading = 'lazy';
                iframe.referrerPolicy = 'no-referrer-when-downgrade';

                mapContainer.innerHTML = ''; // Clear previous map, nya~
                mapContainer.appendChild(iframe);
            } else {
                mapContainer.innerHTML = '<p>Please select both \'From\' and \'To\' offices, nya~.</p>';
            }
        }

        // populateDropdowns();  <--  Remove this line, nya~! PHP handles it now.
        document.getElementById('fromOffice').addEventListener('change', generateMap);
        document.getElementById('toOffice').addEventListener('change', generateMap);
    </script>