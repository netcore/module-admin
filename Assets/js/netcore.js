$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': csrf_token,
        }
    });

    $('body').on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        var btn = $(this);

        swal({
            title: "Are you sure?",
            text: "You will not be able to restore this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete!",
            closeOnConfirm: true
        }, function () {

            $.post(btn.attr('href'), {"_method": "DELETE"}, function (response) {
                if (response.state == 'success') {
                    window.setTimeout(function () {
                        swal("Success", "Data successfully deleted!", "success");
                    }, 500)

                    if (btn.data('id')) {
                        if ($('.object' + btn.data('id')).length) {
                            $('.object' + btn.data('id')).remove();
                        }
                        else {
                            btn.closest('tr').remove();
                        }

                    }
                }

                if (response.state == 'error') {
                    swal("Error", response.message, "error");
                }

            }, 'json');
        });
    });
});
