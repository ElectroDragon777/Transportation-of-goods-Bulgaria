<div class="install-container">
    <div class="install-logo">
        <h1>Elec-Transport Setup</h1>
    </div>
    <div class="steps">
        <div class="step completed">1</div>
        <div class="step-line completed"></div>
        <div class="step active">2</div>
        <div class="step-line"></div>
        <div class="step">3</div>
        <div class="step-line"></div>
        <div class="step">4</div>
        <div class="step-line"></div>
        <div class="step">5</div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Create Root Account</h2>
            <p class="card-text mb-4">Please create a root account for the application:</p>

            <?php if (isset($tpl['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $tpl['error_message']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step2" method="POST">
                <div class="mb-3">
                    <label for="root_name" class="form-label">Root Name</label>
                    <input type="text" class="form-control" id="rootName" name="root_name" value="<?php
                    if (!empty($tpl['root']['name'])) {
                        echo $tpl['root']['name'];
                    }
                    ?>" required>
                </div>
                <div class="mb-3">
                    <label for="root_email" class="form-label">Root Email</label>
                    <input type="email" class="form-control" id="rootEmail" name="root_email" value="<?php
                    if (!empty($tpl['root']['email'])) {
                        echo $tpl['root']['email'];
                    }
                    ?>" required>
                    <div class="form-text">This email will be used for login and important notifications</div>
                </div>
                <div class="mb-3">
                    <label for="root_password" class="form-label">Root Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="rootPassword" name="root_password" required>
                        <i class="password-toggle-icon fa fa-eye" data-target="rootPassword"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="root_password_confirm" class="form-label">Confirm Password</label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="rootPasswordConfirm"
                            name="root_password_confirm" required>
                        <i class="password-toggle-icon fa fa-eye" data-target="rootPasswordConfirm"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step1"
                        class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Next Step</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-4 text-center text-muted">
        <small>Step 2 of 5 - Root Account Creation</small>
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