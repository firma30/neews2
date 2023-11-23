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



        <h1 class="mt-4 dash-heading">App Info</h1>

        <ol class="breadcrumb mb-4">

            <li class="breadcrumb-item active">App Info</li>

        </ol>







        <div class="row my-4">

            <div class="col-12 add-category-box my-4">

                <h1 class="category-heading">App Info</h1>

                <div class="categort-add-container">

                    <form action="code.php" method="POST" style="width: 100%;">

                        <div class="">

                            <div class="row privacy-policy-container">

                            </div>

                            <div class="row about-us-container">

                            </div>

                        </div>

                        <button type="submit" name="save_appinfo" class="add-category-btn  mb-3 mx-2">Save</button>

                    </form>

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

        swal("<?=$_SESSION['message'];?>", {

                icon: "<?=$_SESSION['icon']?>",



            }

        );

    <?php

    unset($_SESSION['status']);

    unset($_SESSION['message']);

    unset($_SESSION['icon']);
}

?>
</script>

<script>
    $(document).ready(function() {

        $.ajax({

            type: "POST",

            url: "code.php",

            data: {

                'getAppInfo': true

            },

            dataType: "JSON",

            success: function(response) {

                // console.log(response);



                $.each(response, function(index, value) {

                    //  console.log(index, value);

                    if (value['id'] == '1') {

                        let html = `

                        <div class="col-8 mb-3 mx-2">

                        <label for="privacy_policy" class="form-label">Privacy Policy</label>

                        <textarea class="form-control" id="privacy_policy" name="privacy_policy" rows="20" style="width: 100%;">${value['information']}</textarea>

                        </div>

                        <div class="col-2 mb-3 mx-2">

                        <label for="catLabel" class="form-label">Language</label>

                        <select name="p_language" id="p_language_option" required class="form-select">

                        <option value="${value['language']}"   class="selected-option">Select Language</option>

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





                        `;



                        $('.privacy-policy-container').append(html);



                        switch (value['language']) {

                            case 'en':

                                $('#p_language_option option:first').text('English');

                                $('.en').remove();

                                break;

                            case 'hi':

                                $('#p_language_option option:first').text('Hindi');

                                $('.hi').remove();

                                break;

                            case 'ar':

                                $('#p_language_option option:first').text('Arabic');

                                $('.ar').remove();

                                break;

                            case 'fr':

                                $('#p_language_option option:first').text('French');

                                $('.fr').remove();

                                break;

                            case 'es':

                                $('#p_language_option option:first').text('Spanish');

                                $('.es').remove();

                                break;

                            case 'de':

                                $('#p_language_option option:first').text('German');

                                $('.de').remove();

                                break;

                            case 'ru':

                                $('#p_language_option option:first').text('Russian');

                                $('.ru').remove();

                                break;

                            case 'id':

                                $('#p_language_option option:first').text('Indonesian');

                                $('.ru').remove();

                                break;

                            case 'ja':

                                $('#p_language_option option:first').text('Japnese');

                                $('.ja').remove();

                                break;

                            case 'it':

                                $('#p_language_option option:first').text('Italian');

                                $('.it').remove();

                                break;



                            default:

                                $('#p_language_option option:first').text('English');

                                break;

                        }

                    } else {

                        let html = `



                        <div class="col-8 mb-3 mx-2">

                        <label for="about_us" class="form-label">About Us</label>

                        <textarea class="form-control" id="about_us" name="about_us" rows="20" style="width: 100%;">${value['information']}</textarea>

                        </div>

                        <div class="col-2 mb-3 mx-2">

                        <label for="catLabel" class="form-label">Language</label>

                        <select name="a_language" id="a_language_option" required class="form-select">

                        <option value="${value['language']}"   class="selected-option">Select Language</option>

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





                        `;



                        $('.about-us-container').append(html);



                        switch (value['language']) {

                            case 'en':

                                $('#a_language_option option:first').text('English');

                                $('.en').remove();

                                break;

                            case 'hi':

                                $('#a_language_option option:first').text('Hindi');

                                $('.hi').remove();

                                break;

                            case 'ar':

                                $('#a_language_option option:first').text('Arabic');

                                $('.ar').remove();

                                break;

                            case 'fr':

                                $('#a_language_option option:first').text('French');

                                $('.fr').remove();

                                break;

                            case 'es':

                                $('#a_language_option option:first').text('Spanish');

                                $('.es').remove();

                                break;

                            case 'de':

                                $('#a_language_option option:first').text('German');

                                $('.de').remove();

                                break;

                            case 'ru':

                                $('#a_language_option option:first').text('Russian');

                                $('.ru').remove();

                                break;

                            case 'id':

                                $('#a_language_option option:first').text('Indonesian');

                                $('.ru').remove();

                                break;

                            case 'ja':

                                $('#a_language_option option:first').text('Japnese');

                                $('.ja').remove();

                                break;

                            case 'it':

                                $('#a_language_option option:first').text('Italian');

                                $('.it').remove();

                                break;



                            default:

                                $('#a_language_option option:first').text('English');

                                break;

                        }

                    }

                });

            },

            error: function(error) {

                swal({

                    title: "Failed",

                    text: "Some Error Occured While Fetching App info!",

                    icon: "error",

                });

            }

        });

    });
</script>







<?php

include "includes/footer.php";

?>