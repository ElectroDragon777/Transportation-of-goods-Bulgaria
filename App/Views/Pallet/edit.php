<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <h4 class="card-title with-line">Edit Parcel</h4>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <form class="forms-sample" method="POST"
                        action="<?php echo INSTALL_URL; ?>?controller=Pallet&action=edit">
                        <input type="hidden" name="id" value="<?php echo $tpl['id']; ?>" />

                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">General Information (Name, Type and Weight)</h5>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="name" class="form-label">Parcel Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo $tpl['name']; ?>" required>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="weight_kg" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.1" min="0.1" class="form-control" id="weight_kg"
                                    name="weight_kg" value="<?php echo $tpl['weight_kg']; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">Parcel Type</h5>
                            </div>
                            <div class="form-group col-md-4 mb-3 d-flex">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="typeDocument"
                                        value="document" <?php echo ($tpl['category'] == 'document') ? 'checked' : ''; ?>
                                        required>
                                    <label class="form-check-label" for="typeDocument">Document</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type" id="typePallet"
                                        value="pallet" <?php echo ($tpl['category'] == 'pallet') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="typePallet">Pallet</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">Dimensions</h5>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="size_x_cm" class="form-label">Size X (cm)</label>
                                <input type="number" step="1" min="1" class="form-control" id="size_x_cm"
                                    name="size_x_cm" value="<?php echo $tpl['size_x_cm']; ?>" required>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="size_y_cm" class="form-label">Size Y (cm)</label>
                                <input type="number" step="1" min="1" class="form-control" id="size_y_cm"
                                    name="size_y_cm" value="<?php echo $tpl['size_y_cm']; ?>" required>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="size_z_cm" class="form-label">Size Z (cm)</label>
                                <input type="number" step="1" min="1" class="form-control" id="size_z_cm"
                                    name="size_z_cm" value="<?php echo $tpl['size_z_cm']; ?>" required>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">Transportation Details</h5>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="start_point" class="form-label">Start Point</label>
                                <input type="text" class="form-control" id="start_point" name="start_point"
                                    value="<?php echo isset($tpl['start_point']) ? $tpl['start_point'] : ''; ?>">
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="end_destination" class="form-label">End Destination</label>
                                <input type="text" class="form-control" id="end_destination" name="end_destination"
                                    value="<?php echo isset($tpl['end_destination']) ? $tpl['end_destination'] : ''; ?>">
                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col-12">
                                <h5 class="with-line">Extra Information</h5>
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="stock" class="form-label">Count</label>
                                <input type="number" step="0.1" min="0.1" class="form-control" id="stock" name="stock"
                                    value="<?php echo $tpl['stock']; ?>" required>
                            </div>
                            <div class="form-group col-md-12 mb-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    style="resize: vertical;"><?php echo $tpl['description']; ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-white me-0">Update Parcel</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const documentRadio = document.getElementById('typeDocument');
        const palletRadio = document.getElementById('typePallet');
        const weightInput = document.getElementById('weight_kg');
        const sizeXInput = document.getElementById('size_x_cm');
        const sizeYInput = document.getElementById('size_y_cm');
        const sizeZInput = document.getElementById('size_z_cm');
        const countInput = document.getElementById('stock');
        const nameInput = document.getElementById('name');

        function updateFieldsForType() {
            if (documentRadio.checked) {
                // Document is selected
                weightInput.disabled = true;
                weightInput.value = '0.2'; // Default weight for documents
                sizeXInput.value = 21; // A4 width in cm
                sizeXInput.disabled = true;
                sizeYInput.value = 29.7; // A4 height in cm
                sizeYInput.disabled = true;
                sizeZInput.disabled = true;
                sizeZInput.value = '1'; // Default thickness for documents
                countInput.disabled = true;
                countInput.value = '1'; // Default count for documents
                nameInput.disabled = true;
                nameInput.value = 'Documents'; // Default name for documents
            } else {
                // Pallet is selected
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

        // Initialize fields based on current selection
        updateFieldsForType();
    });
</script>