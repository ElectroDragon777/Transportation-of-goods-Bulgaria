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
                        <div class="step completed">3</div>
                        <div class="step-line completed"></div>
                        <div class="step active">4</div>
                        <div class="step-line"></div>
                        <div class="step">5</div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">= Mail Configuration =</h2>
                            <p class="card-text mb-4">Please enter your Mail Server details below:</p>

                            <?php if (isset($tpl['error_message'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $tpl['error_message']; ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step4" method="POST">
                                <div class="mb-3">
                                    <label for="mail_host" class="form-label shadowify">Mail Host</label> <input
                                        type="text" class="form-control" id="mailHost" name="mail_host"
                                        placeholder="smtp.example.com" value="<?php
                                        if (MAIL_HOST != '{mail_host}') {
                                            echo MAIL_HOST;
                                        }
                                        ?>" required>
                                        <div class="form-text">
                                        <p class="text-muted small">E.g. <a href="https://mailtrap.io/" target="_blank">MailTrap.io</a></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="mail_port" class="form-label shadowify">Mail Port</label> <input
                                        type="number" class="form-control" id="mailPort" name="mail_port"
                                        placeholder="587" value="<?php
                                        if (MAIL_PORT != '{mail_port}') {
                                            echo MAIL_PORT;
                                        }
                                        ?>" required>
                                        <div class="form-text">
                                        <p class="text-muted small">Common ports: 25, 465, 587, 2525</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="mail_username" class="form-label shadowify">Mail's Username:</label>
                                    <input type="text" class="form-control" id="mailUsername" name="mail_username"
                                        value="<?php
                                        if (MAIL_USERNAME != '{mail_username}') {
                                            echo MAIL_USERNAME;
                                        }
                                        ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="mail_password" class="form-label shadowify">Mail's Password:</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="mailPassword"
                                            name="mail_password" required>
                                        <i class="password-toggle-icon fa fa-eye" data-target="mailPassword"></i>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step3"
                                        class="btn btn-secondary">Back</a>
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
            </div>
        </div>
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