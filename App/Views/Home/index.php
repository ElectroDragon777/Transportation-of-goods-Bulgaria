<?php

namespace Core;
use \App\Models\Order; // Use the correct namespace for your Order model.
use \App\Models\User;  // Use the correct namespace for your User model.
use \App\Models\Courier; // Use the correct namespace for your Courier model.

// Define $date_key here!
$user_order_interval = 'weekly'; // or 'daily' - changed to weekly
$user_order_date_key = ($user_order_interval == 'daily') ? date('Y-m-d') : date('Y-W');

// Start session to store previous week's data
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sets a cookie to track page visits, including the maximum number of visits.
 *
 * This function attempts to simulate a "never-expiring" cookie by setting
 * the expiration time far into the future (approximately 10 years). It
 * retrieves the current visit count and maximum visit count from cookies,
 * updates them as necessary, and then updates the cookies.
 *
 * @param string $visitCookieName The name of the cookie for current visits.
 * @param string $maxVisitCookieName The name of the cookie for maximum visits.
 * @return array
 */
function trackPageVisit($visitCookieName = 'page_visits', $maxVisitCookieName = 'max_page_visits')
{
    // // Use a session variable as a flag to ensure this function runs only once per request.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $interval = 'daily'; // or 'weekly'
    $date_key = ($interval == 'daily') ? date('Y-m-d') : date('Y-W');

    // Use a session variable to track if the daily/weekly count has been done.
    if (!isset($_SESSION['page_visit_tracked_' . $date_key])) {
        $_SESSION['page_visit_tracked_' . $date_key] = true;

        // Determine the current visit count.
        $visitCount = isset($_COOKIE[$visitCookieName]) ? (int) $_COOKIE[$visitCookieName] : 0;

        // Increment the visit count.
        $visitCount++;

        // Determine the current maximum visit count.
        $maxVisitCount = isset($_COOKIE[$maxVisitCookieName]) ? (int) $_COOKIE[$maxVisitCookieName] : 0;

        // Update the maximum visit count if necessary.
        if ($visitCount > $maxVisitCount) {
            $maxVisitCount = $visitCount;
        }

        // Calculate the expiration time (10 years from now).
        $expiration = time() + (365 * 24 * 60 * 60 * 10); // 10 years in seconds

        // Set the cookies with the updated visit count and maximum visit count.
        setcookie($visitCookieName, $visitCount, $expiration, '/');  //removed samesite
        setcookie($maxVisitCookieName, $maxVisitCount, $expiration, '/');      //removed samesite

        return array(
            'visits' => $visitCount,
            'maxVisits' => $maxVisitCount,
            'previousVisits' => isset($_SESSION['previous_visits_' . $date_key]) ? $_SESSION['previous_visits_' . $date_key] : 0, //ADDED THIS LINE
            'previousMaxVisits' => isset($_SESSION['previous_max_visits_' . $date_key]) ? $_SESSION['previous_max_visits_' . $date_key] : 0,
        );
    } else {
        $visitCount = isset($_COOKIE[$visitCookieName]) ? (int) $_COOKIE[$visitCookieName] : 0;
        $maxVisitCount = isset($_COOKIE[$maxVisitCookieName]) ? (int) $_COOKIE[$maxVisitCookieName] : 0;
        return array(
            'visits' => $visitCount,
            'maxVisits' => $maxVisitCount,
            'previousVisits' => isset($_SESSION['previous_visits_' . $date_key]) ? $_SESSION['previous_visits_' . $date_key] : 0, //ADDED THIS LINE
            'previousMaxVisits' => isset($_SESSION['previous_max_visits_' . $date_key]) ? $_SESSION['previous_max_visits_' . $date_key] : 0,
        );
    }
}
// Define $date_key here!
$interval = 'daily'; // or 'weekly'
$date_key = ($interval == 'daily') ? date('Y-m-d') : date('Y-W');

// Call trackPageVisit() BEFORE any HTML output!
$visitData = trackPageVisit();
$visits = $visitData['visits'];
$maxVisits = $visitData['maxVisits'];
$previousVisits = $visitData['previousVisits']; // Access previous visits from the array
$previousMaxVisits = $visitData['previousMaxVisits'];

// Get current counts from the database
$orderModel = new Order();
$total_orders = $orderModel->countAll();  //renamed
$userModel = new User();
$total_users = $userModel->countAll();    //renamed

// Get previous counts from session, default to 0 if not set
$previous_orders = isset($_SESSION['previous_orders_' . $date_key]) ? $_SESSION['previous_orders_' . $date_key] : 0;
$previous_users = isset($_SESSION['previous_users_' . $date_key]) ? $_SESSION['previous_users_' . $date_key] : 0;

// Calculate percentage changes
$order_change = ($previous_orders != 0) ? (($total_orders - $previous_orders) / $previous_orders) * 100 : 0;
$order_change_formatted = number_format($order_change, 2);

$user_change = ($previous_users != 0) ? (($total_users - $previous_users) / $previous_users) * 100 : 0;
$user_change_formatted = number_format($user_change, 2);

// Store current counts in session for next week's calculation
$_SESSION['previous_orders_' . $date_key] = $total_orders;
$_SESSION['previous_users_' . $date_key] = $total_users;

// Calculate the percentage change for visits
$percentageChange = ($previousVisits != 0) ? (($visits - $previousVisits) / $previousVisits) * 100 : 0;
$percentageChange = number_format($percentageChange, 2);

// Calculate the percentage change for max visits
$maxPercentageChange = ($previousMaxVisits != 0) ? (($maxVisits - $previousMaxVisits) / $previousMaxVisits) * 100 : 0;
$maxPercentageChange = number_format($maxPercentageChange, 2);


// Store current visits for next calculation
$_SESSION['previous_visits_' . $date_key] = $visits;
$_SESSION['previous_max_visits_' . $date_key] = $maxVisits;
?>

<?php
// To show a container, warning it is a guest account, therefore, limited access to the system. Only home page is accessible for guests.
$isLoggedIn = !empty($_SESSION['user']);
?>


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
                        <a class="nav-link" id="about-tab" data-bs-toggle="tab" href="#about" role="tab"
                            aria-selected="false">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="price_decision-tab" data-bs-toggle="tab" href="#price_decision"
                            role="tab" aria-selected="false">Price-decision</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="faqs-tab" data-bs-toggle="tab" href="#faqs" role="tab"
                            aria-selected="false">FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" id="contact-tab" data-bs-toggle="tab" href="#contacts" role="tab"
                            aria-selected="false">Contacts</a>
                    </li>
                </ul>
                <div>
                    <!-- <div class="btn-wrapper">
                        <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
                        <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                        <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
                    </div> -->
                </div>
            </div>

            <!-- Show a container for the guest users they are not logged in and need an account for further access. If out of screen, becomes a notification overlapping everything but stays on top. -->
            <?php if (!$isLoggedIn): ?>
                <div class="guest-notification"
                    style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background-color: #f8d7da; color: #721c24; padding: 10px; text-align: center;">
                    <strong>Guest Account:</strong> You are currently using a guest account. You can log in to access more!
                    If you are a new one on our web platform, please consider registering!
                    <a href="<?php echo INSTALL_URL; ?>?controller=Auth&action=login" class="btn btn-primary btn-sm"
                        style="margin-left: 20px; top:50%; position: flex; -ms-transform: translate(-50%, -50%); transform: translate(0%, 10%);">Login</a>
                    <a href="<?php echo INSTALL_URL; ?>?controller=Auth&action=register" class="btn btn-primary btn-sm"
                        style="margin-left: 0px; top:50%; position: flex; -ms-transform: translate(-50%, -50%); transform: translate(0%, 10%);">Register</a>
                </div>
            <?php endif; ?>

            <!-- Tab panes -->
            <div class="tab-content tab-content-basic">
                <!-- Home -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <!-- Statistics, can be useful later :3 -->
                    <?php if ($isLoggedIn): ?>
                        <?php if ((in_array($_SESSION['user']['role'], ['admin', 'root'])) && ($isLoggedIn)): ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="statistics-title">Page Views (<?php echo ucfirst($interval); ?>)</p>
                                            <h3 class="rate-percentage"><?php echo $visits; ?></h3>
                                            <p
                                                class="text-<?php echo ($percentageChange >= 0) ? 'success' : 'danger'; ?> d-flex">
                                                <i
                                                    class="mdi mdi-menu-<?php echo ($percentageChange >= 0) ? 'up' : 'down'; ?>"></i>
                                                <span><?php echo $percentageChange; ?>%</span>
                                            </p>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Max Page Views</p>
                                            <h3 class="rate-percentage"><?php echo $maxVisits; ?></h3>
                                            <p
                                                class="text-<?php echo ($maxPercentageChange >= 0) ? 'success' : 'danger'; ?> d-flex">
                                                <i
                                                    class="mdi mdi-menu-<?php echo ($maxPercentageChange >= 0) ? 'up' : 'down'; ?>"></i>
                                                <span><?php echo $maxPercentageChange; ?>%</span>
                                            </p>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Total Orders</p>
                                            <h3 class="rate-percentage"><?php echo $total_orders; ?></h3>
                                            <p class="text-<?php
                                            if ($order_change > 0) {
                                                echo 'success';
                                            } elseif ($order_change < 0) {
                                                echo 'danger';
                                            } else {
                                                echo 'muted'; // Use muted for no change
                                            }
                                            ?> d-flex">
                                                <?php if ($order_change > 0): ?>
                                                    <i class="mdi mdi-menu-up"></i>
                                                <?php elseif ($order_change < 0): ?>
                                                    <i class="mdi mdi-menu-down"></i>
                                                <?php else: ?>
                                                    <i class="mdi mdi-menu-minus"></i> <?php endif; ?>
                                                <span><?php echo ($order_change == 0) ? '-' : $order_change_formatted . '%'; ?></span>
                                            </p>
                                        </div>
                                        <div class="d-none d-md-block">
                                            <p class="statistics-title">Registered/All users:</p>
                                            <h3 class="rate-percentage"><?php echo $total_users; ?></h3>
                                            <p class="text-<?php
                                            if ($user_change > 0) {
                                                echo 'success';
                                            } elseif ($user_change < 0) {
                                                echo 'danger';
                                            } else {
                                                echo 'muted'; // Use muted for no change
                                            }
                                            ?> d-flex">
                                                <?php if ($user_change > 0): ?>
                                                    <i class="mdi mdi-menu-up"></i>
                                                <?php elseif ($user_change < 0): ?>
                                                    <i class="mdi mdi-menu-down"></i>
                                                <?php else: ?>
                                                    <i class="mdi mdi-menu-minus"></i> <?php endif; ?>
                                                <span><?php echo ($user_change == 0) ? '-' : $user_change_formatted . '%'; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- The version it should be, coded by me, but working now. Reminder to check notes! >w< -->

                    <!-- Hero Section -->
                    <div class="hero-section">
                        <h2 class="hero-title">Welcome!</h2>
                        <div style="width: 60px; height: 4px; background-color: #3498db; margin: 0 auto 20px auto;">
                        </div>
                        <p class="hero-subtitle"><br>Elec-Transport is one of the major Bulgarian Transportation
                            Companies!</p>
                        <p class="hero-description"><br>Our company is made for High School of Mathematics Varna! Here
                            are
                            our benefits:</p>
                    </div>

                    <!-- Feature Cards -->
                    <div class="container">
                        <div class="feature-cards">
                            <div class="feature-card">
                                <div class="feature-card-bg"
                                    style="background-image: url('Extras/Dashboard/Main/No_Hidden_Fees.jpg');">
                                </div>
                                <div class="feature-card-content">
                                    <h3 class="feature-card-title">No cost cancellation,<br>No hidden fees!</h3>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-card-bg"
                                    style="background-image: url('Extras/Dashboard/ContactsBG/BG.png');">
                                </div>
                                <div class="feature-card-content">
                                    <h3 class="feature-card-title">The best service in<br>the industry</h3>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-card-bg"
                                    style="background-image: url('Extras/Dashboard/Main/Courier_because_I_can.png');">
                                </div>
                                <div class="feature-card-content">
                                    <h3 class="feature-card-title">All our drivers are fully<br>insured and bonded</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Section -->
                    <div class="quote-section" style="background-image: url('Extras/Dashboard/Main/HowToElectricity.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover; border-radius: 25px;">
                        <?php
                        $userModel = new User();
                        $root = $userModel->getFirstBy(['role' => 'root']);
                        $root_name = $root['name'];
                        $root_phone = $root['phone_number'];
                        ?>
                        <h3 class="quote-title">Call us if you need anything!</h3>
                        <h2 class="quote-heading">The Electro Dragon's phone: (Or mine, <?php echo $root_name ?>'s)
                        </h2>
                        <div class="quote-phone"><?php echo $root_phone ?></div>
                        <p class="quote-text">We are committed to offering you the best rates and customer service.</p>
                    </div>

                    <!-- Events
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
                        </div> -->

                    <!-- Admin Tracking activities of couriers -->
                    <!-- <div class="col-md-6 col-lg-6 grid-margin stretch-card">
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
                </div> -->


                    <div class="row flex-grow">

                        <!-- TO-DO List -->
                        <!-- <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body card-rounded">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="card-title card-title-dash">TO-DO list</h4>
                                                <div class="add-items d-flex mb-0">
                                                    <input type="text" class="form-control todo-list-input"
                                                        placeholder="Add new task...">
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
                        </div> -->

                        <!-- Type by Amount -->
                        <!-- <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="chartjs-size-monitor">
                                                            <div class="chartjs-size-monitor-expand">
                                                                <div class=""></div>
                                                            </div>
                                                            <div class="chartjs-size-monitor-shrink">
                                                                <div class=""></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h4 class="card-title card-title-dash">Type By Amount</h4>
                                                        </div>
                                                        <canvas class="my-auto chartjs-render-monitor" id="doughnutChart"
                                                            height="166" style="display: block; width: 249px; height: 166px;"
                                                            width="249"></canvas>
                                                        <div id="doughnut-chart-legend" class="mt-5 text-center">
                                                            <div class="chartjs-legend">
                                                                <ul class="justify-content-center">
                                                                    <li><span style="background-color:#1F3BB3"></span>Total
                                                                    </li>
                                                                    <li><span style="background-color:#FDD0C7"></span>Net
                                                                    </li>
                                                                    <li><span style="background-color:#52CDFF"></span>Gross
                                                                    </li>
                                                                    <li><span style="background-color:#81DADA"></span>AVG
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                        <!-- Reports  -->
                        <!-- <div class="col-md-6 col-lg-6 grid-margin stretch-card">
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
                        </div> -->


                        <!-- <div class="row flex-grow">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Top Performer</h4>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <div
                                                        class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                        <div class="d-flex">
                                                            <img class="img-sm rounded-10" src="images/faces/face1.jpg"
                                                                alt="profile">
                                                            <div class="wrapper ms-3">
                                                                <p class="ms-1 mb-1 fw-bold">Brandon Washington</p>
                                                                <small class="text-muted mb-0">162543</small>
                                                            </div>
                                                        </div>
                                                        <div class="text-muted text-small">
                                                            1h ago
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                        <div class="d-flex">
                                                            <img class="img-sm rounded-10" src="images/faces/face2.jpg"
                                                                alt="profile">
                                                            <div class="wrapper ms-3">
                                                                <p class="ms-1 mb-1 fw-bold">Wayne Murphy</p>
                                                                <small class="text-muted mb-0">162543</small>
                                                            </div>
                                                        </div>
                                                        <div class="text-muted text-small">
                                                            1h ago
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                        <div class="d-flex">
                                                            <img class="img-sm rounded-10" src="images/faces/face3.jpg"
                                                                alt="profile">
                                                            <div class="wrapper ms-3">
                                                                <p class="ms-1 mb-1 fw-bold">Katherine Butler</p>
                                                                <small class="text-muted mb-0">162543</small>
                                                            </div>
                                                        </div>
                                                        <div class="text-muted text-small">
                                                            1h ago
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                        <div class="d-flex">
                                                            <img class="img-sm rounded-10" src="images/faces/face4.jpg"
                                                                alt="profile">
                                                            <div class="wrapper ms-3">
                                                                <p class="ms-1 mb-1 fw-bold">Matthew Bailey</p>
                                                                <small class="text-muted mb-0">162543</small>
                                                            </div>
                                                        </div>
                                                        <div class="text-muted text-small">
                                                            1h ago
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="wrapper d-flex align-items-center justify-content-between pt-2">
                                                        <div class="d-flex">
                                                            <img class="img-sm rounded-10" src="images/faces/face5.jpg"
                                                                alt="profile">
                                                            <div class="wrapper ms-3">
                                                                <p class="ms-1 mb-1 fw-bold">Rafell John</p>
                                                                <small class="text-muted mb-0">Alaska, USA</small>
                                                            </div>
                                                        </div>
                                                        <div class="text-muted text-small">
                                                            1h ago
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>


                <!-- About Page : Also, keep in mind, show active is for HOME only. Do not add to "tab-pane fade" the "show active" unless it is HOME-->
                <?php
                $userModel = new User();
                $root = $userModel->getFirstBy(['role' => 'root']);
                $root_name = $root['name'];
                $root_phone = $root['phone_number'];
                ?>
                <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about">
                    <div class="row flex-grow">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h3 class="card-title card-title-dash">About Us</h3>
                                    <p class="card-description"><br>Welcome to Elec-Transport! We are a team of
                                        couriers,
                                        ready to deliver everything you want! No matter when, your pallets will
                                        arrive
                                        in the office in
                                        due time. We make everything easier, not pressuring with paperwork, just
                                        sign
                                        you have gotten your palletand you are free to go! We will take care of the
                                        paperwork for you.</p>
                                    <p class="card-description">* This company is with made for educational
                                        purposes/diploma thesis and
                                        is not real, so please, do not spend money on it.</p>
                                    <div class="row">
                                        <div class="col-md-4 grid-margin stretch-card">
                                            <div class="card card-rounded border-primary">
                                                <div class="card-body text-center">
                                                    <img src="Extras\Dashboard\AboutUs\page2-img1.jpg" alt="Reliability"
                                                        class="img-fluid mb-3" style="max-height: 200px;">
                                                    <h4 class="card-title">Reliability</h4>
                                                    <p class="card-text">
                                                        Your requests are our priority. We ensure timely and secure
                                                        delivery, every time.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 grid-margin stretch-card">
                                            <div class="card card-rounded border-success">
                                                <div class="card-body text-center">
                                                    <img src="Extras\Dashboard\AboutUs\page2-img2.jpg" alt="Safety"
                                                        class="img-fluid mb-3" style="max-height: 200px;">
                                                    <h4 class="card-title">Safety</h4>
                                                    <p class="card-text">
                                                        We handle your goods with utmost care, ensuring they reach
                                                        their
                                                        destination safely.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 grid-margin stretch-card">
                                            <div class="card card-rounded border-info">
                                                <div class="card-body text-center">
                                                    <img src="Extras\Dashboard\AboutUs\page2-img3.jpg"
                                                        alt="Transparency" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <h4 class="card-title">Transparency</h4>
                                                    <p class="card-text">
                                                        We keep you informed every step of the way with clear
                                                        communication and tracking.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="solutions">
                                        <h3 class="card-title card-title-dash mt-5">Our Solutions</h3>
                                        <p class="card-description">We offer a wide range of logistics solutions to meet
                                            your needs.</p>

                                        <!-- Lists -->
                                        <div class="row">
                                            <div class="col-md-6 grid-margin stretch-card">
                                                <div class="card card-rounded bg-light">
                                                    <div class="card-body">
                                                        <h4 class="card-title"><i
                                                                class="mdi mdi-truck-delivery text-primary me-2"></i>
                                                            Logistics Consulting</h4>
                                                        <p class="card-text">
                                                            Our logistics experts assist you in finding the best and
                                                            most
                                                            cost-effective transport solutions.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 grid-margin stretch-card">
                                                <div class="card card-rounded bg-light">
                                                    <div class="card-body">
                                                        <h4 class="card-title"><i
                                                                class="mdi mdi-rocket text-warning me-2"></i>
                                                            Quick
                                                            Services
                                                        </h4>
                                                        <p class="card-text">
                                                            Fast and time-bound deliveries across all major locations
                                                            with
                                                            flexible and optimal routing.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 grid-margin stretch-card">
                                                <div class="card card-rounded bg-light">
                                                    <div class="card-body">
                                                        <h4 class="card-title"><i
                                                                class="mdi mdi-shield-check text-success me-2"></i> Safe
                                                            &
                                                            Secure</h4>
                                                        <p class="card-text">
                                                            No matter what the destination is, your cargo will reach it
                                                            on
                                                            time and intact.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 grid-margin stretch-card">
                                                <div class="card card-rounded bg-light">
                                                    <div class="card-body">
                                                        <h4 class="card-title"><i
                                                                class="mdi mdi-home-import-outline text-secondary me-2"></i>
                                                            Door Delivery & Pick Up</h4>
                                                        <p class="card-text">
                                                            Comprehensive delivery services with pick-up at your door
                                                            and
                                                            last-mile delivery.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <div id="offices-BG" class="display-4">0</div>
                                            <p class="text-muted">Offices in Bulgaria</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div id="kilometers-covered" class="display-4">0</div>
                                            <p class="text-muted">Kilometers covered</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div id="people-team" class="display-4">0</div>
                                            <p class="text-muted">People in Team</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div id="clients" class="display-4">0</div>
                                            <p class="text-muted">Clients</p>
                                        </div>
                                    </div>
                                    <!-- Team -->
                                    <h3 class="card-title card-title-dash mt-5">Meet Our Couriers</h3>
                                    <p class="card-description">Our dedicated couriers are the backbone of our
                                        service,
                                        ensuring your goods are delivered safely and on time.</p>
                                    <div class="courier-showcase">
                                        <!-- Extracting Couriers from users -->
                                        <?php
                                        $userModel = new \App\Models\User();
                                        $couriers = $userModel->getAll(['role' => 'courier']);  // Fetch all couriers from the database 101
                                        ?>
                                        <?php if (!empty($couriers)): ?>
                                            <?php foreach ($couriers as $courier): ?>
                                                <div class="courier-card">
                                                    <?php if (!empty($courier['photo_path'])): ?>
                                                        <img src="<?php echo htmlspecialchars($courier['photo_path']); ?>"
                                                            alt="<?php echo htmlspecialchars($courier['name']); ?> Profile Picture"
                                                            class="courier-image">
                                                    <?php else: ?>
                                                        <div class="courier-image-placeholder">
                                                            <i class="mdi mdi-account" style="font-size: 80px;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="courier-info">
                                                        <?php if (!empty($courier['name'])): ?>
                                                            <h4 class="courier-name">
                                                                <?php echo htmlspecialchars($courier['name']); ?>
                                                            </h4>
                                                        <?php endif; ?>
                                                        <?php if (!empty($courier['description'])): ?>
                                                            <p class="courier-description">
                                                                <?php echo htmlspecialchars($courier['description']); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if (!empty($courier['phone_number'])): ?>
                                                            <p class="courier-phone"><i
                                                                    class="mdi mdi-phone-outline"></i><?php echo htmlspecialchars($courier['phone_number']); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>No couriers to display.</p>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Price-setting Page -->
                <div class="tab-pane fade" id="price_decision" role="tabpanel" aria-labelledby="price_decision">
                    <div class="row flex-grow">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h3 class="card-title card-title-dash">How do we price?</h3>
                                    <p class="card-description">Our pricing is based on the weight and dimensions of
                                        your shipment to ensure fair and transparent costs. Below is a breakdown of
                                        our
                                        pricing structure:</p>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered price-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col" data-sortable="true">
                                                        Weight/Dimensions
                                                        <span class="sort-icon sort-asc">&#9650;</span>
                                                        <span class="sort-icon sort-desc">&#9660;</span>
                                                    </th>
                                                    <th scope="col" data-sortable="true">
                                                        Price (BGN)
                                                        <span class="sort-icon sort-asc">&#9650;</span>
                                                        <span class="sort-icon sort-desc">&#9660;</span>
                                                    </th>
                                                    <th scope="col" data-sortable="false">Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Up to 3 kg</td>
                                                    <td>10</td>
                                                    <td>Base price for very light packages.</td>
                                                </tr>
                                                <tr>
                                                    <td>Up to 6 kg</td>
                                                    <td>15</td>
                                                    <td>Price for slightly heavier packages.</td>
                                                </tr>
                                                <tr>
                                                    <td>Up to 10 kg</td>
                                                    <td>20</td>
                                                    <td>Pricing for medium-weight shipments.</td>
                                                </tr>
                                                <tr>
                                                    <td>Up to 20 kg</td>
                                                    <td>35</td>
                                                    <td>Standard price for packages up to 20 kg.</td>
                                                </tr>
                                                <tr>
                                                    <td>20 kg - 50 kg</td>
                                                    <td>35 + 1 per kg over 20</td>
                                                    <td>Base price of 35 BGN plus 1 BGN for each kilogram exceeding
                                                        20
                                                        kg, up to 50 kg.</td>
                                                </tr>
                                                <tr>
                                                    <td>Over 50 kg (max 80/120/90 cm)</td>
                                                    <td>30 + 0.9 per kg</td>
                                                    <td>Base price of 30 BGN plus 0.9 BGN for each kilogram over 50
                                                        kg,
                                                        up to 1000 kg, with maximum dimensions of 80x120x90 cm.</td>
                                                </tr>
                                                <tr class="table-info">
                                                    <th scope="row" colspan="3" class="text-center">Packages with
                                                        Dimensions Larger Than 80/120/90 cm</th>
                                                </tr>
                                                <tr>
                                                    <td>Up to 15 kg (large dimensions)</td>
                                                    <td>K * 150</td>
                                                    <td>Price is calculated using the coefficient K, where K =
                                                        (longest
                                                        dimension in meters * shortest dimension in meters) / 0.96.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Over 15 kg (large dimensions)</td>
                                                    <td>2 * K * 150</td>
                                                    <td>Price is calculated as twice the value using the coefficient
                                                        K,
                                                        where K = (longest dimension in meters * shortest dimension
                                                        in
                                                        meters) / 0.96.</td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <th scope="row" colspan="3" class="text-center">Additional Fees
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Cash on Delivery</td>
                                                    <td>+ 1.5% of the total amount</td>
                                                    <td>A fee of 1.5% is applied to the total amount for cash on
                                                        delivery service.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <p class="mt-3"><strong>Please note:</strong> For shipments exceeding 1000 kg or
                                        with significantly larger dimensions, please contact us
                                        for a custom quote.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQs  -->
                <div class="tab-pane fade" id="faqs" role="tabpanel" aria-labelledby="faqs-tab">
                    <div class="row flex-grow" style="background: linear-gradient(rgb(255, 255, 255), rgba(85, 85, 85, 0.4)), url('Extras/Dashboard/FAQ/faq.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover; border-radius: 25px;">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h3 class="card-title card-title-dash">FAQs (Frequently Asked Questions)</h3>
                                    <p class="card-description">Find answers to common questions about our local
                                        transportation services.</p>

                                    <div class="accordion" id="faqAccordion">
                                        <div class="card card-rounded mb-2">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        <i class="mdi mdi-help-circle-outline mr-2"></i> What types
                                                        of
                                                        goods do you transport?
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                                data-parent="#faqAccordion">
                                                <div class="card-body">
                                                    We transport documents and pallets
                                                    at any size to your location or your selected office's, either
                                                    for
                                                    you or a friend/relative. Please note that due to our local
                                                    focus
                                                    within
                                                    Bulgaria,
                                                    we do not handle international shipping.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-rounded mb-2">
                                            <div class="card-header" id="headingTwo">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                        aria-expanded="true" aria-controls="collapseTwo">
                                                        <i class="mdi mdi-help-circle-outline mr-2"></i> How do I
                                                        request a transportation service?
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                data-parent="#faqAccordion">
                                                <div class="card-body">
                                                    You can request a service by clicking on "Create Order" and
                                                    choose!
                                                    Please
                                                    provide details about the order, pickup and delivery locations
                                                    within the country, and anything more specific.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-rounded mb-2">
                                            <div class="card-header" id="headingThree">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                        aria-expanded="true" aria-controls="collapseThree">
                                                        <i class="mdi mdi-help-circle-outline mr-2"></i> What is
                                                        your
                                                        pricing structure?
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                                data-parent="#faqAccordion">
                                                <div class="card-body">
                                                    Our pricing is based on several factors, including the size and
                                                    weight of the goods, the distance of
                                                    transportation within country. You can refer to our <a
                                                        href="#price_decision">Price-setting Page</a> for general
                                                    pricing
                                                    guidelines.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-rounded mb-2">
                                            <div class="card-header" id="headingFour">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                        aria-expanded="true" aria-controls="collapseFour">
                                                        <i class="mdi mdi-help-circle-outline mr-2"></i> What are
                                                        your
                                                        operating hours?
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                                data-parent="#faqAccordion">
                                                <div class="card-body">
                                                    Our standard operating hours for pickups and deliveries within
                                                    Bulgaria are from 8:00 to 18:00. Any order outside these hours
                                                    is
                                                    rescheduled.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-rounded mb-2">
                                            <div class="card-header" id="headingFive">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFive" aria-expanded="false"
                                                        aria-controls="collapseFive">
                                                        <i class="mdi mdi-help-circle-outline mr-2"></i> What areas
                                                        in
                                                        Bulgaria do you serve?
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                                data-parent="#faqAccordion">
                                                <div class="card-body">
                                                    We provide local transportation services throughout Bulgaria,
                                                    covering all
                                                    major cities and towns in Bulgaria". Please contact us to
                                                    confirm
                                                    service availability for your specific locations.
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Contacts -->
                <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts">
                    <div class="row d-flex align-items-stretch">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <h3 class="card-title card-title-dash">Contact Us</h3>

                                    <?php
                                    // Check if the user is logged in and get their role
                                    $userModel = new \App\Models\User();
                                    $root = $userModel->getFirstBy(['role' => 'root']);
                                    ?>
                                    <?php if (!$isLoggedIn): ?>
                                        <p class="card-description">Hey, <?php echo htmlspecialchars($root['name']); ?>
                                            here! Please log in to access the contact form fully!</p>
                                        <!-- Left Column with Root Profile -->
                                        <div class="row d-flex align-items-stretch">
                                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                                <div class="admin-contact-card">
                                                    <div class="text-center text-content">
                                                        <h4 class="text-light font-weight-bold mb-3">Super-Administrator
                                                            Contact
                                                        </h4>

                                                        <?php if (!empty($root['photo_path'])): ?>
                                                            <img src="<?php echo htmlspecialchars($root['photo_path']); ?>"
                                                                alt="Root Profile Picture"
                                                                class="img-thumbnail rounded-circle mb-3"
                                                                style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                                        <?php endif; ?>

                                                        <?php if (!empty($root['name'])): ?>
                                                            <h4 class="mb-2 text-warning font-weight-bold">
                                                                <?php echo htmlspecialchars($root['name']); ?>
                                                            </h4>
                                                        <?php endif; ?>

                                                        <hr class="my-2">
                                                    </div>

                                                    <div class="px-3 text-content">
                                                        <?php if (!empty($root['phone_number'])): ?>
                                                            <p><i class="mdi mdi-phone-outline text-success"></i>
                                                                <strong>Phone:</strong>
                                                                <?php echo htmlspecialchars($root['phone_number']); ?>
                                                            </p>
                                                        <?php endif; ?>

                                                        <?php if (!empty($root['email'])): ?>
                                                            <p><i class="mdi mdi-email-outline text-danger"></i>
                                                                <strong>E-mail:</strong>
                                                                <a href="mailto:<?php echo htmlspecialchars($root['email']); ?>"
                                                                    class="text-white">
                                                                    <?php echo htmlspecialchars($root['email']); ?>
                                                                </a>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Column with Message Form -->
                                            <div class="col-md-6 flex-column justify-content-center">
                                                <h4>About the Super-Administrator/Root:</h4>
                                                <p class="text-muted">Heya, it is me again, Chara Dreemurr, the
                                                    Root-Admin/Super-Administrator of this system! I am here to help
                                                    you
                                                    with
                                                    everything you need!
                                                    If you have any questions or need assistance, feel free to reach
                                                    out
                                                    to me. I am
                                                    here to make your experience as smooth as possible. Let's work
                                                    together to make
                                                    this system even better!
                                                    <br><b>You are a guest, and sadly I cannot let you use the contact form.
                                                        Make an account or log in if you need me!</b>

                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <?php if ($_SESSION['user']['name'] == $root['name']): ?>
                                                <p class="card-description">This form is for debugging purposes. Messages
                                                    sent
                                                    here
                                                    are for testing and will not be sent to other users.</p>
                                                <p>Writing as
                                                    <strong><?php echo htmlspecialchars($root['name']); ?></strong>
                                                </p>

                                                <?php if ($root['name'] == "Chara Dreemurr"): ?>
                                                    <p class="text-muted">Root-Admin (You,
                                                        <?php echo htmlspecialchars($root['name']); ?>)... Hey, it is me! I am
                                                        Chara
                                                        Dreemurr, the Root-Admin of this system! This is my DEBUG-ger! This will
                                                        simulate receiving messages from users to me by well... sending messages
                                                        myself.
                                                        But as long as it works!
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-muted">Root-Admin (You,
                                                        <?php echo htmlspecialchars($root['name']); ?>) cannot use this contact
                                                        form. It
                                                        is intended for users to contact you.
                                                    </p>
                                                <?php endif ?>

                                                <form id="contactForm">
                                                    <div class="form-group">
                                                        <label for="message">Message</label>
                                                        <textarea class="form-control" id="message" rows="5"
                                                            style="width: 100%; min-height: 150px;" required></textarea>
                                                    </div>
                                                    <button type="button" class="btn btn-primary mr-2" onclick="sendMessage()"
                                                        aria-label="Send Message">Send</button>
                                                    <button type="reset" class="btn btn-light"
                                                        aria-label="Clear Message">Clear</button>
                                                </form>
                                            <?php else: ?>
                                                <p class="card-description">Feel free to reach out to us using the contact
                                                    information below or by sending us a message.</p>
                                                <!-- Left Column with Root Profile -->
                                                <div class="row d-flex align-items-stretch">
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <div class="admin-contact-card">
                                                            <div class="text-center text-content">
                                                                <h4 class="text-light font-weight-bold mb-3">
                                                                    Super-Administrator
                                                                    Contact
                                                                </h4>

                                                                <?php if (!empty($root['photo_path'])): ?>
                                                                    <img src="<?php echo htmlspecialchars($root['photo_path']); ?>"
                                                                        alt="Root Profile Picture"
                                                                        class="img-thumbnail rounded-circle mb-3"
                                                                        style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                                                <?php endif; ?>

                                                                <?php if (!empty($root['name'])): ?>
                                                                    <h4 class="mb-2 text-warning font-weight-bold">
                                                                        <?php echo htmlspecialchars($root['name']); ?>
                                                                    </h4>
                                                                <?php endif; ?>

                                                                <hr class="my-2">
                                                            </div>

                                                            <div class="px-3 text-content">
                                                                <?php if (!empty($root['phone_number'])): ?>
                                                                    <p><i class="mdi mdi-phone-outline text-success"></i>
                                                                        <strong>Phone:</strong>
                                                                        <?php echo htmlspecialchars($root['phone_number']); ?>
                                                                    </p>
                                                                <?php endif; ?>

                                                                <?php if (!empty($root['email'])): ?>
                                                                    <p><i class="mdi mdi-email-outline text-danger"></i>
                                                                        <strong>E-mail:</strong>
                                                                        <a href="mailto:<?php echo htmlspecialchars($root['email']); ?>"
                                                                            class="text-white">
                                                                            <?php echo htmlspecialchars($root['email']); ?>
                                                                        </a>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Right Column with Message Form -->
                                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                                        <h4>Send us a Message:</h4>
                                                        <p>Writing as:
                                                            <strong><?php echo htmlspecialchars($_SESSION['user']['name']); ?></strong>
                                                        </p>
                                                        <p>Role:
                                                            <i><?php echo htmlspecialchars($_SESSION['user']['role']); ?></i>
                                                        </p>
                                                        <p class="text-muted">Heya, it is me again, Chara Dreemurr, the
                                                            Root-Admin/Super-Administrator of this system! I am here to help
                                                            you
                                                            with
                                                            everything you need!
                                                            If you have any questions or need assistance, feel free to reach
                                                            out
                                                            to me. I am
                                                            here to make your experience as smooth as possible. Let's work
                                                            together to make
                                                            this system even better!
                                                        </p>
                                                        <form id="contactForm">
                                                            <div class="form-group">
                                                                <label for="message">Message</label>
                                                                <textarea class="form-control" id="message" rows="5"
                                                                    style="width: 100%; min-height: 150px;" required></textarea>
                                                            </div>
                                                            <button type="button" class="btn btn-primary mr-2"
                                                                onclick="sendMessage()" aria-label="Send Message">Send</button>
                                                            <button type="reset" class="btn btn-light"
                                                                aria-label="Clear Message">Clear</button>
                                                            <div id="messageSentAlert" class="alert alert-success mt-3"
                                                                style="display:none;">Message sent!</div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <?php
                $userModel = new User();
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
</div>

<!-- For About's Counters animation -->
<?php
// User Model and user count
$userModel = new User();
$total_users = $userModel->countAll();    //renamed

//Courier Model and courier count
$courierModel = new Courier();
$total_couriers = $courierModel->countAll(); //renamed
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('.display-4');
        const aboutSection = document.getElementById('about');
        const targets = {
            'offices-BG': 26,
            'kilometers-covered': 1928,
            'people-team': <?php echo $total_couriers; ?>,
            'clients': <?php echo $total_users; ?>
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

<!-- About's Courier Team -->
<style>
    .courier-showcase {
        display: flex;
        overflow-x: auto;
        /* Enable horizontal scrolling */
        scroll-snap-type: x mandatory;
        /* Optional: Snap scrolling */
        padding-bottom: 15px;
        /* Add padding for the scrollbar */
        margin-bottom: 20px;
    }

    .courier-card {
        flex: 0 0 auto;
        /* Don't grow or shrink, auto width */
        width: 300px;
        /* Adjust card width as needed */
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-right: 20px;
        /* Spacing between cards */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        scroll-snap-align: start;
        /* Optional: Snap to the start of the card */
    }

    .courier-card:hover {
        transform: scale(1.02);
        /* Slight zoom on hover */
    }

    .courier-image {
        width: 100%;
        height: 200px;
        /* Adjust image height as needed */
        object-fit: cover;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .courier-image-placeholder {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        background-color: #f0f0f0;
        /* Light background for the placeholder */
    }

    .courier-info {
        padding: 15px;
    }

    .courier-name {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .courier-description {
        font-size: 1em;
        color: #555;
        margin-bottom: 10px;
    }

    .courier-phone {
        font-size: 1em;
        color: #333;
    }

    .courier-phone i {
        margin-right: 5px;
    }

    /* Optional: Hide scrollbar on WebKit browsers (Chrome, Safari) */
    .courier-showcase::-webkit-scrollbar {
        display: none;
    }

    /* Optional: Style scrollbar on Firefox */
    .courier-showcase {
        scrollbar-width: thin;
        scrollbar-color: #888 #f5f5f5;
    }

    .courier-showcase::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .courier-showcase::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }

    .courier-showcase::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

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

<!-- Root PFP BG for Contacts -->
<!-- Extras/Dashboard/ContactsBG/BG.png -->
<style>
    .admin-contact-card {
        position: relative;
        background: url('Extras/Dashboard/ContactsBG/BG.png') no-repeat center;
        background-size: cover;
        opacity: 0.85;
        padding: 20px;
        border-radius: 8px;
        color: #ffffff;
        /* Ensures text remains bright */
    }

    /* Soft transparent background behind the text for contrast */
    .admin-contact-card .text-content {
        position: relative;
        background: rgba(255, 255, 255, 0.15);
        /* Light background for better readability */
        padding: 10px;
        border-radius: 8px;
    }

    /* Ensure link contrast */
    .admin-contact-card a {
        color: #ffffff;
        font-weight: bold;
    }

    /* Flexbox for layout */
    .admin-contact-card,
    .guest-contact-form {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        /* Ensures both halves stretch equally */
    }
</style>

<!-- For Message Sending -->
<style>
    /* notification-styles.css */
    /* Container to manage notifications */
    .notification-container {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 0 !important;
        z-index: 9999 !important;
        pointer-events: none !important;
        /* Let clicks pass through */
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
    }

    /* Core notification styling */
    .notification-popup {
        position: fixed !important;
        top: -100px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        z-index: 9999 !important;
        padding: 15px 25px !important;
        border-radius: 5px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
        transition: top 0.5s ease-in-out !important;
        display: flex !important;
        align-items: center !important;
        width: auto !important;
        min-width: 250px !important;
        max-width: 90% !important;
        pointer-events: auto !important;
        /* Allow interaction with notification */
        margin: 0 !important;
    }

    .notification-popup.success {
        background-color: #28a745 !important;
        color: white !important;
        border-left: 4px solid #1e7e34 !important;
    }

    .notification-popup.error {
        background-color: #dc3545 !important;
        color: white !important;
        border-left: 4px solid #bd2130 !important;
    }

    .notification-popup.info {
        background-color: #17a2b8 !important;
        color: white !important;
        border-left: 4px solid #138496 !important;
    }

    .notification-popup.show {
        top: 20px !important;
        /* Slide to this position when shown */
    }

    .notification-content {
        display: flex !important;
        align-items: center !important;
        flex-grow: 1 !important;
    }

    .notification-icon {
        margin-right: 12px !important;
        font-size: 20px !important;
    }

    .close-notification {
        margin-left: 15px !important;
        background: none !important;
        border: none !important;
        color: white !important;
        font-size: 18px !important;
        cursor: pointer !important;
        padding: 0 !important;
        opacity: 0.8 !important;
    }

    .close-notification:hover {
        opacity: 1 !important;
    }
</style>
<script>
    // Create a container for notifications
    function ensureNotificationContainer() {
        // Check if the container already exists
        if (document.querySelector('.notification-container')) {
            return document.querySelector('.notification-container');
        }

        // Create a fresh container
        const container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
        return container;
    }

    // Function to display a notification popup
    function showNotification(message, type = 'success', duration = 5000) {
        console.log("Showing notification:", message, type); // Debug log

        // Ensure container exists
        const container = ensureNotificationContainer();

        // Create the notification element
        const notification = document.createElement('div');
        notification.className = `notification-popup ${type}`;

        // Set the appropriate icon
        let icon = '✓';
        if (type === 'error') icon = '⚠';
        if (type === 'info') icon = 'ℹ';

        notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${icon}</span>
            <span>${message}</span>
        </div>
        <button class="close-notification" onclick="closeNotification(this.parentElement)">&times;</button>
    `;

        // Add to the container
        container.appendChild(notification);

        // Force reflow to ensure the transition works
        notification.offsetHeight;

        // Show the notification with animation (wrapped in timeout to ensure DOM update)
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Auto-hide after the specified duration
        if (duration > 0) {
            setTimeout(() => {
                closeNotification(notification);
            }, duration);
        }

        return notification;
    }

    // Function to close a notification
    function closeNotification(notificationElement) {
        if (!notificationElement) return;

        // Hide the notification
        notificationElement.classList.remove('show');

        // Remove from DOM after the transition ends
        setTimeout(() => {
            if (notificationElement.parentElement) {
                notificationElement.parentElement.removeChild(notificationElement);
            }
        }, 500); // Match this to the transition time
    }

    // Modified sendMessage function that uses our notification system
    function sendMessage() {
        const message = document.getElementById('message').value;
        if (message.trim() !== '') {
            // Show loading indication
            const loadingNotif = showNotification('Sending message...', 'info', 0);

            // AJAX request to send the message
            $.ajax({
                url: '<?php echo INSTALL_URL; ?>?controller=Messages&action=sendMessage',
                type: 'POST',
                dataType: 'json',
                data: { message: message },
                success: function (response) {
                    // Close the loading notification
                    closeNotification(loadingNotif);

                    if (response.status === 'success') {
                        // Display success message as popup
                        showNotification(response.message || 'Message sent successfully!', 'success');
                        $('#contactForm')[0].reset(); // Clear the form
                    } else {
                        // Display error message as popup
                        showNotification(response.message || 'Failed to send message.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    // Close the loading notification
                    closeNotification(loadingNotif);

                    // Handle AJAX errors with popup
                    console.error('AJAX Error:', status, error);
                    showNotification('An error occurred while sending the message. Please try again.', 'error');
                }
            });
        } else {
            showNotification('Please enter your message.', 'error');
        }
    }

    // This is to prevent form submission from reloading the page
    $(document).ready(function () {
        $('#contactForm').submit(function (event) {
            event.preventDefault();
        });
        // For testing - uncomment to see the notification when the page loads
        // setTimeout(() => {
        //     showNotification('Successfully claimed credits! Come back tomorrow for more!', 'success');
        // }, 1000);
    });
</script>


<!-- For Home Page - Main -->
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