(function ($) {
    $(function () {

        $(document).delegate("#imageFile", "change", function (e) {
            e.preventDefault();

            let formData = new FormData();
            let fileInput = $('#imageFile')[0].files[0];
            if (!fileInput) {
                alert("Please select an image to upload.");
                return;
            }

            var $id = $("#input-id").val();

            formData.append('file', fileInput);
            formData.append('id', $id);

            $.ajax({
                url: 'index.php?controller=Gallery&action=upload', // Server endpoint (PHP file)
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    $('#image-container-id').html(response);
                },
                error: function (response) {
                    $('#uploadStatus').html('<p>Error uploading image.</p>');
                }
            });
        }).delegate('.btn-delete-img', 'click', function (e) {
            e.preventDefault();

            var $id = $(this).attr('data-id');
            var $this = $(this);

            $.ajax({
                url: 'index.php?controller=Gallery&action=deleteImage', // Server endpoint (PHP file)
                type: 'POST',
                data: {
                    id: $id
                },
                success: function (res) {
                    $this.parent().parent().remove();
                }
            });
        });

    });
}(jQuery));