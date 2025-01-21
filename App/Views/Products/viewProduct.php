<div class="card">
    <div class="card-body">
        <h4 class="card-title">View Product</h4>

        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Product Name</label>
            <div class="col-sm-9">
                <p><?php echo $tpl['product']['product_name']; ?></p>
            </div>
        </div>
        <div class="form-group row">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Product Description</label>
            <div class="col-sm-9">
                <p><?php echo $tpl['product']['product_description']; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div id="image-container-id">
            <?php
            include 'getImages.php';
            ?>
        </div>
    </div>
</div>