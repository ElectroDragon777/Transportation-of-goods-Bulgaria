<div class="install-container">
    <div class="install-logo">
        <h1>Elec-Transport Setup</h1>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">=== Welcome to the Web Installer! ===</h2>
            <h4 class="card-title text-center mb-4">Heya! Chara Dreemurr here, to help you throughout!</h4>
            <p class="card-text">You will be guided through the installation/setup process of the Great Diploma Thesis.
                Please
                make sure you have the following information ready:</p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item">I. Database Connection Details [HostName (localhost/127.0.0.1), UserName];
                </li>
                <li class="list-group-item">II. Root Account Information [RootName, RootPassword];</li>
                <li class="list-group-item">III. PayPal [Business] Email;</li>
                <p class="card-text">(Here SandBox-Emails will be used, it is a GDT only)</p>
                <li class="list-group-item">IV. Mail server configuration (Optional) [MailTrap Emails to Admin/Owner of
                    GDT]</li>
            </ul>
            <p class="card-text">This Setup is a 5-Step one. You will be going through all, first 3 are compulsory, last
                2, not that much.</p>
            <div class="d-grid gap-2">
                <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step1" class="btn btn-primary btn-lg">Take
                    up the Setup!</a>
            </div>
        </div>
    </div>
    <div class="mt-4 text-center text-muted">
        <small>== Elec-Transport Setup == -- Welcome --</small>
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