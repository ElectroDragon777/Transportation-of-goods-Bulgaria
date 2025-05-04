<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="card card-rounded mt-3">
                <div class="card-body" style="background: linear-gradient(rgba(255, 255, 255, 0.47), rgba(85, 85, 85, 0.23)), url('Extras/Controllers/bluebackground_order_creation.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover; border-radius: 25px;">
                    <h4 class="card-title with-line">Create New Parcel</h4>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form class="forms-sample" method="POST"
                        action="<?php echo INSTALL_URL; ?>?controller=Pallet&action=create">
                        <input type="hidden" name="send" value="1" />

                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">Parcel Category</h5>
                            </div>
                            <div class="form-group col-md-4 mb-3 d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="category" id="typeDocument"
                                        value="document" required>
                                    <label class="form-check-label" for="typeDocument">Document</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="category" id="typePallet"
                                        value="parcel" required>
                                    <label class="form-check-label" for="typePallet">Parcel</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">General Information (Name, Category and Weight)</h5>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="name" class="form-label">Parcel Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="weight_kg" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.1" min="0.1" class="form-control" id="weight_kg"
                                    name="weight_kg" required>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">Dimensions</h5>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="size_x_cm" class="form-label">Size X (cm)</label>
                                <input type="number" step="1" min="1" class="form-control" id="size_x_cm"
                                    name="size_x_cm" required>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="size_y_cm" class="form-label">Size Y (cm)</label>
                                <input type="number" step="1" min="1" class="form-control" id="size_y_cm"
                                    name="size_y_cm" required>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="size_z_cm" class="form-label">Size Z (cm)</label>
                                <input type="number" step="1" min="1" class="form-control" id="size_z_cm"
                                    name="size_z_cm" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5>Extra Information</h5>
                            </div>

                            <!-- <div class="form-group col-md-6 mb-3">
                                <label for="code_billlanding" class="form-label">Code Billlanding
                                    (Auto-assigned)</label>
                                <input type="text" class="form-control" id="code_billlanding" name="code_billlanding"
                                    value="</*?php echo isset($next_billlanding_code) ? $next_billlanding_code : ''; */?>"
                                    readonly style="background-color: #f8f9fa;">
                                <small class="form-text text-muted">This code is automatically assigned by the
                                    system.</small>
                            </div> -->
                            <div class="form-group col-md-12 mb-3">
                                <label for="stock" class="form-label">Count</label>
                                <input type="number" step="0.1" min="0.1" class="form-control" id="stock" name="stock"
                                    required>
                            </div>
                            <div class="form-group col-md-12 mb-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    style="resize: vertical;"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-white me-0">Create Parcel</button>
                                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"
                                    class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Footer -->
                <?php
                $userModel = new App\Models\User();
                $root = $userModel->getFirstBy(['role' => 'root']);
                $root_name = $root['name'];
                $root_phone = $root['phone_number'];
                $root_email = $root['email'];
                ?>
                <footer class="footer">
                    <div class="container">
                        <div class="footer-content">
                            <div class="footer-section">
                                <h3 class="footer-title">Elec-Transport</h3>
                                <p>Providing quality transportation services nationwide since 2016.</p>
                            </div>
                            <div class="footer-section">
                                <h3 class="footer-title">Contact Us</h3>
                                <ul class="footer-list">
                                    <li>Varna 9020 - Boul. Yanosh Huniadi 192</li>
                                    <li><?php echo $root_phone ?></li>
                                    <li><?php echo $root_email ?></li>
                                </ul>
                            </div>
                        </div>
                        <div class="copyright">
                            <p>&copy; 2025 Elec-Transport. All rights reserved.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
<style>
    .d-flex.align-items-center {
        align-items: center;
        /* Ensure vertical alignment */
    }

    .form-check {
        margin-left: 20px;
        /* Adjust spacing between radio buttons */
    }

    .form-check-label {
        margin-left: 5px;
        /* Adjust spacing between radio button and label */
    }

    .form-label.me-3.mb-0 {
        margin-bottom: 0;
        /* Remove default bottom margin */
        line-height: 1.5;
        /* Adjust line height for vertical alignment */
    }
</style>

<style>
    .card-title.with-line {
        position: relative;
        padding-bottom: 10px;
        /* Space for the line */
    }

    .card-title.with-line::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-image: linear-gradient(to right, #9c27b0, #673ab7);
    }

    .h5.with-line {
        position: relative;
        padding-bottom: 10px;
        /* Space for the line */
    }

    .h5.with-line::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-image: linear-gradient(to right, #9c27b0, #673ab7);
    }

    input:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const documentRadio = document.getElementById('typeDocument');
        const palletRadio = document.getElementById('typePallet');
        const weightInput = document.getElementById('weight_kg');
        const sizeXInput = document.getElementById('size_x_cm');
        const sizeYInput = document.getElementById('size_y_cm');
        const sizeZInput = document.getElementById('size_z_cm');
        const countInput = document.getElementById('stock');
        const NameInput = document.getElementById('name');

        function updateFieldsForType() {
            if (documentRadio.checked) {
                // Document is selected
                // A fix, funnily enough, was the input should not be disabled, but values shall be set.
                // weightInput.disabled = true;
                weightInput.value = '0.2'; // Default weight for documents
                sizeXInput.value = 21; // A4 width in cm
                // sizeXInput.disabled = true;
                sizeYInput.value = 30; // A4 height in cm is 29.7 cm, but it needs to be rounded to 30 cm
                // sizeYInput.disabled = true;
                // sizeZInput.disabled = true;
                sizeZInput.value = '1'; // Default thickness for documents
                // countInput.disabled = true;
                countInput.value = '1'; // Default count for documents
                // NameInput.disabled = true;
                NameInput.value = 'Documents'; // Default name for documents
            } else {
                // Parcel is selected
                weightInput.disabled = false;
                sizeXInput.disabled = false;
                sizeYInput.disabled = false;
                sizeZInput.disabled = false;
                NameInput.disabled = false;
                countInput.disabled = false;
                weightInput.value = '';
                sizeXInput.value = '';
                sizeYInput.value = '';
                sizeZInput.value = '';
                NameInput.value = '';
                countInput.value = '';
            }
        }

        // Add event listeners to both radio buttons
        documentRadio.addEventListener('change', updateFieldsForType);
        palletRadio.addEventListener('change', updateFieldsForType);

        // Initialize fields based on default selection
        updateFieldsForType();
    });
</script>

<!-- Footer -->
<style>
    /* General Styles */
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --accent-color: #e74c3c;
        --light-bg: #f8f9fa;
        --dark-bg: #343a40;
        --text-color: #333;
        --light-text: #f8f9fa;
    }

    body {
        font-family: 'Roboto', Arial, sans-serif;
        color: var(--text-color);
        line-height: 1.6;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        background-color: rgba(245, 245, 245, 0);
    }

    /* Override Bootstrap white boxes to make transparent */
    .card,
    .card-body {
        background-color: transparent !important;
        border: none !important;
    }

    /* Hero Section Styles */
    .hero-section {
        background: linear-gradient(rgba(44, 62, 80, 0.7), rgba(44, 62, 80, 0.7)), url('https://images.unsplash.com/photo-1592838064575-70ed626d3a0e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
        background-size: cover;
        border-radius: 25px;
        color: #fff;
        text-align: center;
        padding: 100px 0;
        margin-bottom: 50px;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: rgb(25, 13, 190);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-subtitle {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: rgb(255, 255, 255);
    }

    .hero-description {
        font-size: 1.2rem;
        max-width: 800px;
        margin: 0 auto;
        color: rgb(255, 255, 255);
    }

    /* Feature Cards Styles */
    .feature-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 50px;
    }

    .feature-card {
        flex: 0 0 calc(33.333% - 20px);
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 20px rgba(0, 0, 0, 0.2);
    }

    .feature-card-bg {
        height: 200px;
        background-size: cover;
        background-position: center;
    }

    .feature-card-content {
        padding: 20px;
        background-color: #fff;
        text-align: center;
    }

    .feature-card-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--secondary-color);
    }

    /* Quote Section Styles */
    .quote-section {
        background: linear-gradient(rgba(52, 152, 219, 0.8), rgba(52, 152, 219, 0.8)), url('https://images.unsplash.com/photo-1567501077737-4a931a4e5e7c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
        background-size: cover;
        color: #fff;
        text-align: center;
        padding: 70px 0;
        margin: 50px 0;
    }

    .quote-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .quote-heading {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .quote-phone {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #fff;
    }

    .quote-text {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto 15px;
    }

    /* Footer Styles */
    .footer {
        background-color: var(--dark-bg);
        color: var(--light-text);
        padding: 50px 0 20px;
        border-radius: 25px;
    }

    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .footer-section {
        flex: 0 0 calc(50.000% - 30px);
        margin-bottom: 30px;
    }

    .footer-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--primary-color);
    }

    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        margin-bottom: 10px;
    }

    .footer-list a {
        color: var(--light-text);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-list a:hover {
        color: var(--primary-color);
    }

    .copyright {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* About Page Specific Styles */
    #about {
        padding: 20px 0;
    }

    .card-title-dash {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 20px;
    }

    .card-description {
        margin-bottom: 20px;
        line-height: 1.7;
    }

    .card-rounded {
        border-radius: 10px;
        overflow: hidden;
    }

    .border-primary,
    .border-success,
    .border-info {
        border-width: 2px !important;
    }

    /* .courier-showcase {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    } */

    /* .courier-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        flex: 0 0 calc(50% - 10px);
        display: flex;
        transition: transform 0.3s ease;
    } */

    .courier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    /* .courier-image,
    .courier-image-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f5f5;
    } */

    .courier-info {
        padding: 15px;
        flex: 1;
    }

    .courier-name {
        margin: 0 0 10px;
        color: var(--secondary-color);
    }

    .courier-description {
        margin: 0 0 10px;
        font-size: 0.9rem;
        color: #666;
    }

    .courier-phone {
        margin: 0;
        font-size: 0.9rem;
        color: var(--primary-color);
    }

    .courier-phone i {
        margin-right: 5px;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Responsive styles */
    @media (max-width: 992px) {
        .feature-card {
            flex: 0 0 calc(50% - 15px);
        }

        .footer-section {
            flex: 0 0 calc(50% - 15px);
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.5rem;
        }

        .feature-card {
            flex: 0 0 100%;
        }

        .footer-section {
            flex: 0 0 100%;
        }

        .quote-phone {
            font-size: 1.8rem;
        }
    }

    /* Additional styles for counters in About page */
    .text-center .display-4 {
        transition: all 0.5s ease;
    }

    /* Custom backgrounds for sections */
    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Enhance buttons */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 10px 25px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
</style>