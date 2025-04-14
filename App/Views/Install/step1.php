<div class="install-container">
    <div class="install-logo">
        <h1>Elec-Transport Setup</h1>
    </div>
    <div class="steps">
        <div class="step completed">1</div>
        <div class="step-line"></div>
        <div class="step">2</div>
        <div class="step-line"></div>
        <div class="step">3</div>
        <div class="step-line"></div>
        <div class="step">4</div>
        <div class="step-line"></div>
        <div class="step">5</div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Database Configuration</h2>
            <p class="card-text mb-4">Please enter your database connection details below:</p>

            <?php if (isset($tpl['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $tpl['error_message']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step1" method="POST">
                <div class="mb-3">
                    <label for="hostname" class="form-label">Database Hostname</label>
                    <input type="text" class="form-control" id="hostname" name="hostname" placeholder="localhost" value="<?php
                    if (DEFAULT_HOST != '{hostname}') {
                        echo DEFAULT_HOST;
                    }
                    ?>" required>
                    <div class="form-text">Usually "localhost" or an IP address</div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Connection Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php
                    if (DEFAULT_USER != '{host_username}') {
                        echo DEFAULT_USER;
                    }
                    ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Connection Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="password" name="password">
                        <i class="password-toggle-icon fa fa-eye" data-target="password"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="database" class="form-label">Database Name</label>
                    <input type="text" class="form-control" id="database" name="database" value="<?php
                    if (DEFAULT_DB != '{database_name}') {
                        echo DEFAULT_DB;
                    }
                    ?>" required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step0"
                        class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Next Step</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-4 text-center text-muted">
        <small>Step 1 of 5 - Database Configuration</small>
    </div>
</div>


<style>
    .install-container {
        background-image: url('your-image.jpg');
        /* Replace with your image path */
        background-size: cover;
        /* Cover the entire container */
        background-position: center;
        /* Center the image */
        background-repeat: no-repeat;
        /* Prevent image repetition */
        min-height: 100vh;
        /* Ensure it covers at least the viewport height */
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Center content vertically */
        align-items: center;
        /* Center content horizontally */
        position: relative;
        /* For overlay */
        color: white;
        /* Default text color (adjust as needed) */
    }

    .install-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black overlay */
        opacity: 0.8;
        z-index: 0;
        /* Behind the content */
    }

    .install-container>* {
        position: relative;
        z-index: 1;
        /* In front of the overlay */
    }

    .install-logo h1 {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        /* Text shadow for better readability */
    }

    .card {
        background-color: rgba(255, 255, 255, 0.9);
        /* Semi-transparent white card */
        color: #333;
        /* Darker text for card content */
    }

    .card-title,
    .card-text,
    .list-group-item {
        color: #333;
        /* Ensure text is readable */
    }

    .btn-primary {
        /* Adjust button styles if needed, e.g., text shadow */
    }

    .text-muted small {
        color: #ddd;
        /* Lighter text for the footer */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
    }
</style>