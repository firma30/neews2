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
        <h1 class="mt-4 dash-heading">Video Articles</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Neews Video Articles</li>
        </ol>
        <div class="row my-4">
            <div class="col-12 add-category-box">
                <h1 class="category-heading">Add Video Article</h1>
                <div class="categort-add-container d-flex align-items-center">
                    <form method="post" id="addVideoArticleForm" enctype="multipart/form-data" class="d-flex align-items-start flex-wrap">
                        <div class="mb-3 mx-2">
                            <label for="video" class="form-label">Video</label>
                            <input required name="video" type="file" class="form-control" id="video">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="image" class="form-label">Thumbnail</label>
                            <input required name="image" type="file" class="form-control" id="image">
                            <input value="VIDEO" name="type" type="hidden" class="form-control" id="type">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="name" class="form-label">Title</label>
                            <textarea required placeholder="Title" class="form-control" name="title" id="title" cols="40" rows="1"></textarea>
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="name" class="form-label">Description</label>
                            <textarea required placeholder="Description" class="form-control" name="description" id="description" cols="60" rows="1"></textarea>
                        </div>
                        <div class="mb-3 mx-2">
                            <input type="hidden" name="category_name" class="getCategoryName">
                            <label for="category" class="form-label">Select Category</label>
                            <select class="form-select" class="category_id" id="categoryList" required name="category_id">
                                <option value="" selected>Select Category</option>
                            </select>
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="catLabel" class="form-label">Language</label>
                            <select name="language" required class="form-select">
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
                        <button class="add-category-btn mx-2 mt-4" id="video_article_add_btn" name="add_video_article" type="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <div id="toastContainer" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 10px; right: 10px; z-index: 9999;"></div>
        <div class="row mb-4">
            <div class="user-table">
                <div class="useractioncontainer d-flex align-items-center justify-content-between">
                    <a herf="javascript:void(0)" onclick="delete_all()" class="delete-user-btn disabled-btn">Delete
                        Selected
                        Articles</a>
                    <div class="dropdown">
                        <a class="download-data-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Download
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="export.php?export_video_articles_csv=true">CSV</a></li>
                            <li><a class="dropdown-item" href="export.php?export_video_articles_excel=true">Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form method="post" id="frm">
                    <table id="video-article-table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="hide-sort"><input type="checkbox" onclick="select_all()" id="delete"></th>
                                <th class=" d-none">
                                    <?= $row['id']; ?>
                                    </td>
                                <th>SR No.</th>
                                <th>Title</th>
                                <th>Thumbnail</th>
                                <th>Category</th>
                                <th>Language</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>

        </div>
        <div class="toast-container">
            <div id="progressToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000">
                <div class="toast-body">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <span id="progressStatus"></span>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h1 class="modal-title" style="font-size: 28px; font-weight: 600; margin: 0 auto; text-align: center;" id="deleteModalLabel">Delete Article</h1>
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
    var table = $('#video-article-table').DataTable({
        // Fetching the data
        ajax: {
            type: 'GET',
            url: 'APIs/getVideoArticlesAdmin.php',
            dataType: 'json',
            dataSrc: '',
            async: true
        },
        columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return `<input type="checkbox" name="checkbox3[]" value="${data['id']}" id="${data['id']}">`;
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
                    return `<img style="width: 100px; height: 50px;" src="${data['thumbnail']}" alt="image">`;
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

                    return `
                                <div class="btn-group" role="group">
                                <button type="button" class="more-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item"  href="video_edit.php?video_article_id=${data['id']}">Edit</a></li>
                                <li><button class="delete-article  dropdown-item" name="delete-article" value="${data['id']}">Delete</button></li>
                                <li><button class="notify  dropdown-item" name="send-article" data-title="${data['title']}" data-message="${data['description']}" 
                                    data-image="${data['thumbnail']}"  data-id="${data['id']}">Notify</button></li>
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


    // Delete the Article

    $(document).on('click', '.delete-article-btn', function(e) {

        e.preventDefault();



        var id = $(this).val();



        $.ajax({

            type: "POST",

            url: "code.php",

            data: {

                "delete-video-article": true,

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









    $("#addVideoArticleForm").submit(function(e) {

        e.preventDefault();



        var formData = new FormData(this);



        formData.append('uploadVideoArticle', true);

        // for (const value of formData.values()) {

        //     console.log(value);

        // }



        var file = $('#video')[0].files[0];





        // Create toast element for the file

        var toastId = 'toast_' + new Date().getTime();





        var toast = `

      <div id="${toastId}" class="toast my-2" data-bs-autohide="false" role="alert" aria-live="assertive" aria-atomic="true">

      <div class="toast-header">

      <i class="icon-fa fa-solid fa-spinner fa-spin me-2" style="color: #15122d;"></i>

      <strong class="me-auto upload-status-text">Uploading...</strong>

      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>

      </div>

      <div class="toast-body">



      <div class="progress">

      <div class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>

      </div>

      </div>

      </div>



      `;



        // Append toast to container

        $('#toastContainer').append(toast);



        const toasta = new bootstrap.Toast(toastId);



        $(`#${toastId}`).toast('show');



        $('#addVideoArticleForm')[0].reset();





        $.ajax({
            url: 'code.php',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                // Upload progress event
                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = Math.round((event.loaded / event.total) * 100);
                        // Update progress bar
                        $(`#${toastId} .progress-bar`).css('width', percentComplete + '%');
                        $(`#${toastId} .progress-bar`).text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                $(`#${toastId} .upload-status-text`).text('Uploaded, ' + response.Message);
                $(`.icon-fa`).addClass('fa-circle-check');
                $(`.icon-fa`).addClass('fa-shake');
                $(`.icon-fa`).removeClass('fa-spinner');
                $(`.icon-fa`).removeClass('fa-spin');
                swal({
                    title: "Article Added",
                    text: response.Message,
                    icon: "success",
                });
                table.ajax.reload();
            },
            error: function(error) {
                $(`#${toastId} .upload-status-text`).text('Uploading Failed');
                $(`.icon-fa`).removeClass('fa-spinner');
                $(`.icon-fa`).removeClass('fa-spin');
                $(`.icon-fa`).addClass('fa-xmark');
                $('.progress-bar').css('background-color', '#FF533B');
                $('.progress-bar').css('color', '#FF533B');
                swal({
                    title: "Failed",
                    text: "Some Error Occured!",
                    icon: "error",
                });
            }
        });





    });
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