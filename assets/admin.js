import $ from 'jquery';
import DataTable from 'datatables.net-dt';


$(document).ready( function () {

    // Handling the modal confirmation message.
    $(document).on('submit', 'form[data-confirmation]', function (event) {
        var $form = $(this),
            $confirm = $('#confirmationModal');

        if ($confirm.data('result') !== 'yes') {
            //cancel submit event
            event.preventDefault();

            $confirm
                .off('click', '#btnYes')
                .on('click', '#btnYes', function () {
                    $confirm.data('result', 'yes');
                    $form.find('input[type="submit"]').attr('disabled', 'disabled');
                    $form.trigger('submit');
                })
                .modal('show');
        }
    });

    // Initialization of Datatables
    let table = new DataTable('#list-of-users', {
        // config options...
    });
} );

