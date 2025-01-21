<?php ?>
<div class="row">
    <?php
    foreach ($tpl['images'] as $key => $row) {
        if (file_exists('web/upload/' . $row['image_name'])) {
            ?>
            <div class="col-md-3">
                <div class="image-container">
                    <img src="web/upload/<?php echo $row['image_name']; ?>" alt="..." class="img-thumbnail">
                    <a href="javascript:" data-id="<?php echo $row['id']; ?>" class="btn btn-icons btn-rounded btn-warning btn-delete-img">
                        <i class="mdi mdi-trash-can"></i>
                    </a>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>