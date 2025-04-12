<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <h4 class="card-title">Create New Pallet</h4>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form class="forms-sample" method="POST"
                        action="<?php echo INSTALL_URL; ?>?controller=Pallet&action=create">
                        <input type="hidden" name="send" value="1" />

                        <div class="row">
                            <div class="col-12">
                                <h5>General Information</h5>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="name" class="form-label">Pallet Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    style="resize: vertical;"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5>Dimensions</h5>
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
                                <h5>Weight and Code</h5>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="weight_kg" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.1" min="0.1" class="form-control" id="weight_kg"
                                    name="weight_kg" required>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="code_billlanding" class="form-label">Code Billlanding
                                    (Auto-assigned)</label>
                                <input type="text" class="form-control" id="code_billlanding" name="code_billlanding"
                                    value="<?php echo isset($next_billlanding_code) ? $next_billlanding_code : ''; ?>"
                                    readonly style="background-color: #f8f9fa;">
                                <small class="form-text text-muted">This code is automatically assigned by the
                                    system.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-white me-0">Create Pallet</button>
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
    function formatBilllanding(input) {
        // Don't format if the field is readonly
        if (input.readOnly) return;

        let value = input.value;
        if (value.length > 10) {
            value = value.slice(0, 10); // Truncate to 10 digits if longer
        }
        input.value = value.padStart(10, '0'); // Pad with leading zeros

        // Prevent value from becoming 10 zeros
        if (input.value === '0000000000') {
            input.value = ''; // Clear the input field
        }
    }

    // Add event listener to format the initial value if any
    document.addEventListener('DOMContentLoaded', function () {
        const billlandingInput = document.getElementById('code_billlanding');
        if (billlandingInput && billlandingInput.value && !billlandingInput.readOnly) {
            formatBilllanding(billlandingInput);
        }
    });
</script>