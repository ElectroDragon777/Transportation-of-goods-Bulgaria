<div class="container-scroller">
    <div class="row">
        <div class="col-sm-12">
            <div class="">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <div>
                        <div class="btn-wrapper">
                            <a id="share-pallets" class="btn btn-outline-dark align-items-center"><i
                                    class="icon-share"></i> Share</a>
                            <a id="print-pallets" class="btn btn-outline-dark align-items-center"><i
                                    class="icon-printer"></i> Print</a>
                            <a href="<?php echo INSTALL_URL . "?controller=Pallet&action=create"; ?>"
                                class="btn btn-primary text-white me-3"><i class="icon-plus"></i> New Parcel</a>
                        </div>
                    </div>
                </div>

                <div class="card card-rounded mt-3">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 font-weight-bold text-primary">Advanced Filters</h5>
                                <button type="button"
                                    class="btn btn-primary btn-sm rounded-pill px-3 d-flex align-items-center"
                                    data-bs-toggle="collapse" data-bs-target="#filters-container" aria-expanded="false"
                                    aria-controls="filters-container">
                                    <i class="fa fa-filter me-2"></i>
                                    <span>Toggle Filters</span>
                                </button>
                            </div>
                        </div>
                        <div class="collapse show card-body" id="filters-container">
                            <form id="pallet-filter-form">
                                <div class="card p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="filter-name"
                                                placeholder="Search by name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-description" class="form-label">
                                                Description</label>
                                            <input type="text" class="form-control" id="filter-description"
                                                placeholder="Search by description">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-description" class="form-label">Category</label>
                                            <input type="text" class="form-control" id="filter-category"
                                                placeholder="Search by description">
                                        </div>

                                        <!-- <div class="col-md-6 mb-3">
                                            <label for="filter-price-min" class="form-label">Min Price
                                                (inclusive)</label>
                                            <input type="number" class="form-control" id="filter-price-min"
                                                placeholder="Min price">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-price-max" class="form-label">Max Price
                                                (inclusive)</label>
                                            <input type="number" class="form-control" id="filter-price-max"
                                                placeholder="Max price">
                                        </div> -->

                                        <div class="col-md-6 mb-3">
                                            <label for="filter-size-x" class="form-label">Size X (cm)</label>
                                            <input type="number" class="form-control" id="filter-size-x"
                                                placeholder="Size X">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-size-y" class="form-label">Size Y (cm)</label>
                                            <input type="number" class="form-control" id="filter-size-y"
                                                placeholder="Size Y">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-size-z" class="form-label">Size Z (cm)</label>
                                            <input type="number" class="form-control" id="filter-size-z"
                                                placeholder="Size Z">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="filter-weight" class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" id="filter-weight"
                                                placeholder="Weight">
                                        </div>
                                        <!-- <div class="col-md-6 mb-3">
                                            <label for="filter-code-billlanding" class="form-label">Code
                                                Billlanding</label>
                                            <input type="number" class="form-control" id="filter-code-billlanding"
                                                placeholder="Code Billlanding" oninput="formatBilllanding(this)">
                                        </div> -->
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-3">
                                        <button type="button" class="btn btn-light" id="reset-filters-pallet">
                                            <i class="icon-refresh"></i> Reset
                                        </button>
                                        <button type="button" class="btn btn-primary" id="apply-filters-pallet">
                                            <i class="icon-search"></i> Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])) { ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button id="bulk-delete-pallets-btn" class="btn btn-danger d-none">
                                        <i class="fa fa-trash"></i> Delete Selected (<span
                                            id="selected-count-pallets">0</span>)
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="table-responsive" id="container-pallet-id">
                            <?php
                            include 'component/table.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Parcel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this parcel?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-id="" id="delete-btn-pallet-id">Delete</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePallets" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Parcels</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete these parcels?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-ids="" id="delete-btn-pallets-ids">Delete</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="palletsShareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Parcels</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-outline-primary export-format-pallets" data-format="pdf">
                        <i class="icon-file-pdf"></i> Export as PDF
                    </button>
                    <button type="button" class="btn btn-outline-success export-format-pallets" data-format="excel">
                        <i class="icon-file-excel"></i> Export as Excel
                    </button>
                    <button type="button" class="btn btn-outline-info export-format-pallets" data-format="csv">
                        <i class="icon-file-text"></i> Export as CSV
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Format Billlanding to have leading 0s -->
<!-- <script>
    function formatBilllanding(input) {
        let value = input.value;
        if (value.length > 10) {
            value = value.slice(0, 10); // Truncate to 10 digits if longer
        }
        input.value = value.padStart(10, '0'); // Pad with leading zeros

        // Prevent value from becoming 10 zeros
        if (input.value === '0000000000') {
            input.value = ''; // Clear the input field or you can use "0000000001", or any other value
        }
    }

    // Add event listener to format the initial value if any
    document.addEventListener('DOMContentLoaded', function () {
        const billlandingInput = document.getElementById('filter-code-billlanding');
        if (billlandingInput && billlandingInput.value) {
            formatBilllanding(billlandingInput);
        }
    });
</script> -->