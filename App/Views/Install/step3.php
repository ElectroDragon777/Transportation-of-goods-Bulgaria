<div class="install-container">
    <div class="install-logo">
        <h1>Elec-Transport Setup</h1>
    </div>
    <div class="steps">
        <p class="card-text mb-4">Steps:</p>
        <div class="step completed">1</div>
        <div class="step-line"></div>
        <div class="step completed">2</div>
        <div class="step-line"></div>
        <div class="step active">3</div>
        <div class="step-line"></div>
        <div class="step">4</div>
        <div class="step-line"></div>
        <div class="step">5</div>
    </div>
    <div class="card shadow-sm mx-auto">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">=~= PayPal Account Settings =~=</h2>
            <p class="card-text mb-4">Please enter your PayPal business account details: [Note, soon will be SANDBOX
                ACCOUNTS]</p>

            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step3" method="POST">
                <div class="mb-3">
                    <label for="paypal_business_email" class="form-label">PayPal Business Email</label>
                    <input type="email" class="form-control" id="paypalBusinessEmail" name="paypal_email" value="<?php
                    if (!empty($tpl['paypal_email'])) {
                        echo $tpl['paypal_email'];
                    }
                    ?>" required>
                    <div class="form-text">That email will be associated with your PayPal Business Account, for now,
                        make sure it is real.</div>
                </div>
                <div class="mb-3">
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle me-2"></i>
                        Don't have a PayPal Business account yet?
                        <a href="https://www.paypal.com/bg/business/getting-started" target="_blank" class="alert-link">
                            Click here to create one!
                        </a>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step2"
                        class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Next Step</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-4 text-center text-muted">
        <small>-- Step 3 of 5 - PayPal Configuration --</small>
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