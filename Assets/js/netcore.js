$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': csrf_token,
        }
    });

    /**
     * @TOOD - all messages should be translatable
     */

    // Init switchery
    $('.changeable-state').each(function(i, switcher) {
        new Switchery(switcher);
    });

    $(document).on('change', '.changeable-state', function () {
        var id = $(this).data('id');
        var model = $(this).data('model');
        var whereColumn = $(this).data('where');
        var column = $(this).data('column');

        $.post('/admin/switch-active', {
            id: id,
            model: model,
            where: whereColumn,
            column: column,
            _token: csrf_token
        }, function (response) {
            if (response.state == 'success') {
                swal("Success", "Status has been changed", "success");
            }
            if (response.state == 'error') {
                swal("Error!", response.message, "error");
            }
        });
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
            confirmButtonText: "Delete!"
        }).then(function(){
            $.post(btn.attr('href'), {"_method": "DELETE"}).done(function (response) {
                if (response.state == 'success') {
                    window.setTimeout(function () {
                        swal("Success", "Data successfully deleted!", "success");
                    }, 300)

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
            }).fail(function () {
                swal("Error", "Server error!", "error");
            });
        });
    });
});
