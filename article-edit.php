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
        <div class="heading-contianer d-flex justify-content-between align-items-center">
            <div class="page-details">
                <h1 class="mt-4 dash-heading">Article</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Article / Edit</li>
                </ol>
            </div>
            <a href="" class="back-btn">Go Back</a>
        </div>

        <div class="user-edit-container">
            <?php
            if (isset($_GET['article_id']) || isset($_GET[''])) :
            ?>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="user-data-view">
                            <div class="card-heading d-flex justify-content-between align-items-center">
                                Edit - Article
                                <div class="tag-container">
                                </div>
                            </div>
                            <div class="user-data-container d-flex justify-content-between flex-wrap">
                                <div class="user-data">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    </div>
<?php
            else :
                $_SESSION['status'] = "Error";
                $_SESSION['message'] = "Some Error Occurred [Article id not defined!]";
                $_SESSION['icon'] = 'error';
                header('Location: articles.php');
            endif;
?>
</main>

<?php
include "includes/script.php";
?>

<script>
    $(document).ready(function() {
        function getQueryParam(parameter) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(parameter);
        }

        $(document).on('change', '#categoryList', function() {
            var categoryName = $(this).find('option:selected').text();
            $('.getCategoryName').val(categoryName);
        });

        // article_id:
        var article_id = getQueryParam('article_id');
        var video_article_id = getQueryParam('video_article_id');

        if (video_article_id == null) {
            $('.back-btn').attr('href', 'articles.php');
            $.ajax({
                type: "POST",
                url: "code.php",
                data: {
                    'get_article_data': true,
                    'article_id': article_id
                },
                success: function(response) {
                    $.each(response, function(index, value) {
                        let html = `
                            <form action="code.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="catLabel" class="form-label">Cover Image</label>
                                    <div class="img-container d-flex justify-content-center align-items-center mb-4">
                                        <img alt="icon" src="${value['cover_image']}" id="edit-icon" style="width: 100%; height: 400px;">
                                    </div>
                                    <input type="file" class="form-control" id="cover_image" class="cover_image" name="cover_image">
                                    <input type="hidden" value="${value['cover_image']}" class="oldImage" name="oldImage">
                                    <input type="hidden"  value="${value['id']}" class="id" name="id">
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <textarea required  placeholder="Title" class="form-control" name="title" id="title">${value['title']}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea required  placeholder="Description" class="form-control" name="description" id="description" cols="60" rows="15">${value['description']}</textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" value="${value['category_name']}" name="category_name" class="getCategoryName">
                                    <label for="category" class="form-label">Select Category</label>
                                    <select class="form-select" class="category_id" id="categoryList" required name="category_id">
                                        <option class="selected-category" value="${value['category_id']}" selected>${value['category_name']}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="catLabel" class="form-label">Language</label>
                                    <select class="form-select" name="language" id="language_option">
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
                                <div class="mb-3">
                                    <label for="accreated" class="form-label">Date Created</label>
                                    <input type="datetime-local" value="${value['date_created']}" class="form-control" id="accreated" name="accreated" disabled>
                                </div>
                                <div class="d-flex align-items-center flex-wrap">
                                    <button type="submit" name="save_article" class="btn btn-primary save-user-btn">Submit</button>
                                </div>
                            </form>`;
                        var trending = `<span class="breaking-icon active"><i class="fa-solid fa-arrow-trend-up"></i></span>`;
                        var breaking = `<span class="breaking-icon mx-2 breaking"><i class="fa-solid fa-bolt"></i></span>`;
                        if (value['is_trending'] == 1) {
                            $('.tag-container').append(trending);
                        }
                        if (value['is_breaking'] == 1) {
                            $('.tag-container').append(breaking);
                        }
                        $('.user-data').append(html);
                        $.ajax({
                            type: "GET",
                            url: "code.php",
                            data: {
                                "getCategories": true
                            },
                            dataType: "JSON",
                            success: function(category_response) {
                                $.each(category_response, function(index, category) {
                                    var categoryId = category.id;
                                    var categoryName = category.category_name;
                                    $('#categoryList').append(`<option class="${categoryName}" value="${categoryId}">${categoryName}</option>`);
                                });
                                for (let i = 0; i < category_response.length; i++) {
                                    if (category_response[i]['category_name'] == value['category_name']) {
                                        $(`.${value['category_name']}`).remove();
                                    }
                                }
                            },
                            error: function(error) {
                                swal({
                                    title: "Failed",
                                    text: "Some Error Occurred While Fetching Categories!",
                                    icon: "error",
                                });
                            }
                        });
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
                                $('#language_option option:first').text('Japanese');
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
                    });
                },
                error: function(error) {
                    swal({
                        title: "Failed",
                        text: "Some Error Occurred!",
                        icon: "error",
                    });
                }
            });
        } else {
            $('.back-btn').attr('href', 'video-articles.php');
            $.ajax({
                type: "POST",
                url: "code.php",
                data: {
                    'get_video_article_data': true,
                    'article_id': video_article_id
                },
                success: function(response) {
                    $.each(response, function(index, value) {
                        let html = `
                            <form action="code.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="catLabel" class="form-label">Video</label>
                                    <div class="img-container d-flex justify-content-center align-items-center mb-4">
                                        <video poster="${value['thumbnail']}" src="${value['video']}" style="height: 400px; width: 100%;" controls loop autoplay></video>
                                    </div>
                                    <input type="file" class="form-control" id="video" class="video" name="video">
                                </div>
                                <div class="mb-3">
                                    <label for="catLabel" class="form-label">Thumbnail</label>
                                    <div class="img-container d-flex justify-content-center align-items-center mb-4">
                                        <img alt="icon" src="${value['thumbnail']}" id="edit-icon" style="width: 100%; height: 400px;">
                                    </div>
                                    <input type="file" class="form-control" id="thumbnail" class="thumbnail" name="thumbnail">
                                    <input type="hidden" value="${value['thumbnail']}" class="oldThumbnail" name="oldThumbnail">
                                    <input type="hidden" value="${value['video']}" class="oldVideo" name="oldVideo">
                                    <input type="hidden"  value="${value['id']}" class="id" name="id">
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <textarea required  placeholder="Title" class="form-control" name="title" id="title">${value['title']}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea required  placeholder="Description" class="form-control" name="description" id="description" cols="60" rows="15">${value['description']}</textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" value="${value['category_name']}" name="category_name" class="getCategoryName">
                                    <label for="category" class="form-label">Select Category</label>
                                    <select class="form-select" class="category_id" id="categoryList" required name="category_id">
                                        <option class="selected-category" value="${value['category_id']}" selected>${value['category_name']}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="catLabel" class="form-label">Language</label>
                                    <select class="form-select" name="language" id="language_option">
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
                                <div class="mb-3">
                                    <label for="accreated" class="form-label">Date Created</label>
                                    <input type="datetime-local" value="${value['date_created']}" class="form-control" id="accreated" name="accreated" disabled>
                                </div>
                                <div class="d-flex align-items-center flex-wrap">
                                    <button type="submit" name="save_video_article" class="btn btn-primary save-user-btn">Submit</button>
                                </div>
                            </form>`;
                        var trending = `<span class="breaking-icon active"><i class="fa-solid fa-arrow-trend-up"></i></span>`;
                        var breaking = `<span class="breaking-icon mx-2 breaking"><i class="fa-solid fa-bolt"></i></span>`;
                        if (value['is_trending'] == 1) {
                            $('.tag-container').append(trending);
                        }
                        if (value['is_breaking'] == 1) {
                            $('.tag-container').append(breaking);
                        }
                        $('.user-data').append(html);
                        $.ajax({
                            type: "GET",
                            url: "code.php",
                            data: {
                                "getCategories": true
                            },
                            dataType: "JSON",
                            success: function(category_response) {
                                $.each(category_response, function(index, category) {
                                    var categoryId = category.id;
                                    var categoryName = category.category_name;
                                    $('#categoryList').append(`<option class="${categoryName}" value="${categoryId}">${categoryName}</option>`);
                                });
                                for (let i = 0; i < category_response.length; i++) {
                                    if (category_response[i]['category_name'] == value['category_name']) {
                                        $(`.${value['category_name']}`).remove();
                                    }
                                }
                            },
                            error: function(error) {
                                swal({
                                    title: "Failed",
                                    text: "Some Error Occurred While Fetching Categories!",
                                    icon: "error",
                                });
                            }
                        });
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
                                $('#language_option option:first').text('Japanese');
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
                    });
                },
                error: function(error) {
                    swal({
                        title: "Failed",
                        text: "Some Error Occurred!",
                        icon: "error",
                    });
                }
            });
        }
    });
</script>

<?php
include "includes/footer.php";
?>