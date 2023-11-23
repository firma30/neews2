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
        <h1 class="mt-4 dash-heading">Category</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Neews Category</li>
        </ol>
        <div class="row my-4">
            <div class="col-12 add-category-box">
                <h1 class="category-heading">Add Category</h1>
                <div class="categort-add-container d-flex align-items-center">
                    <form method="post" action="code.php" enctype="multipart/form-data" class="d-flex align-items-center flex-wrap">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input required name="image" type="file" class="form-control" id="image">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="icon" class="form-label">Icon</label>
                            <input required name="icon" type="file" class="form-control" id="icon">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="name" class="form-label">Name</label>
                            <input required type="text" placeholder="Name" class="form-control" name="name" id="name">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="catLabel" class="form-label">Language</label>
                            <select name="language" required class="form-select" aria-label="Default select example">
                                <option value="language">Select Language</option>
                                <option class="en" value="en">English</option>
                                <option class="hi" value="hi">Hindi</option>
                                <option class="ar" value="ar">Arabic</option>
                                <option class="fr" value="fr">French</option>
                                <option class="de" value="de">German</option>
                                <option class="ru" value="ru">Russian</option>
                                <option class="id" value="id">Indonesian</option>
                                <option class="ja" value="ja">Japnese</option>
                                <option class="it" value="it">Italian</option>
                                <option class="es" value="es">Spanish</option>
                            </select>
                        </div>
                        <button class="add-category-btn mx-2 mt-2" name="add_category" type="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="user-table">
                <div class="useractioncontainer d-flex align-items-center justify-content-between">
                    <a herf="javascript:void(0)" onclick="delete_all()" class="delete-user-btn disabled-btn">Delete
                        Selected
                        Category</a>
                    <div class="dropdown">
                        <a class="download-data-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Download
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="export.php?export_category_csv=true">CSV</a></li>
                            <li><a class="dropdown-item" href="export.php?export_category_excel=true">Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form method="post" id="frm">
                    <table id="categories-table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="hide-sort"><input type="checkbox" onclick="select_all()" id="delete"></th>
                                <th class=" d-none">
                                    <?= $row['id']; ?>
                                    </td>
                                <th>SR No.</th>
                                <th>Name</th>
                                <th>Icon</th>
                                <th>Total Articles</th>
                                <th>Language</th>
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
    <div class="modal fade" id="editCategory" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCategoryLabel">Edit Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="catLabel" class="form-label">Icon</label>
                            <div class="img-container d-flex justify-content-center align-items-center mb-4">
                                <img alt="icon" id="edit-icon" style="width: 100px; height: 100px;">
                            </div>
                            <input type="file" class="form-control" id="category_icon" class="category_icon" name="category_icon">
                            <input type="hidden" class="oldIcon" name="oldIcon">
                            <input type="hidden" class="id" name="id">
                        </div>
                        <div class="mb-3">
                            <label for="catLabel" class="form-label">Image</label>
                            <div class="img-container d-flex justify-content-center align-items-center mb-4">
                                <img alt="image" id="edit-image" style="width: 100px; height: 100px;">
                            </div>
                            <input type="file" class="form-control" id="category_image" class="category_image" name="category_image">
                            <input type="hidden" class="oldImage" name="oldImage">
                        </div>
                        <div class="mb-3">
                            <label for="catLabel" class="form-label">Name</label>
                            <input required type="text" class="form-control" id="category_name" name="category_name" class="category_name">
                        </div>
                        <div class="mb-3">
                            <label for="catLabel" class="form-label">Language</label>
                            <select class="form-select" name="language" id="language_option">
                                <option class="selected-option" selected></option>
                                <option class="en" value="en">English</option>
                                <option class="hi" value="hi">Hindi</option>
                                <option class="ar" value="ar">Arabic</option>
                                <option class="fr" value="fr">French</option>
                                <option class="de" value="de">German</option>
                                <option class="ru" value="ru">Russian</option>
                                <option class="id" value="id">Indonesian</option>
                                <option class="ja" value="ja">Japnese</option>
                                <option class="it" value="it">Italian</option>
                                <option class="es" value="es">Spanish</option>
                            </select>
                        </div>
                        <button class="add-category-btn edit-categpory-btn" name="edit_category" type="submit">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="mx-4">Total Articles <span class='totalArticles'></span> </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h1 class="modal-title" style="font-size: 28px; font-weight: 600; margin: 0 auto; text-align: center;" id="deleteModalLabel">Delete Category</h1>
                </div>
                <div class="modal-body  text-center" style="padding: 0.5rem;">
                    <p style="font-size: 20px;">Are you sure to delete this Category ?</p>
                    <input type="hidden" name="del_id" class="delete-category-id" id="del_id">
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="more-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="delButton delete-category-btn" name="delButton">Yes! Delete</button>
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
    var table = $('#categories-table').DataTable({
        // Fetching the data
        ajax: {
            type: 'GET',
            url: 'APIs/getCategoriesAdmin.php',
            dataType: 'json',
            dataSrc: '',
            async: true
        },
        columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return `<input type="checkbox" name="checkbox1[]" value="${data['id']}" id="${data['id']}">`;
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }


            },

            {
                data: 'category_name'
            },

            {

                data: 'icon',

                render: function(data, type, row, meta) {

                    return `<img style="width: 50px; height: 50px;" src="${data}" alt="icon">`;

                }

            },

            {
                data: 'total_articles'
            },

            {
                data: 'language'
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

                        <li><button class="edit-btn dropdown-item"  name="delete-edit" value="${data['id']}">Edit</button></li>

                        <li><button class="delete-category dropdown-item" name="delete-category" value="${data['id']}">Delete</button></li>

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

    $(document).on('click', '.delete-category', function(e) {
        e.preventDefault();
        var id = $(this).val();
        $('.delete-category-id').val(id);
        $('.delete-category-btn').val(id);
        $('#deleteModal').modal('show');
    });



    // Delete the category
    $(document).on('click', '.delete-category-btn', function(e) {
        e.preventDefault();
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                "delete-category": true,
                "id": id
            },
            dataType: "JSON",
            success: function(response) {

                // console.log(response);
                $('#deleteModal').modal('hide');
                swal({
                    title: "Category Deleted",
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



    // Delete Selected Categories
    function delete_all() {
        if (jQuery('input[type=checkbox]').is(':checked')) {
            swal({
                    title: "Are you sure?",
                    text: "Are You Sure To Delete Categories !",
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
                                    title: "Categories Deleted",
                                    text: response.Message,
                                    icon: "success",
                                });
                            },
                            error: function(error) {
                                swal({
                                    title: "Failed",
                                    text: "Some Error Occured!",
                                    icon: "error",
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




    // Edit Category
    $(document).on('click', '.edit-btn', function(e) {
        e.preventDefault();
        var cat_id = $(this).val();

        // console.log(cat_id);
        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                'checking_edit_btn': true,
                'cat_id': cat_id,
            },
            success: function(response) {
                // console.log(response);
                $.each(response, function(key, value) {
                    // console.log(value['name']);
                    $("#category_name").val(value['category_name']);
                    $(".selected-option").val(value['language']);
                    $(".oldIcon").val(value['icon']);
                    $(".oldImage").val(value['image']);
                    $(".id").val(value['id']);
                    $("#edit-icon").attr('src', value['icon']);
                    $("#edit-image").attr('src', value['image']);
                    $(".totalArticles").text(value['total_articles']);

                    switch (value['language']) {
                        case 'en':
                            $('#language_option option:first').text('English');
                            $('.en').remove();
                            break;
                        case 'hi':
                            $('#language_option option:first').text('Hindi');
                            $('.hi').remove();
                            break;
                        case 'ar':
                            $('#language_option option:first').text('Arabic');
                            $('.ar').remove();
                            break;
                        case 'fr':
                            $('#language_option option:first').text('French');
                            $('.fr').remove();
                            break;
                        case 'es':
                            $('#language_option option:first').text('Spanish');
                            $('.es').remove();
                            break;

                        case 'de':
                            $('#language_option option:first').text('German');
                            $('.de').remove();
                            break;

                        case 'ru':
                            $('#language_option option:first').text('Russian');
                            $('.ru').remove();
                            break;

                        case 'id':
                            $('#language_option option:first').text('Indonesian');
                            $('.ru').remove();
                            break;

                        case 'ja':
                            $('#language_option option:first').text('Japnese');
                            $('.ja').remove();
                            break;

                        case 'it':
                            $('#language_option option:first').text('Italian');
                            $('.it').remove();
                            break;

                        default:
                            $('#language_option option:first').text('English');
                            break;
                    }

                    $('#editCategory').modal('show');
                });
            },
            error: function(error) {
                swal({
                    title: "Failed",
                    text: "Some Error Occured!",
                    icon: "error",
                });
            }
        });
    });
</script>




<?php
include("includes/footer.php");
?>