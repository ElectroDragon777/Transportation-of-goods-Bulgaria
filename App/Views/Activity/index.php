<div class="container-scroller">
    <div class="row">
        <div class="col-sm-12">
            <div>
                <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="mb-0">Your Orders:</h3>
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <div class="d-flex align-items-center flex-wrap text-nowrap">
                            <div class="form-group mb-0 me-3">
                                (<?php echo $_SESSION['user']['name'] ?>'s)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="feature-cards">
                        <div class="feature-card">
                            <div class="feature-card-bg"
                                style="background-image: url('Extras/Dashboard/Activity/orderingontheway.jpg');">
                            </div>
                            <div class="feature-card-content">
                                <h3 class="feature-card-title">Your orders are here!</h3>
                            </div>
                        </div>
                        <div class="feature-card">
                            <div class="feature-card-bg"
                                style="background-image: url('Extras/Dashboard/Main/Courier_because_I_can.png');">
                            </div>
                            <div class="feature-card-content">
                                <h3 class="feature-card-title">You can always check here for actiivity purposes!</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <div>
                            <div class="btn-wrapper">
                                <a id="share-orders" class="btn btn-outline-dark align-items-center"><i
                                        class="icon-share"></i> Share</a>
                                <a id="print-orders" class="btn btn-outline-dark align-items-center"><i
                                        class="icon-printer"></i> Print</a>
                                <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])) { ?>
                                    <a href="<?php echo INSTALL_URL; ?>?controller=Order&action=create"
                                        class="btn btn-primary text-white me-0"><i class="icon-plus"></i> New
                                        Order</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])) { ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button id="bulk-delete-orders-btn" class="btn btn-danger d-none">
                                        <i class="fa fa-trash"></i> Delete Selected (<span
                                            id="selected-count-orders">0</span>)
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="table-responsive" id="container-order-id">

                            <?php
                            include 'component/table.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Share Modal -->
    <div class="modal fade" id="ordersShareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-3">
                        <button type="button" class="btn btn-outline-primary export-format-orders" data-format="pdf">
                            <i class="icon-file-pdf"></i> Export as PDF
                        </button>
                        <button type="button" class="btn btn-outline-success export-format-orders" data-format="excel">
                            <i class="icon-file-excel"></i> Export as Excel
                        </button>
                        <button type="button" class="btn btn-outline-info export-format-orders" data-format="csv">
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
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        background-color: rgba(245, 245, 245, 0);
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
        color: rgb(53, 53, 53);
    }
</style>