<div class="install-container">
    <div class="install-logo">
        <h1>Elec-Transport Setup</h1>
    </div>
    <div class="steps">
        <p class="card-text mb-4">Steps:</p>
        <div class="step completed">1</div>
        <div class="step-line completed"></div>
        <div class="step completed">2</div>
        <div class="step-line completed"></div>
        <div class="step completed">2</div>
        <div class="step-line completed"></div>
        <div class="step active">4</div>
        <div class="step-line"></div>
        <div class="step">5</div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">=~= Mail Configuration =~=</h2>
            <p class="card-text mb-4">Please enter your Mail Server details below:</p>

            <?php if (isset($tpl['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $tpl['error_message']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step3" method="POST">
                <div class="mb-3">
                    <label for="mail_host" class="form-label">Mail Host</label>
                    <input type="text" class="form-control" id="mailHost" name="mail_host"
                        placeholder="smtp.example.com" value="<?php
                        if (MAIL_HOST != '{mail_host}') {
                            echo MAIL_HOST;
                        }
                        ?>" required>
                </div>
                <div class="mb-3">
                    <label for="mail_port" class="form-label">Mail Port</label>
                    <input type="number" class="form-control" id="mailPort" name="mail_port" placeholder="587" value="<?php
                    if (MAIL_PORT != '{mail_port}') {
                        echo MAIL_PORT;
                    }
                    ?>" required>
                    <div class="form-text">Common ports: 25, 465, 587, 2525</div>
                </div>
                <div class="mb-3">
                    <label for="mail_username" class="form-label">Mail's Username:</label>
                    <input type="text" class="form-control" id="mailUsername" name="mail_username" value="<?php
                    if (MAIL_USERNAME != '{mail_username}') {
                        echo MAIL_USERNAME;
                    }
                    ?>" required>
                </div>
                <div class="mb-3">
                    <label for="mail_password" class="form-label">Mail's Password:</label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="mailPassword" name="mail_password" required>
                        <i class="password-toggle-icon fa fa-eye" data-target="mailPassword"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-secondary">Back</a>
                    <?php
                    if (!INSTALLED) {
                        echo '<a class="btn btn-warning skip-mail-config">Skip</a>';
                    }
                    ?>
                    <button type="submit" class="btn btn-primary">Next Step</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-4 text-center text-muted">
        <small>-- Step 4 of 5 - Mail Configuration --</small>
    </div>
</div>

<div class="modal fade" id="skipMailConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Skip Mail Configuration</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to skip mail configuration?</p>
                <p>Keep in mind: This means you will not be able to send system emails until configured later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="skip-mail-config-button">Skip</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<style>
    .install-container {
        background-image: url('Extras/Dashboard/ContactsBG/BG.png');
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