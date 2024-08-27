import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/js/dist/alert';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/tab';
import 'bootstrap/js/dist/modal';
import 'jquery'

// // Handling the modal confirmation message.
// $(document).on('submit', 'form[data-confirmation]', function (event) {
//     var $form = $(this),
//         $confirm = $('#confirmationModal');

//         event.preventDefault();

//         console.log(event);

//     if ($confirm.data('result') !== 'yes') {
//         //cancel submit event
//         event.preventDefault();

//         $confirm
//             .off('click', '#btnYes')
//             .on('click', '#btnYes', function () {
//                 $confirm.data('result', 'yes');
//                 $form.find('input[type="submit"]').attr('disabled', 'disabled');
//                 $form.trigger('submit');
//             })
//             .modal('show');
//     }
// });

