(function ($) {
    $(function () {
        $(document).on("click", "#calculate-price-btn-id", function (e) {
            e.preventDefault();
            var frm = $("#booking-frm-id");

            $.ajax({
                url: 'index.php?controller=Order&action=calculatePrice',
                type: 'POST',
                dataType: "json",
                data: frm.serialize(),
                success: function (data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        $("#productPrice").val(data.product_price || '0.00');
                        $("#totalPrice").val(data.total || '0.00');

                        // Trigger change event on payment method radio buttons
                        $('input[name="payment_method"]').trigger('change');

                        // Update total price based on payment method
                    }
                }
            });
        });
    });
}(jQuery));