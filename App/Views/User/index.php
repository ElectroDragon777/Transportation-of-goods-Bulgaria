<div class="row page-title-header">
    <div class="col-12">
        <div class="page-header">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
    <div class="col-md-12">
        <div class="page-header-toolbar">
            <div class="sort-wrapper" style="width: 100%;">
                <a href="create_gallery.php" class="btn btn-primary toolbar-item">New</a>
                <div class="dropdown ml-lg-auto ml-3 toolbar-item" >
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownexport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Export</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownexport">
                        <a class="dropdown-item" href="#">Export as PDF</a>
                        <a class="dropdown-item" href="#">Export as DOCX</a>
                        <a class="dropdown-item" href="#">Export as CDR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>User Phone</th>
                            <th>User Email</th>
                            <th>
                                <div style="float: right">
                                    Action
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($tpl['users'] as $key => $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['first_name'] . ' ' . $row['second_name'] ?></td>
                                <td><?php echo $row['phone'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td>
                                    <div style="float: right">
                                        <a href="<?php echo INSTALL_URL; ?>index.php?controller=User&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-icons btn-inverse-light">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="#" class="btn btn-icons btn-inverse-light">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>