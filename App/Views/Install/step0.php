<div class="container-scroller">
    <div class="row">
        <div class="col-12">
            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <div class="install-logo">
                        <h1>Elec-Transport Setup</h1>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">=== Welcome to the Web Installer! ===</h2>
                            <h4 class="card-title text-center mb-4">Heya! Chara Dreemurr here, to help you throughout!
                            </h4>
                            <p class="card-text">You will be guided through the installation/setup process of the Great
                                Diploma Thesis.
                                Please make sure you have the following information ready:</p>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item shadowify">I. Database Connection Details; <p
                                        class="text-muted small">[HostName (localhost/127.0.0.1), UserName]</p>
                                </li>
                                <li class="list-group-item shadowify">II. Root Account Information; <p
                                        class="text-muted small">[RootName, RootPassword]</p>
                                </li>
                                <li class="list-group-item shadowify">III. PayPal [Business] Email; <p
                                        class="text-muted small">(Here SandBox-Emails will be used, it is a GDT only)
                                    </p>
                                </li>

                                <li class="list-group-item shadowify">IV. Mail server configuration (Optional) <p
                                        class="text-muted small">[MailTrap Emails to Admin/Owner of
                                        GDT]</p>
                                </li>
                            </ul>
                            <p class="card-text">This Setup is a 5-Step one; Should not take long, maximum 5 minutes of your time! You will be going through all, Steps 1-3 are
                                compulsory, Step 4 is not compulsory.</p>
                            <div class="d-grid gap-2">
                                <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step1"
                                    class="btn btn-primary btn-lg">Take
                                    up the Setup!</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-center text-muted">
                        <small>== Elec-Transport Setup == -- Welcome --</small>
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
        /* Changed from auto to 100vh */
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
</style>