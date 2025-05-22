<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <h4 class="card-title">Create New Courier</h4>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form class="forms-sample" method="POST"
                        action="<?php echo INSTALL_URL; ?>?controller=Courier&action=create">
                        <input type="hidden" name="send" value="1" />

                        <h5 class="with-line">Their Name (Username) and Email:</h5>
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="name" class="form-label">Name*</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="email" class="form-label">Email*</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <h5 class="with-line">Their Password:</h5>
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="password" class="form-label">Password*</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control pe-5" id="password" name="password"
                                        required>
                                    <i class="password-toggle-icon fa fa-eye" data-target="password"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="repeatPassword" class="form-label">Repeat Password*</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control pe-5" id="repeatPassword"
                                        name="repeat_password" required>
                                    <i class="password-toggle-icon fa fa-eye" data-target="repeatPassword"></i>
                                </div>
                            </div>
                        </div>

                        <h5 class="with-line">Their Phone number:</h5>
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number*</label>
                                <input type="tel" pattern="^\d{10}$" class="form-control" id="phoneNumber"
                                    name="phone_number" required>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mt-3">
                                <strong>Please note:</strong><br>
                                <span class="note-indicator"></span>
                                * All fields are required.<br>
                                <span class="note-indicator"></span>
                                The phone number is required to be in the format of 10 digits (e.g., 1234567890).
                                Also, it is required for special cases.<br>

                            </p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-white me-0">Create Courier</button>
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

<style>
    .note-indicator {
        display: inline-block;
        /* Allows us to control width and height */
        width: 20px;
        /* Adjust the length of the line */
        height: 2px;
        /* Adjust the thickness of the line */
        background-color: #007bff;
        /* Choose your desired color (Bootstrap primary color used as an example) */
        vertical-align: middle;
        /* Align the line with the text */
        margin-right: 8px;
        /* Add some spacing between the line and the text */
    }
</style>