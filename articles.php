<?php
session_start();

require "config/conn.php";
require "functions.php";
include "includes/header.php";
include "includes/topbar.php";
include "includes/sidebar.php";

session_regenerate_id(true);

if (!isset($_SESSION['AdminLoginId'])) {
    header("Location: index.php");
}
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4 dash-heading">Articles</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Neews Articles</li>
        </ol>

        <!-- Bagian untuk menambahkan artikel baru -->
        <div class="row my-4">
            <div class="col-12 add-category-box">
                <h1 class="category-heading">Add Article</h1>
                <div class="categort-add-container d-flex align-items-center">
                    <form method="post" action="code.php" enctype="multipart/form-data" class="d-flex align-items-start flex-wrap">
                        <!-- Input file untuk gambar sampul artikel -->
                        <div class="mb-3 mx-2">
                            <label for="image" class="form-label">Cover Image</label>
                            <input required name="image" type="file" class="form-control" id="image">
                            <input value="TEXT" name="type" type="hidden" class="form-control" id="type">
                        </div>

                        <!-- Input judul artikel -->
                        <div class="mb-3 mx-2">
                            <label for="name" class="form-label">Title</label>
                            <textarea required placeholder="Title" class="form-control" name="title" id="title" cols="40" rows="1"></textarea>
                        </div>

                        <!-- Input deskripsi artikel -->
                        <div class="mb-3 mx-2">
                            <label for="name" class="form-label">Description</label>
                            <textarea required placeholder="Description" class="form-control" name="description" id="description" cols="60" rows="1"></textarea>
                        </div>

                        <!-- Pilihan kategori artikel -->
                        <div class="mb-3 mx-2">
                            <input type="hidden" name="category_name" class="getCategoryName">
                            <label for="category" class="form-label">Select Category</label>
                            <select class="form-select" class="category_id" id="categoryList" required name="category_id">
                                <option value="category" selected>Select Category 1</option>
                            </select>
                        </div>




                        <!-- Pilihan bahasa artikel -->
                        <div class="mb-3 mx-2">
                            <label for="catLabel" class="form-label">Language</label>
                            <select name="language" required class="form-select">
                                <option value="language">Select Language</option>
                                <!-- Pilihan bahasa -->
                                <option class="en" value="en">English</option>
                                <option class="hi" value="hi">Hindi</option>
                                <option class="ar" value="ar">Arabic</option>
                                <option class="fr" value="fr">French</option>
                                <option class="de" value="de">German</option>
                                <option class="ru" value="ru">Russian</option>
                                <option class="id" value="id">Indonesian</option>
                                <option class="ja" value="ja">Japanese</option>
                                <option class="it" value="it">Italian</option>
                                <option class="es" value="es">Spanish</option>
                            </select>
                        </div>

                        <!-- Pilihan mengirim notifikasi -->
                        <div class="mb-3 mx-2">
                            <label for="title" class="form-label">Send Notification</label>
                            <div class="d-flex align-items-center flex-wrap my-2">
                                <div class="form-check">
                                    <input class="form-check-input" name="notification" type="radio" value="Yes" id="notification">
                                    <label class="form-check-label" for="radio">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="notification" type="radio" value="No" id="notification">
                                    <label class="form-check-label" for="radio">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol untuk menambahkan artikel -->
                        <button class="add-category-btn mx-2 mt-4" name="add_article" type="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar artikel yang ada -->
        <div class="row mb-4">
            <div class="user-table">
                <div class="useractioncontainer d-flex align-items-center justify-content-between">
                    <a herf="javascript:void(0)" onclick="delete_all()" class="delete-user-btn disabled-btn">Delete
                        Selected Articles</a>
                    <div class="dropdown">
                        <a class="download-data-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Download</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="export.php?export_articles_csv=true">CSV</a></li>
                            <li><a class="dropdown-item" href="export.php?export_articles_excel=true">Excel</a></li>
                        </ul>
                    </div>
                </div>
                <form method="post" id="frm">
                    <table id="articles_table" class="table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="hide-sort"><input type="checkbox" onclick="select_all()" id="delete"></th>
                                <!-- Kolom untuk ID artikel (Kolom ini harus ada di database) -->
                                <th class=" d-none">
                                    <?= $row['id']; ?>
                                </th>
                                <th>SR No.</th>
                                <th>Title</th>
                                <th>Cover Image</th>
                                <th>Category</th>
                                <th>Language</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk mengkonfirmasi penghapusan artikel -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h1 class="modal-title" style="font-size: 28px; font-weight: 600; margin: 0 auto; text-align: center;" id="deleteModalLabel">Delete Category</h1>
                </div>
                <div class="modal-body  text-center" style="padding: 0.5rem;">
                    <p style="font-size: 20px;">Are you sure to delete this Article ?</p>
                    <input type="hidden" name="del_id" class="delete-article-id" id="del_id">
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="more-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="delButton delete-article-btn" name="delButton">Yes! Delete</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "includes/script.php";
?>

<script>
    //    Initializing the table

    var table = $('#articles_table').DataTable({

        // Fetching the data

        ajax: {

            type: 'GET',

            url: 'APIs/getArticlesAdmin.php',

            dataType: 'json',

            dataSrc: '',

            async: true

        },

        columns: [

            {

                data: null,

                render: function(data, type, row, meta) {

                    return `<input type="checkbox" name="checkbox2[]" value="${data['id']}" id="${data['id']}">`;

                }

            },

            {

                data: null,

                render: function(data, type, row, meta) {

                    return meta.row + 1;

                }

            },

            {
                data: null,

                render: function(data, type, row, meta) {



                    if (data['is_trending'] == 1) {

                        var trending = `

                <span class="breaking-icon active">

                <i class="fa-solid fa-arrow-trend-up"></i>

                </span>`;

                    } else {

                        var trending = ``;

                    }





                    if (data['is_breaking'] == 1) {

                        var breaking = `

              <span class="breaking-icon mx-2 breaking">

              <i class="fa-solid fa-bolt"></i>

              </span>

              `;

                    } else {

                        var breaking = ``;

                    }



                    return `

            <span>${data['title'].slice(0, 175)}....</span>

            ${trending}

            ${breaking}

            `;

                }







            },

            {
                data: null,

                render: function(data, type, row, meta) {



                    return `<img style="width: 100px; height: 50px;" src="${data['cover_image']}" alt="image">`;

                }

            },

            {
                data: "category_name"
            },

            {
                data: 'language'
            },

            {
                data: 'date_created'
            },

            {

                data: null,

                render: function(data, type, row, meta) {



                    if (data['is_trending'] == 1) {

                        var t_style = `background: #e7fce9;`;

                    } else {

                        var t_style = "";

                    }

                    if (data['is_breaking'] == 1) {

                        var b_style = `background: #ffe69c;`;

                    } else {

                        var b_style = "";

                    }







                    return `

            <div class="btn-group" role="group">

            <button type="button" class="more-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">

            <i class="fa-solid fa-ellipsis-vertical"></i>

            </button>

            <ul class="dropdown-menu">

            <li><a class="dropdown-item"  href="article-edit.php?article_id=${data['id']}">Edit</a></li>

            <li><button class="delete-article  dropdown-item" name="delete-article" value="${data['id']}">Delete</button></li>

            <li><button class="trend-article  dropdown-item" style="${t_style}" name="trend-article" id="${data['id']}" value="${data['is_trending']}">Trending</button></li>

            <li><button class="breaking-article  dropdown-item" style="${b_style}" name="breaking-article" id="${data['id']}" value="${data['is_breaking']}">Breaking</button></li>

            <li><button class="notify  dropdown-item" name="send-article" data-title="${data['title']}" data-message="${data['description']}" data-image="${data['cover_image']}"  data-id="${data['id']}">Notify</button></li>



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









    $(document).on('change', '#categoryList', function() {

        var categoryName = $(this).find('option:selected').text();

        $('.getCategoryName').val(categoryName);

    });











    // Delete Button Action

    $(document).on('click', '.delete-article', function(e) {

        e.preventDefault();



        var id = $(this).val();

        // console.log(user_id);

        $('.delete-article-id').val(id);

        $('.delete-article-btn').val(id);

        $('#deleteModal').modal('show');

    });



    $(document).on('click', '.notify', function(e) {

        e.preventDefault();



        var id = $(this).data('id');

        var message = $(this).data('message');

        var title = $(this).data('title');

        var image = $(this).data('image');



        // Send data to the server using AJAX

        $.ajax({

            url: 'code.php',

            method: 'POST',

            data: {

                sendNotification: true,

                type: "VIDEO",

                id: id,

                message: message,

                title: title,

                image: image

            },

            dataType: "JSON",

            success: function(response) {

                // console.log(response); // Response from the server

                swal({

                    title: "Notification Sent",

                    text: response.Message,

                    icon: "success",

                });

            },

            error: function(xhr, status, error) {

                // console.log(error); // Log any error

                swal({

                    title: "Failed",

                    text: "Some Error Occured!",

                    icon: "error",

                });

            }

        });

    });





    // Trending Button Action

    $(document).on('click', '.trend-article', function(e) {

        e.preventDefault();



        var is_trending = $(this).val();

        var id = $(this).attr('id');



        if (is_trending == 0) {

            $.ajax({

                type: "POST",

                url: "code.php",

                data: {

                    'make_trending': true,

                    'id': id

                },

                dataType: "JSON",

                success: function(response) {

                    // console.log(response);



                    swal({

                        title: "Article is Trending",

                        text: response.Message,

                        icon: "success",

                    });

                    table.ajax.reload();



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

            $.ajax({

                type: "POST",

                url: "code.php",

                data: {

                    'remove_from_trending': true,

                    'id': id

                },

                dataType: "JSON",

                success: function(response) {

                    // console.log(response);



                    swal({

                        title: "Removed From Trending",

                        text: response.Message,

                        icon: "success",

                    });

                    table.ajax.reload();



                },

                error: function(error) {



                    swal({

                        title: "Failed",

                        text: "Some Error Occured!",

                        icon: "error",

                    });

                }



            });

        }

    });





    // Breaking Button Action

    $(document).on('click', '.breaking-article', function(e) {

        e.preventDefault();



        var is_breaking = $(this).val();

        var id = $(this).attr('id');



        if (is_breaking == 0) {

            $.ajax({

                type: "POST",

                url: "code.php",

                data: {

                    'make_breaking_news': true,

                    'id': id

                },

                dataType: "JSON",

                success: function(response) {

                    // console.log(response);



                    swal({

                        title: "Done",

                        text: response.Message,

                        icon: "success",

                    });

                    table.ajax.reload();



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

            $.ajax({

                type: "POST",

                url: "code.php",

                data: {

                    'remove_from_breaking_news': true,

                    'id': id

                },

                dataType: "JSON",

                success: function(response) {

                    // console.log(response);



                    swal({

                        title: "Done",

                        text: response.Message,

                        icon: "success",

                    });

                    table.ajax.reload();



                },

                error: function(error) {



                    swal({

                        title: "Failed",

                        text: "Some Error Occured!",

                        icon: "error",

                    });

                }



            });

        }

    });









    // Delete the user

    $(document).on('click', '.delete-article-btn', function(e) {

        e.preventDefault();



        var id = $(this).val();



        $.ajax({

            type: "POST",

            url: "code.php",

            data: {

                "delete-article": true,

                "id": id

            },

            dataType: "JSON",

            success: function(response) {

                // console.log(response);

                $('#deleteModal').modal('hide');



                swal({

                    title: "Article Deleted",

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







    // Delete Selected Articles

    function delete_all() {

        if (jQuery('input[type=checkbox]').is(':checked')) {

            swal({

                    title: "Are you sure?",

                    text: "Are You Sure To Delete Articles !",

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

                                    title: "Articles Deleted",

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







    $(document).ready(function() {

        $.ajax({

            type: "GET",

            url: "code.php",

            data: {

                "getCategories": true

            },

            dataType: "JSON",

            success: function(response) {

                // console.log(response);

                $.each(response, function(index, category) {

                    var categoryId = category.id;

                    var categoryName = category.category_name;





                    $('#categoryList').append(`<option value="${categoryId}">${categoryName}</option>`);

                });

            },

            error: function(error) {

                swal({

                    title: "Failed",

                    text: "Some Error Occured While Fetching Categories!",

                    icon: "error",

                });

            }

        });

    });
</script>

<?php
include "includes/footer.php";
?>