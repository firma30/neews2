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
        <h1 class="mt-4 dash-heading">Settings</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Settings</li>
        </ol>
        <div class="row my-4">
            <div class="col-12 add-category-box  my-4">
                <h1 class="category-heading">FCM Notification Settings</h1>
                <div class="categort-add-container">
                    <form class="d-flex align-items-center flex-wrap" action="code.php" method="POST">
                        <div class="mb-3 mx-2">
                            <label for="FCMAppId" class="form-label">FCM App Id</label>
                            <input type="text" value="" style="width: 500px" class="form-control" id="FCMAppId" name="FCMAppId">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="FCMApiKey" class="form-label">FCM Rest Api Key</label>
                            <input type="text" value="" style="width: 500px" class="form-control" id="FCMApiKey" name="FCMApiKey">
                        </div>
                        <button type="submit" class="add-category-btn edit-category-btn mt-3" name="save_notification_settings">Save</button>
                    </form>
                </div>
            </div>
            <div class="col-12 add-category-box  my-4">
                <h1 class="category-heading">AD Settings</h1>
                <div class="form-check form-switch mx-4">
                    <input class="form-check-input enableSwitch" style="height: 2rem; width: 4rem;" type="checkbox" role="switch">
                </div>
                <br>
                <p id="text" class="mx-4"></p>
                <div class="categort-add-container" id="AD-Setting-container">
                    <form action="code.php" method="POST" id="form">
                        <div class="ad-container" style="padding: 0rem  1rem;">
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="mb-3 mx-2 col-4">
                                    <label for="andBanner" class="form-label">Android Banner Ad</label>
                                    <input type="text" class="form-control" id="andBanner" name="andBanner">
                                </div>
                                <div class="mb-3 mx-2 col-4">
                                    <label for="iosBanner" class="form-label">iOS Banner Ad</label>
                                    <input type="text" class="form-control" id="iosBanner" name="iosBanner">
                                </div>
                            </div>
                            <button type="submit" class="add-category-btn edit-category-btn my-3" name="save_ad_settings">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "includes/script.php";
?>

<script>
    <?php if (isset($_SESSION['status'])) { ?>
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
    function getAdData() {
        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                'getAppSetting': true
            },
            dataType: "JSON",
            success: function(response) {
                if (response && response.length > 0) {
                    // Pastikan elemen-elemen ada sebelum mengakses properti 'value'
                    if ($('.enableSwitch').length > 0 && $('#FCMAppId').length > 0 && $('#FCMApiKey').length > 0) {
                        if (response[0]['value'] == 1) {
                            $('.enableSwitch').prop('checked', true);
                            $('.ad-container').css('display', 'block');
                        } else {
                            $('.enableSwitch').prop('checked', false);
                            $('.ad-container').css('display', 'none');
                        }

                        $('.enableSwitch').val(response[0]['value']);
                        $('.enableSwitch').attr('id', response[0]['id']);
                        $('#FCMAppId').val(response[1]['value']);
                        $('#FCMApiKey').val(response[2]['value']);
                    }
                } else {
                    // Handle data not found or incorrect response
                    console.error("Data not found or incorrect response");
                }
            },
            error: function(error) {
                // Handle AJAX error
                console.error("AJAX request error: " + error.statusText);
            }
        });
    }


    $(document).ready(function() {
        getAdData();
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "APIs/getAdInfoAdmin.php",
                dataType: "JSON",
                success: function(response) {
                    $('#andBanner').val(response[0]['value']);
                    $('#iosBanner').val(response[1]['value']);
                },
                error: function(error) {
                    swal({
                        title: "Failed",
                        text: "Some Error Occurred While Fetching App info!",
                        icon: "error",
                    });
                }
            });
        });
    });
</script>

<script>
    let enableSwitch = $('.enableSwitch').val();

    function EnableDisableFunction() {
        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                'check_onOff': true
            },
            success: function(response) {
                console.log(response);
                let id = $(this).attr('id');
                $.each(response, function(key, value) {
                    var check = value['value'];
                    if (check == '1') {
                        $.ajax({
                            type: "POST",
                            url: "code.php",
                            data: {
                                'disable_ad': true
                            },
                            success: function(response) {
                                swal({
                                    title: "AD Disabled",
                                    text: "AD Disabled Successfully",
                                    icon: "success",
                                });
                                getAdData();
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "code.php",
                            data: {
                                'enable_ad': true,
                            },
                            success: function(response) {
                                swal({
                                    title: "AD Enabled",
                                    text: "AD Enabled Successfully",
                                    icon: "success",
                                });
                                getAdData();
                            }
                        });
                    }
                });
            }
        });
    }

    $('.enableSwitch').on('click', function() {
        EnableDisableFunction();
    });
</script>

<?php
include "includes/footer.php";
?>