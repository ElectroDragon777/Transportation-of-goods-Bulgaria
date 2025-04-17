<div class="container-scroller">
    <div class="row">
        <div class="col-12">
            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <div class="install-logo">
                        <h1>Elec-Transport Setup</h1>
                    </div>
                    <p class="card-text mb-2 text-center text-muted"><strong>Steps:</strong></p>
                    <div class="steps" style="display: flex; align-items: center;">
                        <div class="step completed">1</div>
                        <div class="step-line completed"></div>
                        <div class="step completed">2</div>
                        <div class="step-line completed"></div>
                        <div class="step active">3</div>
                        <div class="step-line"></div>
                        <div class="step">4</div>
                        <div class="step-line"></div>
                        <div class="step">5</div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">= PayPal Account Settings =</h2>
                            <p class="card-text mb-4">Please enter your PayPal business account details: [Note, soon
                                will be
                                SANDBOX ACCOUNTS]</p>

                            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step3" method="POST">
                                <div class="mb-3">
                                    <label for="paypal_business_email" class="form-label shadowify">PayPal Business
                                        Email</label> <input type="email" class="form-control" id="paypalBusinessEmail"
                                        name="paypal_email" value="<?php
                                        if (!empty($tpl['paypal_email'])) {
                                            echo $tpl['paypal_email'];
                                        }
                                        ?>" required>
                                    <div class="form-text text-muted small">That email will be associated with your
                                        PayPal
                                        Business Account, for now, make sure it is real.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="alert alert-info" role="alert">
                                        <i class="fa fa-info-circle me-2"></i>
                                        Don't have a PayPal Business account yet?
                                        <a href="https://www.paypal.com/bg/business/getting-started" target="_blank"
                                            class="alert-link">
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
            </div>
        </div>
    </div>
</div>


<style>
    .container-scroller {
        background-image: url('Extras/Dashboard/ContactsBG/BG.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        justify-content: center;
        align-items: center;
        position: relative;
        color: white;
        background-attachment: fixed;
        /* Keeps the background fixed  */
    }

    .container-scroller::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0.8;
        z-index: 0;
    }

    .container-scroller>.row>.col-12>.card {
        background-color: rgba(255, 255, 255, 0.9);
        color: #333;
        width: 90%;
        max-width: 900px;
        margin: 5% auto;
        padding: 2%;
    }

    .install-logo h1 {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
    }

    .card-title,
    .card-text,
    .list-group-item {
        color: #333;
    }

    .btn-primary {
        /* Adjust button styles if needed  */
    }

    .text-muted small {
        color: #ddd;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
    }

    .text-muted {
        color: #ddd;
        text-shadow: 1px 1px 2px rgba(70, 69, 69, 0.8);
    }

    .steps {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .steps p {
        margin-right: 10px;
        margin-bottom: 0;
    }

    .step {
        border: 2px solid #333;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px;
        color: #333;
        font-weight: bold;
    }

    .step.completed {
        background-color: #007bff;
        /* Consistent Completed Color  */
        color: white;
    }

    .step.active {
        background-color: #ffc107;
        /* Or any other active color */
        color: #333;
    }

    .step-line {
        flex-grow: 1;
        height: 2px;
        background-color: #333;
        margin: 0 5px;
    }

    .step-line.completed {
        background-color: #007bff;
    }

    .form-label.shadowify {
        color: #333;
        text-shadow: 1px 1px 2px rgba(19, 18, 18, 0.8);
        margin: 0;
    }

    .form-control {
        padding: 0.75rem 0.75rem;
        font-size: 1rem;
        height: auto;
    }

    .text-muted.small {
        color: #ddd;
        text-shadow: 1px 1px 2px rgba(71, 71, 71, 0.8);
        margin: 0;
    }

    .text-muted {
        color: #ddd;
        text-shadow: 1px 2px 2px rgba(95, 95, 95, 0.8);
        margin: 0;
    }
</style>