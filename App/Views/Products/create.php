<div class="card">
    <div class="card-body">
        <h4 class="card-title">Create product</h4>
        <form class="forms-sample" method="post" action="<?php INSTALL_URL ?>index.php?controller=Products&action=create">
            <input type="hidden" name="send_frm" value="1" />
            <div class="form-group row">
                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Product Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="gallery_name" placeholder="Name" name="product_name" value="" />
                </div>
            </div>

            <div class="form-group row">
                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Product Description</label>
                <div class="col-sm-9">
                    <textarea name="product_description" class="form-control"></textarea>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success mr-2">Save</button>
            <button class="btn btn-light">Cancel</button>
        </form>
    </div>
</div>