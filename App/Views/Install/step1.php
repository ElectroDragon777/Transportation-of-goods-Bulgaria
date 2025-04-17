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
                            <h2 class="card-title text-center mb-4">= Database Configuration: =</h2>
                            <p class="card-text mb-2">Please enter your Database's Connection Details below:</p>
                            <?php if (isset($tpl['error_message'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $tpl['error_message']; ?>
                                </div>
                            <?php endif; ?>
                            <form action="<?php echo INSTALL_URL; ?>?controller=Install&action=step1" method="POST">
                                <div class="mb-3">
                                    <label for="hostname" class="form-label shadowify" style="width: 100%;">Database
                                        Hostname:</label> <input type="text" class="form-control" id="hostname"
                                        name="hostname" placeholder="localhost" value="<?php
                                        if (DEFAULT_HOST != '{hostname}') {
                                            echo DEFAULT_HOST;
                                        }
                                        ?>" required>
                                    <div class="form-text">
                                        <p class="text-muted small">Usually "localhost" (e.g. localhost:8111) or an IP
                                            Address
                                            (e.g. 127.0.0.1:8111)</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label shadowify" style="width: 100%;">Connection
                                        Username:</label> <input type="text" class="form-control" id="username"
                                        name="username" value="<?php
                                        if (DEFAULT_USER != '{host_username}') {
                                            echo DEFAULT_USER;
                                        }
                                        ?>" required>
                                         <div class="form-text">
                                        <p class="text-muted small">Must be 'root' for site's purposes.</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="database" class="form-label shadowify" style="width: 100%;">Database
                                        Name:</label> <input type="text" class="form-control" id="database"
                                        name="database" value="<?php
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
                        <small>-- Step 1 of 5 - Database Configuration --</small>
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
        /* Increased width */
        max-width: 900px;
        /* Increased max width */
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
        /* Adjust button styles if needed */
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
        /* (Steps_Active) */
        background-color: #ffc107;
        color: white;
    }

    .step-line {
        flex-grow: 1;
        height: 2px;
        background-color: #333;
        margin: 0 5px;
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