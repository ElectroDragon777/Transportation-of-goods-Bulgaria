<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Gallery</h4>
        <form class="forms-sample" method="post" action="<?php INSTALL_URL ?>index.php?controller=Gallery&action=edit">
            <input type="hidden" name="send_frm" value="1" />
            <input type="hidden" name="id" value="<?php echo $tpl['gallery']['id']; ?>" />
            <div class="form-group row">
                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Gallery Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="gallery_name" placeholder="Name" name="gallery_name" value="<?php echo $tpl['gallery']['gallery_name']; ?>" />
                </div>
            </div>
            <button type="submit" class="btn btn-success mr-2">Edit</button>
            <button class="btn btn-light">Cancel</button>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div id="image-container-id">
            <?php
            include 'getImages.php';
            ?>
        </div>
        <?php include 'uploadImage.php' ?>
    </div>
</div>