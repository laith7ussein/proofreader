
jQuery(($) => {

    $('[name="word"]').focus();

    $('.delete-word').on('click', function () {
        const word = $(this).attr('data-word');
        if ( word ) {
            $.ajax({
                url: proofreader_ajax.ajax_url,
                type: 'POST',
                data: {
                    nonce: proofreader_ajax.nonce,
                    action: 'proofreader_delete_word',
                    word,
                },
                success: (response) => {
                    window.location.reload();
                }
            });
        }
    });

}, 'jquery')