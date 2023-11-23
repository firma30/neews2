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
        <h1 class="mt-4 dash-heading">Notifications</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Notifications</li>
        </ol>
        <div class="row my-4">
            <div class="col-12 add-category-box">
                <h1 class="category-heading">Send Notification</h1>
                <div class="categort-add-container">
                    <form method="post" action="code.php" enctype="multipart/form-data" class="d-flex align-items-start flex-wrap">
                        <div class="mb-3 mx-2">
                            <label for="image" class="form-label">Image</label>
                            <input required name="image" type="file" class="form-control" id="image">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="title" class="form-label">Title</label>
                            <input required type="text" placeholder="Title" class="form-control" name="title" id="title">
                        </div>
                        <div class="mb-3 mx-2">
                            <label for="description" class="form-label">Message</label>
                            <textarea class="form-control" placeholder="Message" id="message" name="message" rows="1" cols="50"></textarea>
                        </div>
                        <button class="add-category-btn mt-4" name="send_notification" type="submit">Send To
                            All</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="user-table">
                <table id="users_table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


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
    $(document).ready(function() {
        $('.send-notif-ind').click(function(e) {
            e.preventDefault();
            var id = $(this).val();
            // console.log(user_id);
            $('.user-uid').val(id);
            $('#sendNotificationInd').modal('show')
        });
    });
</script>

<script>
    $(document).ready(function() {
        //    Initializing the table
        var table = $('#users_table').DataTable({
            // Fetching the data
            ajax: {
                type: 'GET',
                url: 'APIs/getNotification.php',
                dataType: 'json',
                dataSrc: '',
                async: true
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'title'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return ` ${data['description'].slice(0, 175)}.... `;
                    }
                },
                {
                    data: 'image',
                    render: function(data, type, row, meta) {
                        if (data == '') {
                            return 'No Image';
                        } else {
                            return `<img style="height: 50px;" src="${data}" alt="img">`;
                        }
                    }
                },
                {
                    data: 'type'
                },
                {
                    data: 'date'
                }
            ],
            rowCallback: function(row, data, index) {
                $(row).attr('id', 'box' + data['id']);;
            }
        });
    });
</script>
<?php
include "includes/footer.php";
?>