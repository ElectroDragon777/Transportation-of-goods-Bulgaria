<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="card card-rounded mt-3">
                <div class="card-body">
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