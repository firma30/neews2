<?php

session_start();



require("config/conn.php");

require("functions.php");

include("includes/header.php");

include("includes/topbar.php");

include("includes/sidebar.php");





session_regenerate_id(true);



if (!isset($_SESSION['AdminLoginId'])) {

    header("Location: index.php");
}

?>







<main>

    <div class="container-fluid px-4">



        <h1 class="mt-4 dash-heading">Users</h1>

        <ol class="breadcrumb mb-4">

            <li class="breadcrumb-item active">Users</li>

        </ol>







        <div class="row mb-4">



            <div class="user-table">



                <div class="useractioncontainer d-flex align-items-center justify-content-between">

                    <a herf="javascript:void(0)" onclick="delete_all()" class="delete-user-btn disabled-btn">Delete Selected

                        Users</a>



                    <div class="dropdown">

                        <a class="download-data-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            Download

                        </a>



                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="export.php?export_user_csv=true">CSV</a></li>

                            <li><a class="dropdown-item" href="export.php?export_user_excel=true" href="#">Excel</a></li>

                            <li><a class="dropdown-item" href="export.php?export_user_pdf=true">PDF</a></li>

                        </ul>

                    </div>

                </div>

                <form method="post" id="frm">

                    <table id="users_table" class="table table-striped" style="width:100%">

                        <thead>

                            <tr>

                                <th class="hide-sort"><input type="checkbox" onclick="select_all()" id="delete"></th>

                                <th>#</th>

                                <th>Name</th>

                                <th>Email</th>

                                <th>Sign Up Via</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </form>

            </div>

        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h1 class="modal-title" style="font-size: 28px; font-weight: 600; margin: 0 auto; text-align: center;" id="deleteModalLabel">Delete User</h1>
                </div>
                <div class="modal-body  text-center" style="padding: 0.5rem;">
                    <p style="font-size: 20px;">Are you sure to delete this user ?</p>
                    <input type="hidden" name="del_id" class="delete-user-id" id="del_id">
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="more-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="delButton delete-user-id" name="delButton">Yes! Delete</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php

include("includes/script.php");
?>


<script>
    <?php
    if (isset($_SESSION['status'])) {
    ?>
        swal("<?= $_SESSION['message']; ?>", {
            icon: "<?= $_SESSION['icon'] ?>",
        });

    <?php
        unset($_SESSION['status']);
        unset($_SESSION['message']);
        unset($_SESSION['icon']);
    }
    ?>
</script>



<script>
    //    Initializing the table
    var table = $('#users_table').DataTable({
        // Fetching the data
        ajax: {
            type: 'GET',
            url: 'APIs/getUsersAdmin.php',
            dataType: 'json',
            dataSrc: '',
            async: true
        },
        columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return `<input type="checkbox" name="checkbox[]" value="${data['id']}" id="${data['id']}">`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'user_name'
            },

            {
                data: 'email'
            },

            {

                data: 'sign_up_via',
                render: function(data, type, row, meta) {
                    if (data == 'EMAIL') {
                        return '<img src="uploads/images/email.svg" alt="Email">';
                    } else {
                        return '<img src="uploads/images/google.svg" alt="Email">';
                    }
                }
            },

            {
                data: 'signed_in',
                render: function(data, type, row, meta) {
                    if (data == 0) {
                        return '<span class="banned">Inactive</span>';
                    } else {
                        return '<span class="active">Active</span>';
                    }
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return `              
                        <div class="btn-group" role="group">
                        <button type="button" class="more-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item"  href="user-edit.php?user_id=${data['id']}">Edit</a></li>
                        <li> 
                            <button class="deleteUser dropdown-item" name="deleteUser" value="${data['id']}">Delete</button>
                        </li>
                        </ul>
                        </div>
                        `;
                }
            }
        ],

        rowCallback: function(row, data, index) {
            $(row).attr('id', 'box' + data['id']);;
        }
    });


    // Delete Button Action
    $(document).on('click', '.deleteUser', function(e) {
        e.preventDefault();
        var id = $(this).val();

        // console.log(user_id);
        $('.delete-user-id').val(id);
        $('#deleteModal').modal('show');
    });


    // Delete the user
    $(document).on('click', '.delete-user-id', function(e) {
        e.preventDefault();
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                "delete-user": true,
                "id": id
            },
            dataType: "JSON",
            success: function(response) {

                // console.log(response);
                $('#deleteModal').modal('hide');
                swal({
                    title: "Users Deleted",
                    text: response.Message,
                    icon: "success",
                });
                table.ajax.reload();
            },
            error: function(error) {
                $('#deleteModal').modal('hide');
                swal({
                    title: "Failed",
                    text: "Some Error Occured!",
                    icon: "error",
                });
            }
        });
        // table.ajax.reload();
    });

    // Enable or Disable Button
    $(document).on('change', 'input[type="checkbox"]', function() {
        var checkboxes = $('input[type="checkbox"]');
        if ($(this).is(':checked')) {

            // Checkbox is checked
            $('.delete-user-btn').removeClass('disabled-btn');
        } else {

            // Checkbox is not checked
            var allChecked = true;

            checkboxes.each(function() {
                if ($(this).is(':checked')) {
                    allChecked = false;
                    return false; // Exit the loop early
                }
            });



            if (allChecked) {
                //    console.log("ALL CHECKBOCES ARE NOT CHECKED");
                $('.delete-user-btn').addClass('disabled-btn');
            } else {
                // console.log("ALL CHECKBOCES ARE CHECKED");
            }
        }
    });



    // Select All Code
    function select_all() {
        if (jQuery('#delete').prop("checked")) {
            jQuery('input[type=checkbox]').each(function(param) {
                jQuery('#' + this.id).prop('checked', true);
                // console.log(this.id);
            });
        } else {
            jQuery('input[type=checkbox]').each(function(param) {
                jQuery('#' + this.id).prop('checked', false);
                // console.log(this.id);
            });
        }
    }


    // Delete Selected Users
    function delete_all() {
        if (jQuery('input[type=checkbox]').is(':checked')) {
            swal({
                    title: "Are you sure?",
                    text: "Are You Sure To Delete Users !",
                    icon: "warning",
                    buttons: ["Cancel", "Yes ! Delete"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        jQuery.ajax({
                            type: "post",
                            url: "code.php",
                            data: jQuery("#frm").serialize(),
                            dataType: 'JSON',
                            success: function(response) {
                                // console.log(response);
                                table.ajax.reload();
                                swal({
                                    title: "Users Deleted",
                                    text: response.Message,
                                    icon: "success",
                                });
                            },
                            error: function(error) {
                                swal({
                                    title: "Failed",
                                    text: "Some Error Occured!",
                                    icon: "error"
                                });
                            }
                        });
                    } else {
                        // DO NOTHING
                    }
                });
        } else {
            // DO NOTHING
        }
    }
</script>

<script>
</script>

<?php
include("includes/footer.php");
?>