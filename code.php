<?php
require './config/conn.php';
require 'functions.php';
session_start();
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';
header('Content-Type: application/json');
// Users Section Script
// -- Edit the User
if (isset($_POST['save_user'])) {
    $id = $_POST['id'];
    $user_name = filter($_POST['user_name']);
    $email = filter($_POST['email']);
    $language = filter($_POST['language']);
    $sql = "UPDATE `users` SET `user_name`='$user_name',`email`='$email',`language`='$language' WHERE `id` = '$id'";
    $query_run = mysqli_query($conn, $sql);
    if ($query_run) {
        $_SESSION['status'] = "Updated";
        $_SESSION['message'] = "User Updated Successfully";
        $_SESSION['icon'] = 'success';
        header('Location: users.php');
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Some Error Occured";
        $_SESSION['icon'] = 'error';
        header('Location: users.php');
    }
}

// -- Delete Selected Users --
if (isset($_POST['checkbox']['0'])) {
    foreach ($_POST['checkbox'] as $row) {
        $id = mysqli_real_escape_string($conn, $row);
        $query = "DELETE FROM `users` WHERE `id`='$id'";
        $query_run = mysqli_query($conn, $query);
    }
    if ($query_run) {
        http_response_code(200);
        echo json_encode(array("Message" => "Users Deleted Successfully", "id" => $id));
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Delete Single User --
if (isset($_POST['delete-user'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM `users` WHERE `id`='$id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "User Deleted Successfully", "id" => $id));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// Category Section Script

// -- Add Category --
if (isset($_POST['add_category'])) {
    $name = filter($_POST['name']);
    $language = filter($_POST['language']);
    $img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $img_tmp_name = $_FILES['image']['tmp_name'];
    $img_error = $_FILES['image']['error'];
    $icon_name = $_FILES['icon']['name'];
    $icon_size = $_FILES['icon']['size'];
    $icon_tmp_name = $_FILES['icon']['tmp_name'];
    $icon_error = $_FILES['icon']['error'];
    if ($img_error === 0 && $icon_error === 0) {
        if ($img_size > 12000000 && $icon_size > 12000000) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Image is Large';
            header("Location: category.php");
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $icon_ex = pathinfo($icon_name, PATHINFO_EXTENSION);
            $icon_ex_lc = strtolower($icon_ex);
            $allowed_extensions = array('png', 'jpg', 'jpeg');
            if (in_array($img_ex_lc, $allowed_extensions) && in_array($icon_ex_lc, $allowed_extensions)) {
                $new_image_name = uniqid("category-image", true) . '.' . $img_ex_lc;
                $new_icon_name = uniqid("category-icon", true) . '.' . $icon_ex_lc;
                $img_upload_path = "uploads/categories/" . $new_image_name;
                $icon_upload_path = "uploads/categories/" . $new_icon_name;
                move_uploaded_file($img_tmp_name, $img_upload_path);
                move_uploaded_file($icon_tmp_name, $icon_upload_path);
                $image = "uploads/categories/" . $new_image_name;
                $icon = "uploads/categories/" . $new_icon_name;
            } else {
                $_SESSION['status'] = "Error";
                $_SESSION['message'] = "Category Adding failed [USE PNG,  JPG, JPEG Files]";
                $_SESSION['icon'] = 'error';
                header('Location: category.php');
            }
        }
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Category Adding failed ['Some Error Occured']";
        $_SESSION['icon'] = 'error';
        header('Location: category.php');
    }

    //    Insert The Data
    $sql = "INSERT INTO `categories`(`category_name`, `icon`, `image`,`language`, `created_at`) VALUES ('$name', '$icon','$image','$language', current_timestamp())";
    $query_run = mysqli_query($conn, $sql);
    if ($query_run) {
        $_SESSION['status'] = "Created";
        $_SESSION['message'] = "Category Created Successfully";
        $_SESSION['icon'] = 'success';
        header('Location: category.php');
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Category Creating Failed ['Query Error']";
        $_SESSION['icon'] = 'error';
        header('Location: category.php');
    }
}

// -- Send Category Data --
if (isset($_POST['checking_edit_btn'])) {
    $id = $_POST['cat_id'];
    $result_array = [];
    $query = "SELECT * FROM `categories` WHERE `id`='$id'";
    $query_run = mysqli_query($conn, $query);
    if (mysqli_num_rows($query_run) > 0) {
        foreach ($query_run as $row) {
            http_response_code(200);
            array_push($result_array, $row);
            header('Content-Type: application/json');
            echo json_encode($result_array);
        }
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Edit Category --

if (isset($_POST['edit_category'])) {
    $id = filter($_POST['id']);
    $oldIcon = filter($_POST['oldIcon']);
    $oldImage = filter($_POST['oldImage']);
    $category_name = filter($_POST['category_name']);
    $language = filter($_POST['language']);
    $img_name = $_FILES['category_image']['name'];
    $img_size = $_FILES['category_image']['size'];
    $img_tmp_name = $_FILES['category_image']['tmp_name'];
    $img_error = $_FILES['category_image']['error'];
    $icon_name = $_FILES['category_icon']['name'];
    $icon_size = $_FILES['category_icon']['size'];
    $icon_tmp_name = $_FILES['category_icon']['tmp_name'];
    $icon_error = $_FILES['category_icon']['error'];
    if ($_FILES['category_image']['name'] == "") {
        $image = $oldImage;
    } else {
        if ($img_error === 0) {
            if ($img_size > 12000000) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Image is Large';
                header("Location: category.php");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_extensions = array('png', 'jpg', 'jpeg');
                if (in_array($img_ex_lc, $allowed_extensions)) {
                    $new_image_name = uniqid("category-image", true) . '.' . $img_ex_lc;
                    $img_upload_path = "uploads/categories/" . $new_image_name;
                    move_uploaded_file($img_tmp_name, $img_upload_path);
                    $image = "uploads/categories/" . $new_image_name;
                } else {
                    $_SESSION['status'] = "Error";
                    $_SESSION['message'] = "Category Adding failed [USE PNG,  JPG, JPEG Files]";
                    $_SESSION['icon'] = 'error';
                    header('Location: category.php');
                }
            }
        }
    }

    if ($_FILES['category_icon']['name'] == "") {
        $icon = $oldIcon;
    } else {
        if ($icon_error === 0) {
            if ($icon_size > 12000000) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Image is Large';
                header("Location: category.php");
            } else {
                $icon_ex = pathinfo($icon_name, PATHINFO_EXTENSION);
                $icon_ex_lc = strtolower($icon_ex);
                $allowed_extensions = array('png', 'jpg', 'jpeg');
                if (in_array($icon_ex_lc, $allowed_extensions)) {
                    $new_icon_name = uniqid("category-icon", true) . '.' . $icon_ex_lc;
                    $icon_upload_path = "uploads/categories/" . $new_icon_name;
                    move_uploaded_file($icon_tmp_name, $icon_upload_path);
                    $icon = "uploads/categories/" . $new_icon_name;
                } else {
                    $_SESSION['status'] = "Error";
                    $_SESSION['message'] = "Category Adding failed [USE PNG,  JPG, JPEG Files]";
                    $_SESSION['icon'] = 'error';
                    header('Location: category.php');
                }
            }
        }
    }

    $sql = "UPDATE `categories` SET `id`='$id',`category_name`='$category_name',`icon`='$icon',`image`='$image', `language`='$language' WHERE `id`='$id'";
    $query_run = mysqli_query($conn, $sql);
    if ($query_run) {
        $_SESSION['status'] = "Edited";
        $_SESSION['message'] = "Category Edited Successfully";
        $_SESSION['icon'] = 'success';
        header('Location: category.php');
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Error ocurred!";
        $_SESSION['icon'] = 'error';
        header('Location: category.php');
    }
}

// -- Delete Selected Categories --
if (isset($_POST['checkbox1']['0'])) {
    foreach ($_POST['checkbox1'] as $row) {
        $id = mysqli_real_escape_string($conn, $row);
        $query = "DELETE FROM `categories` WHERE `id`='$id'";
        $query_run = mysqli_query($conn, $query);
    }
    if ($query_run) {
        http_response_code(200);
        echo json_encode(array("Message" => "Categories Deleted Successfully", "id" => $id));
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Delete Single Category --
if (isset($_POST['delete-category'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM `categories` WHERE `id`='$id'";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        http_response_code(200);
        echo json_encode(array("Message" => "Category Deleted Successfully", "id" => $id));
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// Article Section Script
// -- Get Categories on AJAX call

if (isset($_GET['getCategories'])) {
    $sql = "SELECT `id`, `category_name` FROM `categories`";
    $result_array = [];
    $query_run = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query_run) >= 0) {
        http_response_code(200);
        while ($row = $query_run->fetch_assoc()) {
            $result_array[] = $row;
        }
        echo json_encode($result_array);
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Add Article --
if (isset($_POST['add_article'])) {
    $title = filter($_POST['title']);
    $category_name = filter($_POST['category_name']);
    $description = filter($_POST['description']);
    $category_id = filter($_POST['category_id']);
    $language = filter($_POST['language']);
    $notification = filter($_POST['notification']);
    $type = filter($_POST['type']);
    $img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

    if ($error === 0) {
        if ($img_size > 12000000) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Image is Large';
            header("Location: articles.php");
            exit;
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_extensions = array('png', 'jpg', 'jpeg');

            if (in_array($img_ex_lc, $allowed_extensions)) {
                $new_image_name = uniqid("article", true) . '.' . $img_ex_lc;
                $img_upload_path = "uploads/articles/" . $new_image_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                $image = "uploads/articles/" . $new_image_name;
            } else {
                $_SESSION['status'] = "Error";
                $_SESSION['message'] = "Article Adding failed [USE PNG,  JPG, JPEG Files]";
                $_SESSION['icon'] = 'error';
                header('Location: articles.php');
                exit;
            }
        }
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Article Adding failed ['Some Error Occurred']";
        $_SESSION['icon'] = 'error';
        header('Location: articles.php');
        exit;
    }

    $sql = "INSERT INTO `articles`(`category_id`, `category_name`, `title`, `cover_image`, `description`, `views`, `likes`, `saves`, `is_trending`, `is_breaking`, `language`, `reports`, `date_created`) 
    VALUES ('$category_id','$category_name','$title','$image','$description','0','0','0','0','0','$language','0',current_timestamp())";

    $query_run = mysqli_query($conn, $sql);

    $query = "UPDATE `categories` SET `total_articles` = `total_articles`+1 WHERE `categories`.`id` = '$category_id'";
    $sql_run = mysqli_query($conn, $query);

    $appId = getSettingValue('app_settings', 'FCM App Id');
    $apiKey = getSettingValue('app_settings', 'FCM Api Key');

    if ($notification == "Yes") {
        $lastInsertIdQuery = "SELECT LAST_INSERT_ID() AS last_id";
        $result = mysqli_query($conn, $lastInsertIdQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $inserted_id = $row['last_id'];

            $domain = $_SERVER['HTTP_HOST'];
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

            $imagePath = '/' . $image;
            $imageUrl = $protocol . $domain . $imagePath;

            $sql = "INSERT INTO `notifications`(`title`, `description`, `image`, `type`, `data`, `date`) 
                    VALUES ('$title', '$description', '$image', 'TEXT', '$inserted_id', current_timestamp())";

            $query_run = mysqli_query($conn, $sql);
            $result = sendFCMNotification($title, $description, $imageUrl);

            if ($query_run && $sql_run && $result == "Notification sent successfully!") {
                $_SESSION['status'] = "Added";
                $_SESSION['message'] = "Article Added Successfully";
                $_SESSION['icon'] = 'success';
                header('Location: articles.php');
                exit;
            } else {
                $_SESSION['status'] = "Error";
                $_SESSION['message'] = "Article Adding Failed ['Query Error']";
                $_SESSION['icon'] = 'error';
                header('Location: articles.php');
                exit;
            }
        }
    } else {
        if ($query_run && $sql_run) {
            $_SESSION['status'] = "Added";
            $_SESSION['message'] = "Article Added Successfully";
            $_SESSION['icon'] = 'success';
            header('Location: articles.php');
            exit;
        } else {
            $_SESSION['status'] = "Error";
            $_SESSION['message'] = "Article Adding Failed ['Query Error']";
            $_SESSION['icon'] = 'error';
            header('Location: articles.php');
            exit;
        }
    }
}


// -- Delete Single Article --

if (isset($_POST['delete-article'])) {

    $id = $_POST['id'];

    $query = "DELETE FROM `articles` WHERE `id`='$id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "Article Deleted Successfully", "id" => $id));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Delete Selected Articles --

if (isset($_POST['checkbox2']['0'])) {

    foreach ($_POST['checkbox2'] as $row) {

        $id = mysqli_real_escape_string($conn, $row);

        $query = "DELETE FROM `articles` WHERE `id`='$id'";

        $query_run = mysqli_query($conn, $query);
    }

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "Articles Deleted Successfully", "id" => $id));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Send Article Data --

if (isset($_POST['get_article_data'])) {
    $articleId = $_POST['article_id'];
    $result_array = [];
    $query = "SELECT * FROM `articles` WHERE `id`='$articleId'";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0) {
        foreach ($query_run as $row) {
            http_response_code(200);
            array_push($result_array, $row);
            header('Content-Type: application/json');
            echo json_encode($result_array);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Edit Article --

if (isset($_POST['save_article'])) {
    $id = filter($_POST['id']);
    $oldImage = filter($_POST['oldImage']);
    $title = filter($_POST['title']);
    $description = filter($_POST['description']);
    $category_name = filter($_POST['category_name']);
    $category_id = filter($_POST['category_id']);
    $language = filter($_POST['language']);
    $img_name = $_FILES['cover_image']['name'];
    $img_size = $_FILES['cover_image']['size'];
    $tmp_name = $_FILES['cover_image']['tmp_name'];
    $error = $_FILES['cover_image']['error'];
    if ($_FILES['cover_image']['name'] == "") {
        $image = $oldImage;
    } else {

        if ($error === 0) {

            if ($img_size > 12000000) {

                $_SESSION['status'] = 'error';

                $_SESSION['message'] = 'Image is Large';

                header("Location: articles.php");
            } else {

                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

                $img_ex_lc = strtolower($img_ex);

                $allowed_extensions = array('png', 'jpg', 'jpeg');

                if (in_array($img_ex_lc, $allowed_extensions)) {

                    $new_image_name = uniqid("article", true) . '.' . $img_ex_lc;

                    $img_upload_path = "uploads/articles/" . $new_image_name;

                    move_uploaded_file($tmp_name, $img_upload_path);

                    $image = "uploads/articles/" . $new_image_name;
                } else {

                    $_SESSION['status'] = "Error";

                    $_SESSION['message'] = "Article Adding failed [USE PNG,  JPG, JPEG Files]";

                    $_SESSION['icon'] = 'error';

                    header('Location: articles.php');
                }
            }
        }
    }

    $sql = "UPDATE `articles` SET `category_id`='$category_id',`category_name`='$category_name',`title`='$title',`cover_image`='$image',`description`='$description',`language`='$language' WHERE `id`='$id'";
    $query_run = mysqli_query($conn, $sql);
    if ($query_run) {
        $_SESSION['status'] = "Edited";
        $_SESSION['message'] = "Article Edited Successfully";
        $_SESSION['icon'] = 'success';
        header('Location: articles.php');
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Error ocurred!";
        $_SESSION['icon'] = 'error';
        header('Location: articles.php');
    }
}

// -- Make Article Trending --

if (isset($_POST['make_trending'])) {
    $id = filter($_POST['id']);
    $sql = "UPDATE `articles` SET `is_trending` = '1' WHERE `articles`.`id` = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        http_response_code(200);
        echo json_encode(array("Message" => "The Article is now trending on the app"));
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Remove Article From Trending --

if (isset($_POST['remove_from_trending'])) {

    $id = filter($_POST['id']);

    $sql = "UPDATE `articles` SET `is_trending` = '0' WHERE `articles`.`id` = '$id'";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        http_response_code(200);

        echo json_encode(array("Message" => "The Article is not trending anymore"));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Make Article Breaking News --

if (isset($_POST['make_breaking_news'])) {

    $id = filter($_POST['id']);

    $sql = "UPDATE `articles` SET `is_breaking` = '1' WHERE `articles`.`id` = '$id'";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        http_response_code(200);

        echo json_encode(array("Message" => "The Article is now a breaking news"));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Remove Article From Breaking News --

if (isset($_POST['remove_from_breaking_news'])) {

    $id = filter($_POST['id']);

    $sql = "UPDATE `articles` SET `is_breaking` = '0' WHERE `articles`.`id` = '$id'";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        http_response_code(200);

        echo json_encode(array("Message" => "The Article is not in breaking news anymore"));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// Video Article Script

// -- Add Video Article

if (isset($_POST['uploadVideoArticle'])) {
    $title = filter($_POST['title']);
    $description = filter($_POST['description']);
    $category_id = filter($_POST['category_id']);
    $category_name = filter($_POST['category_name']);
    $language = filter($_POST['language']);
    $notification = filter($_POST['notification']);
    $type = filter($_POST['type']);
    $video_name = $_FILES['video']['name'];
    $video_size = $_FILES['video']['size'];
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_error = $_FILES['video']['error'];
    $img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $img_tmp_name = $_FILES['image']['tmp_name'];
    $img_error = $_FILES['image']['error'];
    if ($img_error === 0 && $video_error === 0) {
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $video_ex = pathinfo($video_name, PATHINFO_EXTENSION);
        $video_ex_lc = strtolower($video_ex);
        $allowed_img_extensions = array('png', 'jpg', 'jpeg');
        $allowed_video_extensions = array('mp4');
        if (in_array($img_ex_lc, $allowed_img_extensions) && in_array($video_ex_lc, $allowed_video_extensions)) {
            // Image
            $new_image_name = uniqid("thumbnail", true) . '.' . $img_ex_lc;
            $img_upload_path = "uploads/thumbnails/" . $new_image_name;
            move_uploaded_file($img_tmp_name, $img_upload_path);
            $thumbnail = "uploads/thumbnails/" . $new_image_name;
            // Video
            $new_video_name = uniqid("video-article", true) . '.' . $video_ex_lc;
            $video_upload_path = "uploads/videos/" . $new_video_name;
            move_uploaded_file($video_tmp_name, $video_upload_path);
            $video = "uploads/videos/" . $new_video_name;
        } else {
            http_response_code(400);
            echo json_encode(array("Message" => "Article Adding failed [USE PNG,  JPG, JPEG Files For Thumbnail And MP4 For Video]"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Article adding failed some error occured!"));
    }
    $query = "INSERT INTO `video_articles`(`category_id`, `category_name`, `title`, `description`, `thumbnail`, `video`, `language`, `date_created`) 
    VALUES ('$category_id','$category_name','$title','$description','$thumbnail','$video', '$language' ,current_timestamp())";
    $query_run = mysqli_query($conn, $query);

    $sql = "UPDATE `categories` SET `total_articles` = `total_articles`+1 WHERE `categories`.`id` = '$category_id'";
    $sql_run = mysqli_query($conn, $sql);

    if ($notification == "Yes") {
        $domain = $_SERVER['HTTP_HOST'];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $lastInsertIdQuery = "SELECT LAST_INSERT_ID() AS last_id";
        $result = mysqli_query($conn, $lastInsertIdQuery);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $inserted_id = $row['last_id'];
        } else {
        }
        // Modify the image path according to your specific scenario

        $imagePath = $thumbnail;
        $imageUrl = $protocol . $domain . $imagePath;
        $appId = getSettingValue('app_settings', 'FCM App Id');
        $apiKey = getSettingValue('app_settings', 'FCM Api Key');
        $sql1 = "INSERT INTO `notifications`(`title`, `description`, `image`, `type`, `data`, `date`) 
        VALUES ('$title','$description','$thumbnail','VIDEO','$inserted_id', current_timestamp())";
        $sql_run1 = mysqli_query($conn, $sql1);
        $result = sendFCMNotification($title, $description, $imageUrl,);
        // echo $result;
        if ($query_run && $sql_run && $sql_run1) {
            http_response_code(200);
            echo json_encode(array("Message" => "Article Added Successfully"));
        } else {
            http_response_code(400);
            echo json_encode(array("Message" => "Somee Error Occured"));
        }
    } else {
        if ($query_run && $sql_run) {
            http_response_code(200);
            echo json_encode(array("Message" => "Article Added Successfully"));
        } else {
            http_response_code(400);
            echo json_encode(array("Message" => "Some Error Occured"));
        }
    }
}

// -- Delete Single Video Article --

if (isset($_POST['delete-video-article'])) {

    $id = $_POST['id'];

    $query = "DELETE FROM `video_articles` WHERE `id`='$id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "Article Deleted Successfully", "id" => $id));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Delete Selected Video Articles --

if (isset($_POST['checkbox3']['0'])) {

    foreach ($_POST['checkbox3'] as $row) {

        $id = mysqli_real_escape_string($conn, $row);

        $query = "DELETE FROM `video_articles` WHERE `id`='$id'";

        $query_run = mysqli_query($conn, $query);
    }

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "Articles Deleted Successfully", "id" => $id));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Send Video Article Data --

if (isset($_POST['get_video_article_data'])) {

    $id = $_POST['article_id'];

    $result_array = [];

    $query = "SELECT * FROM `video_articles` WHERE `id`='$id'";

    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0) {

        foreach ($query_run as $row) {

            http_response_code(200);

            array_push($result_array, $row);

            header('Content-Type: application/json');

            echo json_encode($result_array);
        }
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Edit Video Article --

if (isset($_POST['save_video_article'])) {

    $id = filter($_POST['id']);

    $oldThumbnail = filter($_POST['oldThumbnail']);

    $oldVideo = filter($_POST['oldVideo']);

    $title = filter($_POST['title']);

    $description = filter($_POST['description']);

    $category_name = filter($_POST['category_name']);

    $category_id = filter($_POST['category_id']);

    $language = filter($_POST['language']);

    $img_name = $_FILES['thumbnail']['name'];

    $img_size = $_FILES['thumbnail']['size'];

    $img_tmp_name = $_FILES['thumbnail']['tmp_name'];

    $img_error = $_FILES['thumbnail']['error'];

    $video_name = $_FILES['video']['name'];

    $video_size = $_FILES['video']['size'];

    $video_tmp_name = $_FILES['video']['tmp_name'];

    $video_error = $_FILES['video']['error'];

    if ($_FILES['thumbnail']['name'] == "") {

        $thumbnail = $oldThumbnail;
    } else {

        if ($img_error === 0) {

            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

            $img_ex_lc = strtolower($img_ex);

            $allowed_img_extensions = array('png', 'jpg', 'jpeg');

            if (in_array($img_ex_lc, $allowed_img_extensions)) {

                // Image

                $new_image_name = uniqid("thumbnail", true) . '.' . $img_ex_lc;

                $img_upload_path = "uploads/thumbnails/" . $new_image_name;

                move_uploaded_file($img_tmp_name, $img_upload_path);

                $thumbnail = "uploads/thumbnails/" . $new_image_name;
            } else {

                $_SESSION['status'] = "Error";

                $_SESSION['message'] = "Article Adding failed [USE PNG,  JPG, JPEG Files]";

                $_SESSION['icon'] = 'error';

                header('Location: video-article.php');
            }
        } else {

            $_SESSION['status'] = "Error";

            $_SESSION['message'] = "Some Error Occured";

            $_SESSION['icon'] = 'error';

            header('Location: video-article.php');
        }
    }

    if ($_FILES['video']['name'] == "") {

        $video = $oldVideo;
    } else {

        if ($video_error === 0) {

            $video_ex = pathinfo($video_name, PATHINFO_EXTENSION);

            $video_ex_lc = strtolower($video_ex);

            $allowed_video_extensions = array('mp4');

            if (in_array($video_ex_lc, $allowed_video_extensions)) {

                // Video

                $new_video_name = uniqid("video-article", true) . '.' . $video_ex_lc;

                $video_upload_path = "uploads/videos/" . $new_video_name;

                move_uploaded_file($video_tmp_name, $video_upload_path);

                $video = "uploads/videos/" . $new_video_name;
            } else {

                $_SESSION['status'] = "Error";

                $_SESSION['message'] = "Article Adding failed mp4 Files]";

                $_SESSION['icon'] = 'error';

                header('Location: video-article.php');
            }
        } else {

            $_SESSION['status'] = "Error";

            $_SESSION['message'] = "Some Error Occured";

            $_SESSION['icon'] = 'error';

            header('Location: video-article.php');
        }
    }

    $sql = "UPDATE `video_articles` SET `category_id`='$category_id',`category_name`='$category_name',`title`='$title',`description`='$description',`thumbnail`='$thumbnail',`video`='$video',`language`='$language' WHERE `id` = '$id'";

    $query_run = mysqli_query($conn, $sql);

    if ($query_run) {

        $_SESSION['status'] = "Edited";

        $_SESSION['message'] = "Article Edited Successfully";

        $_SESSION['icon'] = 'success';

        header('Location: video-articles.php');
    } else {

        $_SESSION['status'] = "Error";

        $_SESSION['message'] = "Error ocurred!";

        $_SESSION['icon'] = 'error';

        header('Location: video-articles.php');
    }
}

// App Info Script

// -- Send App Info Data --

if (isset($_POST['getAppInfo'])) {

    $result_array = [];

    $query = "SELECT * FROM `app_information`";

    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0) {

        http_response_code(200);

        foreach ($query_run as $row) {

            array_push($result_array, $row);

            header('Content-Type: application/json');
        }

        echo json_encode($result_array);
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Update App Info --

if (isset($_POST['save_appinfo'])) {

    $privacy_policy = filter($_POST['privacy_policy']);

    $p_language = filter($_POST['p_language']);

    $about_us = filter($_POST['about_us']);

    $a_language = filter($_POST['a_language']);

    $query1 = "UPDATE `app_information` SET `information`='$privacy_policy',`language`='$p_language' WHERE `type`='privacy_policy'";

    $query2 = "UPDATE `app_information` SET  `information`='$about_us',`language`='$a_language' WHERE `type`='about_us'";

    $query_run1 = mysqli_query($conn, $query1);

    $query_run2 = mysqli_query($conn, $query2);

    if ($query_run1 && $query_run2) {

        $_SESSION['status'] = "Updated";

        $_SESSION['message'] = "App info Updated Successfully";

        $_SESSION['icon'] = 'success';

        header('Location: appinfo.php');
    } else {

        $_SESSION['status'] = "Error";

        $_SESSION['message'] = "Error ocurred!";

        $_SESSION['icon'] = 'error';

        //   header('Location: appinfo.php');

    }
}

// Setting Script

// -- Update Notification Setting --

if (isset($_POST['save_notification_settings'])) {

    $FCMAppId = filter($_POST['FCMAppId']);

    $FCMApiKey = filter($_POST['FCMApiKey']);

    $sql1 = "UPDATE `app_settings` SET `value` = '$FCMAppId' WHERE `app_settings`.`name` = 'FCM App Id'";

    $sql2 = "UPDATE `app_settings` SET `value` = '$FCMApiKey' WHERE `app_settings`.`name` = 'FCM Api Key'";

    $query_run1 = mysqli_query($conn, $sql1);

    $query_run2 = mysqli_query($conn, $sql2);

    if ($query_run1 && $query_run2) {

        $_SESSION['status'] = "Updated";

        $_SESSION['message'] = "Setting Updated Successfully";

        $_SESSION['icon'] = 'success';

        header('Location: settings.php');
    } else {

        $_SESSION['status'] = "Error";

        $_SESSION['icon'] = 'error';

        $_SESSION['message'] = "Error ocurred!";

        header('Location: settings.php');
    }
}

// -- Send App Settings

if (isset($_POST['getAppSetting'])) {

    $result_array = [];

    $query = "SELECT * FROM `app_settings`";

    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0) {

        http_response_code(200);

        foreach ($query_run as $row) {

            array_push($result_array, $row);
        }

        echo json_encode($result_array);
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Ad Enable and Disable Check --

if (isset($_POST['check_onOff'])) {

    $result_array = [];

    $sql = "SELECT * FROM `app_settings` WHERE `name` = 'ad_enabled' LIMIT 1";

    $query_run = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query_run) > 0) {

        http_response_code(200);

        foreach ($query_run as $row) {

            array_push($result_array, $row);
        }

        echo json_encode($result_array);
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Enable Ad --

if (isset($_POST['enable_ad'])) {

    $sql = "UPDATE `app_settings` SET `value` = '1' WHERE `name` = 'ad_enabled'";

    $query_run = mysqli_query($conn, $sql);

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "Ad Enabled"));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Disable Ad --

if (isset($_POST['disable_ad'])) {

    $sql = "UPDATE `app_settings` SET `value` = '0' WHERE `name` = 'ad_enabled'";

    $query_run = mysqli_query($conn, $sql);

    if ($query_run) {

        http_response_code(200);

        echo json_encode(array("Message" => "Ad Disabled"));
    } else {

        http_response_code(400);

        echo json_encode(array("Message" => "Some Error Occured"));
    }
}

// -- Update Ad Setting --

if (isset($_POST['save_ad_settings'])) {

    $andBanner = filter($_POST['andBanner']);

    $iosBanner = filter($_POST['iosBanner']);

    $sql1 = "UPDATE `ad_info` SET `value` = '$andBanner' WHERE `ad_info`.`id` = 1";

    $sql2 = "UPDATE `ad_info` SET `value` = '$iosBanner' WHERE `ad_info`.`id` = 2";

    $query_run1 = mysqli_query($conn, $sql1);

    $query_run2 = mysqli_query($conn, $sql2);

    if ($query_run1 && $query_run2) {

        $_SESSION['status'] = "Updated";

        $_SESSION['message'] = "Setting Updated Successfully";

        $_SESSION['icon'] = 'success';

        header('Location: settings.php');
    } else {

        $_SESSION['status'] = "Error";

        $_SESSION['icon'] = 'error';

        $_SESSION['message'] = "Error ocurred!";

        header('Location: settings.php');
    }
}

// -- Update Admin Settings --

if (isset($_POST['save_admin_panel_settings'])) {

    $appName = filter($_POST['adminPanelSetting']);

    $oldIcon = filter($_POST['oldDashboardImage']);

    $oldLoginIcon = filter($_POST['oldLoginImage']);

    $img_name = $_FILES['newDashboardIcon']['name'];

    $img_size = $_FILES['newDashboardIcon']['size'];

    $tmp_name = $_FILES['newDashboardIcon']['tmp_name'];

    $error = $_FILES['newDashboardIcon']['error'];

    $login_img_name = $_FILES['newLoginIconIcon']['name'];

    $login_img_size = $_FILES['newLoginIconIcon']['size'];

    $login_tmp_name = $_FILES['newLoginIconIcon']['tmp_name'];

    $login_error = $_FILES['newLoginIconIcon']['error'];

    if ($_FILES['newDashboardIcon']['name'] == "") {

        $icon = $oldIcon;
    } else {

        if ($error === 0) {

            if ($img_size > 12000000) {

                $_SESSION['status'] = "Error";

                $_SESSION['message'] = "Error ocurred!";

                $_SESSION['icon'] = 'error';

                header('Location: profile.php');
            } else {

                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

                $img_ex_lc = strtolower($img_ex);

                $allowed_extensions = array('png', 'jpg', 'jpeg');

                if (in_array($img_ex_lc, $allowed_extensions)) {

                    $new_image_name = uniqid("admin_panel", true) . '.' . $img_ex_lc;

                    $img_upload_path = "uploads/admin_panel/" . $new_image_name;

                    move_uploaded_file($tmp_name, $img_upload_path);

                    $path = "uploads/admin_panel/" . $new_image_name;

                    $icon = $path;

                    unlink($_POST['oldDashboardImage']);
                } else {

                    $_SESSION['status'] = "Error";

                    $_SESSION['message'] = "Error ocurred! [Use PNG, JPG, JPEG]";

                    $_SESSION['icon'] = 'error';

                    header('Location: profile.php');
                }
            }
        }
    }

    if ($_FILES['newLoginIconIcon']['name'] == "") {

        $icon2 = $oldLoginIcon;
    } else {

        if ($login_error === 0) {

            if ($login_img_size > 12000000) {

                $_SESSION['status'] = "Error";

                $_SESSION['message'] = "Error ocurred!";

                $_SESSION['icon'] = 'error';

                header('Location: profile.php');
            } else {

                $img_ex = pathinfo($login_img_name, PATHINFO_EXTENSION);

                $img_ex_lc = strtolower($img_ex);

                $allowed_extensions = array('png', 'jpg', 'jpeg');

                if (in_array($img_ex_lc, $allowed_extensions)) {

                    $new_image_name = uniqid("admin_panel", true) . '.' . $img_ex_lc;

                    $img_upload_path = "uploads/admin_panel/" . $new_image_name;

                    move_uploaded_file($login_tmp_name, $img_upload_path);

                    $path = "uploads/admin_panel/" . $new_image_name;

                    $icon2 = $path;

                    unlink($_POST['oldLoginImage']);
                } else {

                    $_SESSION['status'] = "Error";

                    $_SESSION['message'] = "Error ocurred! [Use PNG, JPG, JPEG]";

                    $_SESSION['icon'] = 'error';

                    header('Location: profile.php');
                }
            }
        }
    }

    $sql1 = "UPDATE `admin_settings` SET `value` = '$appName' WHERE `name` = 'Admin Panel Name'";

    $sql2 = "UPDATE `admin_settings` SET `value` = '$icon' WHERE `name` = 'Dashboard Icon'";

    $sql3 = "UPDATE `admin_settings` SET `value` = '$icon2' WHERE `name` = 'Login Image'";

    $query_run1 = mysqli_query($conn, $sql1);

    $query_run2 = mysqli_query($conn, $sql2);

    $query_run3 = mysqli_query($conn, $sql3);

    if ($query_run1 && $query_run2 && $query_run3) {

        $_SESSION['status'] = "Updated";

        $_SESSION['message'] = "Admin Panel Settings Updated Successfully";

        $_SESSION['icon'] = 'success';

        header('Location: profile.php');
    } else {

        $_SESSION['status'] = "Error";

        $_SESSION['icon'] = 'error';

        $_SESSION['message'] = "Error ocurred!";

        header('Location: profile.php');
    }
}

// -- Send Notification --

if (isset($_POST['send_notification'])) {
    $title = filter($_POST['title']);
    $description = filter($_POST['description']);
    $message = filter($_POST['message']);
    $url = filter($_POST['url']);
    $img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];
    if ($error === 0) {
        if ($img_size > 12000000) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Image is Large';
            header("Location: notifications.php");
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_extensions = array('png', 'jpg', 'jpeg');
            if (in_array($img_ex_lc, $allowed_extensions)) {
                $new_image_name = uniqid("notifications", true) . '.' . $img_ex_lc;
                $img_upload_path = "uploads/notifications/" . $new_image_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                $image = "uploads/notifications/" . $new_image_name;
            } else {
                $_SESSION['status'] = "Error";
                $_SESSION['message'] = "Notification Sending  failed [USE PNG,  JPG, JPEG Files]";
                $_SESSION['icon'] = 'error';
                header('Location: notifications.php');
            }
        }
    } else {
        $_SESSION['status'] = "Error";
        $_SESSION['message'] = "Notification Sending failed ['Some Error Occured']";
        $_SESSION['icon'] = 'error';
        header('Location: notifications.php');
    }
    $appId = getSettingValue('app_settings', 'FCM App Id');
    $apiKey = getSettingValue('app_settings', 'FCM Api Key');
    $domain = $_SERVER['HTTP_HOST'];
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    // Modify the image path according to your specific scenario
    $imagePath = 'uploads/notifications/' . $image;
    $imageUrl = $protocol . $domain . $imagePath;
    $result = sendFCMNotification($title, $message, $description,);
    //    echo $result;
    $sql = "INSERT INTO `notifications`(`title`, `description`, `image`, `type`, `data`, `date`) 
            VALUES ('$title','$message','$image','','', current_timestamp())";
    $query_run = mysqli_query($conn, $sql);
    if ($result == "Notification sent successfully!") {
        // echo "Notification sent successfully.";
        $_SESSION['status'] = "Sent";
        $_SESSION['message'] = "Notification Sent Successfully";
        $_SESSION['icon'] = 'success';
        header('Location: notifications.php');
    } else {
        // echo "Notification could not be sent.";
        $_SESSION['status'] = "Error";
        $_SESSION['icon'] = 'error';
        $_SESSION['message'] = "Error ocurred!";
        header('Location: notifications.php');
    }
}

if (isset($_POST['sendNotification'])) {
    $type = filter($_POST['type']);
    $message = filter($_POST['message']);
    $title = filter($_POST['title']);
    $id = filter($_POST['id']);
    $image = filter($_POST['image']);
    $appId = getSettingValue('app_settings', 'FCM App Id');
    $apiKey = getSettingValue('app_settings', 'FCM Api Key');
    $domain = $_SERVER['HTTP_HOST'];
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    // Modify the image path according to your specific scenario
    $imagePath = '/' . $image;
    $imageUrl = $protocol . $domain . $imagePath;
    $result = sendFCMNotification($title, $message, $description,);
    $sql = "INSERT INTO `notifications`(`title`, `description`, `image`, `type`, `data`, `date`) 
            VALUES ('$title','$message','$image','$type','$id', current_timestamp())";
    $query_run = mysqli_query($conn, $sql);
    if ($result == "Notification sent successfully!") {
        http_response_code(200);
        echo json_encode(array("Message" => "Notification  Sent Successfully"));
    } else {
        http_response_code(400);
        echo json_encode(array("Message" => "Error Occured!"));
    }
}

// Update Admin Details

if (isset($_POST['save_admin_panel_login_details'])) {

    $username = filter($_POST['username']);

    $password = filter($_POST['password']);

    $sql = "UPDATE `admin_login` SET `username`='$username', `password`='$password' WHERE `id` = '1'";

    $query_run = mysqli_query($conn, $sql);

    if ($query_run) {

        $_SESSION['status'] = "Updated";

        $_SESSION['message'] = "Admin Panel Settings Updated Successfully";

        $_SESSION['icon'] = 'success';

        header('Location: profile.php');
    } else {

        $_SESSION['status'] = "Error";

        $_SESSION['icon'] = 'error';

        $_SESSION['message'] = "Error ocurred!";

        header('Location: profile.php');
    }
}
