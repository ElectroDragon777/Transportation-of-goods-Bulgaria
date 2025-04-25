<table class="table select-table" id="pallet-table-id">
    <thead>
        <tr>
            <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])) { ?>
                <th>
                    <div class="form-check form-check-flat mt-0">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="select-all-pallets"> </label>
                    </div>
                </th>
            <?php } ?>
            <th>Packet ID</th>
            <th>Packet Name</th>
            <th>Category</th> <!-- Added Type column -->
            <th>Packet Description</th>
            <th>Count</th> <!-- Renamed from Stock for clarity -->
            <!-- <th>Price</th> -->
            <th>Size X (cm)</th>
            <th>Size Y (cm)</th>
            <th>Size Z (cm)</th>
            <th>Weight (kg)</th>
            <th style="text-align: right;"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tpl['pallets'] as $pallet) { ?>
            <tr>
                <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])) { ?>
                    <td>
                        <div class="form-check form-check-flat mt-0">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input pallet-checkbox"
                                    data-id="<?php echo $pallet['id']; ?>"> </label>
                        </div>
                    </td>
                <?php } ?>
                <td><?php echo htmlspecialchars($pallet['id']); ?></td>
                <td><?php echo htmlspecialchars($pallet['name']); ?></td>
                <td><?php echo ucfirst(htmlspecialchars($pallet['category'])); ?></td> <!-- Display Type -->
                <td><?php echo htmlspecialchars($pallet['description']); ?></td>
                <td><?php echo htmlspecialchars($pallet['stock']); ?></td>
                <!-- <td><?/*php echo Utility::getDisplayableAmount(htmlspecialchars($pallet['price'])); */ ?></td> -->
                <td><?php echo htmlspecialchars($pallet['size_x_cm']); ?></td>
                <td><?php echo htmlspecialchars($pallet['size_y_cm']); ?></td>
                <td><?php echo htmlspecialchars($pallet['size_z_cm']); ?></td>
                <td><?php echo htmlspecialchars($pallet['weight_kg'] ?: 'N/A'); ?></td>
                <!-- Show N/A if null (for Documents) -->
                <td style="text-align: right;">
                    <!-- Buttons remain the same -->
                    <a class="btn btn-info btn-circle mdc-ripple-upgraded"
                        href="<?php echo INSTALL_URL; ?>?controller=Pallet&action=edit&id=<?php echo $pallet['id'] ?>"> <i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-danger btn-circle delete-pallet" href="#" data-id="<?php echo $pallet['id']; ?>"> <i
                            class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>