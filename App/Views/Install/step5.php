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
                        <div class="step completed">4</div>
                        <div class="step-line completed"></div>
                        <div class="step completed">5</div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="check-icon text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                </svg>
                            </div>
                            <h2 class="card-title text-center mb-3"><strong><i>Congratulations!</i></strong></h2>
                            <p class="card-text mb-4 text-center"><strong>Everything is set up and ready to go!</strong></p>

                            <div class="alert alert-success mb-4 text-center" role="alert">
                                <h4 class="alert-heading">-~- Configuration files created! -~-</h4>
                                <p>All configuration files have been created and saved successfully. Your Database and
                                    Mail Server
                                    connections are properly set up!</p>
                            </div>

                            <div class="mb-4">
                                <h5>-- Next Steps: --</h5>
                                <p class="card-text mb-4 text-center">So,
                                    <?php echo isset($tpl['root']['name']) ? htmlspecialchars($tpl['root']['name']) : 'User'; ?>,
                                    my guidance has reached its end. You did wonderfully! Now:
                                </p>
                                <ul class="list-group list-group-flush mb-4">
                                    <li class="list-group-item bg-transparent">I. Log into your Admin Dashboard using
                                        your Email
                                        and Password; 
                                        <small class="text-muted"><strong><br>Use your RootName and RootPass!</strong></small>
                                    </li>
                                    
                                    <li class="list-group-item bg-transparent">II. Configure Settings; [Important for
                                        Starters]
                                        <small class="text-muted"><strong><br>Use your RootName and RootPass!</strong></small>
                                    </li>
                                    <li class="list-group-item bg-transparent">III. Start managing your company! Make
                                        couriers,
                                        information, and much more!
                                    </li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?php echo INSTALL_URL; ?>" class="btn btn-primary btn-lg">Visit site</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-center text-muted">
                        <small>-- Setup has completed successfully! Thank you very much for trusting us! --</small>
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
    .card-title,
    .card-text,
    .list-group-item {
        color: #333;
    }

    .list-group-item.shadowify {
        color: #333;
        text-shadow: 1px 1px 2px rgba(19, 18, 18, 0.8);
        margin: 0;
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

    .list-group-flush {
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
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