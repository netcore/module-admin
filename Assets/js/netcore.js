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

                $.growl({
                    title : 'Success!',
                    message : 'Status changed'
                });
            }
            if (response.state == 'error') {
                swal("Error!", response.message, "error");
            }
        });
    });

    /**
     * There also is .confirm-action down below, which is more sophisticated
     */
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
        }).catch(swal.noop);
    });

    /**
     * This is improved version of .confirm-delete
     * Probably .confirm-delete should be deprecated and deleted in favor of this
     */
    $('body').on('click', '.confirm-action', function (e) {
        e.preventDefault();
        var btn = $(this);

        swal({
            title: $(btn).data('title') || 'Confirmation',
            text: $(btn).data('text') || 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: $(btn).data('confirm-button-text') || 'Confirm',
            cancelButtonText: $(btn).data('cancel-button-text') || 'Cancel'
        }).then(function(){

            $.ajax({
                url: btn.data('href'),
                type: btn.data('method'),
                dataType: 'json',

                success: function (response) {

                    // Reload page on success
                    var refreshPageOnSuccess = $(btn).data('refresh-page-on-success');
                    if(refreshPageOnSuccess !== undefined && refreshPageOnSuccess !== false) {
                        window.location.reload();
                    }

                    window.setTimeout(function () {
                        swal({
                            title: btn.data('success-title'),
                            text: btn.data('success-text'),
                            type: "success"
                        });
                    }, 100);

                    var fadeOutSelector = btn.data('fade-out-selector');
                    if ( fadeOutSelector && $(fadeOutSelector).length ) {
                        $(fadeOutSelector).fadeOut();
                    }

                    var refreshDataTable = btn.data('refresh-datatable');
                    if(refreshDataTable) {
                        $(refreshDataTable).DataTable().ajax.reload();
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    swal("Error",
                        "Sorry, there was an error. Please try again later. If the problem persists, please contact with technical support.",
                        "error"
                    );
                }
            });

        }).catch(swal.noop);
    });
});
