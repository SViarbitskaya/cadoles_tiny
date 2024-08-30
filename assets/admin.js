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
    $('#datatable-users').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/users/data", // The URL that returns JSON data for DataTables
        "columns": [
                { "data": "email" },
                {
                    "data": "groups",
                    "render": function(data) {
                        return data.map(function(group) {
                            return '<span class="badge bg-secondary">' + group.name + '</span>';
                        }).join(' ');
                    }
                },
                {
                    "data": "roles",
                    "render": function(data) {
                        return data.map(function(role) {
                            return '<span class="badge bg-primary">' + role + '</span>';
                        }).join(' ');
                    }
                },
                {
                    data: 'urls',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        // Return action links as a plain string
                        return `
                            <div class="item-actions">
                                <a href="${data[0]}" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-eye" aria-hidden="true"></i> show
                                </a>
                                <a href="${data[1]}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit" aria-hidden="true"></i> edit
                                </a>
                            </div>`;
                    }
                }
            ]
    });

    let tableGroups = new DataTable('#datatable-groups', {
        serverSide: true,
        ajax: {
            url: '/admin/groups/data',
            type: 'GET',
        },
        responsive: true
    });
});

