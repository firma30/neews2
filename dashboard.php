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
        <h1 class="mt-4 dash-heading">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row cards-container">
            <div class="col-2 stats-heading-container">
                <h1 class="stats">
                    Stats
                </h1>
                <p>
                    Neews Engagememt Stats
                </p>
            </div>
            <div class="col-10 card-cont mb-4">
                <div class="card card-item">
                    <div class="icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="data">
                        <h1>
                            Users
                        </h1>
                        <p>
                            <span class="numdata" id="user-data-box">
                            </span>
                            <span class="growthNum" id="userGrowthNumber"></span>
                        </p>
                    </div>
                </div>
                <div class="card card-item">
                    <div class="icon">
                        <i class="fa-brands fa-quinscape"></i>
                    </div>
                    <div class="data">
                        <h1>
                            Categories
                        </h1>
                        <p>
                            <span class="numdata" id="categories-data-box"></span>
                            <span class="growthNum" id="categoryGrowthNumber"></span>
                        </p>
                    </div>
                </div>
                <div class="card card-item">
                    <div class="icon">
                        <i class="fa-solid fa-q"></i>
                    </div>
                    <div class="data">
                        <h1>
                            Articles
                        </h1>
                        <p>
                            <span class="numdata" id="articles-data-box"></span>
                            <span class="growthNum" id="articlesGrowthNumber"></span>
                        </p>
                    </div>
                </div>
                <div class="card card-item">
                    <div class="icon">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div class="data">
                        <h1 style="font-size: 1.2rem;">
                            Video Articles
                        </h1>
                        <p>
                            <span style="margin: 10px 0;" class="numdata" id="v_articles-data-box">
                            </span>
                            <span class="growthNum" id="v_articlesGrowthNumber"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4 align-items-start">
            <div class="col-6">
                <div class="user-table-data  user-table-data" style="padding: 1rem 1rem; height: 559px;">
                    <div class="card-heading  text-black d-flex justify-content-between align-items-center">
                        <span>Users Chart <span class="user-chart-heading">[Yearly]</span></span>
                        <div class="btn-group">
                            <button type="button" class="simple-btn yearly-chart" style="padding: 5px 10px;">
                                Yearly
                            </button>
                            <button type="button" class="simple-btn monthly-chart disabled" style="padding: 5px 10px;">
                                Monthly
                            </button>
                            <button type="button" class="simple-btn weekly-chart disabled" style="padding: 5px 10px;">
                                Weekely
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="userMonthChart"></canvas>
                        <canvas id="userDaysMonthChart"></canvas>
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="user-table-data  user-table-data" style="padding: 1rem 1rem; height: 559px;">
                    <div class="card-heading  text-black d-flex justify-content-start align-items-center">
                        <span>User Intrests Chart</span>
                    </div>
                    <div class="card-body" style="height: 80%;">
                        <canvas id="userIntrestChart" style="height: 100%;"></canvas>
                        <p style=" text-align: center;
                         margin: 20px;">
                            User Intrest in Categories</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4 align-items-start">
            <div class="col-6">
                <div class="user-table-data  user-table-data" style="padding: 1rem 1rem; height: 559px;">
                    <div class="card-heading  text-black d-flex justify-content-start align-items-center">
                        <span>User Language Chart</span>
                    </div>
                    <div class="card-body" style="height: 80%;">
                        <canvas id="userLanguageChart" style="height: 100%;"></canvas>
                        <p style="text-align: center;margin: 20px;
    ">Languages Selected By Users</p>
                    </div>
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
    // Set new default font family and font color to mimic Bootstrap's default styling

    Chart.defaults.global.defaultFontFamily =
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';

    Chart.defaults.global.defaultFontColor = '#292b2c';
    $('#userMonthChart').hide();
    $('#userDaysMonthChart').hide();
    $(document).ready(function() {
        $('#userDaysMonthChart').hide();
        $('#weeklyChart').hide();
        $('#userMonthChart').show();
        $.ajax({
            url: 'APIs/getUserIntrestAdmin.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Log the data in the browser console
                //   console.log(response);
                let category = [];
                let data = [];
                $.each(response, function(index, value) {
                    category[index] = value['category'];
                    data[index] = value['count'];
                });
                // console.log(category, data);
                var ctx = document.getElementById("userIntrestChart");
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: category,
                        datasets: [{
                            data: data,
                            backgroundColor: [], // Will be populated dynamically
                            hoverBackgroundColor: [],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                        },
                        legend: {
                            display: false,
                        },
                        cutoutPercentage: 80,
                    },
                });
                // Generate random background colors
                var dataset = myPieChart.data.datasets[0];
                var backgroundColors = [];
                var hoverBackgroundColors = [];
                for (var i = 0; i < dataset.data.length; i++) {
                    var r = Math.floor(Math.random() * 256);
                    var g = Math.floor(Math.random() * 256);
                    var b = Math.floor(Math.random() * 256);
                    var alpha = 0.7; // Opacity value (0.0 - 1.0)
                    var color = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
                    backgroundColors.push(color);
                    // Generate darker shade for hover background color
                    var hoverColor = 'rgba(' + Math.floor(r * 0.7) + ', ' + Math.floor(g * 0.7) + ', ' +
                        Math.floor(b * 0.7) + ', ' + alpha + ')';
                    hoverBackgroundColors.push(hoverColor);
                }
                // Assign new background colors and hover background colors
                dataset.backgroundColor = backgroundColors;
                dataset.hoverBackgroundColor = hoverBackgroundColors;
                // Update the chart
                myPieChart.update();
            },
            error: function(xhr, status, error) {
                // Handle the error case
                console.log(error);
            }
        });


        $.ajax({
            url: 'APIs/getUsersAdmin.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Process the API response
                var languages = [];
                var counts = [];


                // Count the occurrences of each language
                response.forEach(function(user) {
                    var language = user.language;
                    if (language) {
                        var index = languages.indexOf(language);
                        if (index !== -1) {
                            counts[index]++;
                        } else {
                            languages.push(language);
                            counts.push(1);
                        }
                    }
                });


                //    console.log(languages);
                //    console.log(counts);
                var ctx = document.getElementById("userLanguageChart");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: languages,
                        datasets: [{
                            data: counts,
                            backgroundColor: [], // Will be populated dynamically
                            hoverBackgroundColor: [],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                        },

                        legend: {
                            display: false,
                        },
                        cutoutPercentage: 0,
                    },
                });


                // Generate random background colors
                var dataset = myPieChart.data.datasets[0];
                var backgroundColors = [];
                var hoverBackgroundColors = [];
                for (var i = 0; i < dataset.data.length; i++) {
                    var r = Math.floor(Math.random() * 256);
                    var g = Math.floor(Math.random() * 256);
                    var b = Math.floor(Math.random() * 256);
                    var alpha = 0.7; // Opacity value (0.0 - 1.0)
                    var color = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
                    backgroundColors.push(color);

                    // Generate darker shade for hover background color

                    var hoverColor = 'rgba(' + Math.floor(r * 0.7) + ', ' + Math.floor(g * 0.7) + ', ' +
                        Math.floor(b * 0.7) + ', ' + alpha + ')';

                    hoverBackgroundColors.push(hoverColor);

                }

                // Assign new background colors and hover background colors

                dataset.backgroundColor = backgroundColors;
                dataset.hoverBackgroundColor = hoverBackgroundColors;

                // Update the chart

                myPieChart.update();
            },

            error: function(xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
    });
</script>

<script>
    $('.monthly-chart').on('click', function(e) {
        e.preventDefault();
        $('.yearly-chart').addClass('disabled');
        $('.weekly-chart').addClass('disabled');
        $(this).removeClass('disabled');
        $('#userMonthChart').hide();
        $('#weeklyChart').hide();
        $('#userDaysMonthChart').show();
        $('.user-chart-heading').text('[Monthly]');
    });

    $('.yearly-chart').on('click', function(e) {
        e.preventDefault();
        $('.monthly-chart').addClass('disabled');
        $('.weekly-chart').addClass('disabled');
        $(this).removeClass('disabled');
        $('#userDaysMonthChart').hide();
        $('#weeklyChart').hide();
        $('#userMonthChart').show();
        $('.user-chart-heading').text('[Yearly]');
    });

    $('.weekly-chart').on('click', function(e) {
        e.preventDefault();
        $('.yearly-chart').addClass('disabled');
        $('.monthly-chart').addClass('disabled');
        $(this).removeClass('disabled');
        $('#userDaysMonthChart').hide();
        $('#userMonthChart').hide();
        $('#weeklyChart').show();
        $('.user-chart-heading').text('[Weekly]');
    });

    $(document).ready(function() {});
</script>

<script>
    $(document).ready(function() {
        getYearlyUsers();
        getMonthlyUsers();
        getWeeklyUsers();
        getNoOfData('users', '#user-data-box');
        getNoOfData('categories', '#categories-data-box');
        getNoOfData('articles', '#articles-data-box');
        getNoOfData('video_articles', '#v_articles-data-box');
        getNoOfToData('users', '#userGrowthNumber', 'account_created');
        getNoOfToData('categories', '#categoryGrowthNumber', 'created_at');
        getNoOfToData('articles', '#articlesGrowthNumber', 'date_created');
        getNoOfToData('video_articles', '#v_articlesGrowthNumber', 'date_created');

        function getNoOfToData(table_name, selector, coloumn_name) {
            $.ajax({
                url: 'APIs/getNoOfTodayAdmin.php',
                type: 'GET',
                data: {
                    'table_name': table_name,
                    'coloumn_name': coloumn_name
                },

                dataType: 'JSON',
                success: function(response) {
                    var count = response.count;

                    // console.log(count);

                    if (response.count > 0) {
                        $(selector).text(`+${count}`);
                    } else {
                        // Do Nothing
                    }
                },

                error: function(xhr, status, error) {

                    // console.log(error);

                }
            });
        }

        function getNoOfData(table_name, selector) {
            // AJAX request to retrieve the number of rows from the PHP API
            $.ajax({
                url: 'APIs/getNoOfAdmin.php',
                type: 'GET',
                data: {
                    'table_name': table_name,
                },
                dataType: 'json',
                success: function(response) {
                    var count = response.count;

                    // console.log(count);
                    $(selector).text(count);
                },

                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    });


    function getWeeklyUsers() {
        $.ajax({
            type: "GET",
            url: "APIs/getUsersWeeklyStats.php",
            dataType: "json",
            success: function(response) {
                // console.log(response);
                let week = [];
                let data = [];

                $.each(response, function(index, value) {
                    week[index] = value['day'];
                    data[index] = value['count'];
                });

                // console.log(week, data);

                var ctx = document.getElementById("weeklyChart");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: week,
                        datasets: [{
                            label: "Users",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(21, 18, 45, 1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: data,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    maxTicksLimit: 13
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    maxTicksLimit: 10
                                    //   max: 10,
                                },
                                gridLines: {
                                    display: false,
                                }
                            }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    function getYearlyUsers() {
        $.ajax({
            url: 'APIs/getUsersYearlyStats.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data); 
                let months = [];
                let month_count = [];
                $.each(data, function(index, value) {
                    months[index] = value['month'];
                    month_count[index] = value['count'];
                });


                var ctx = document.getElementById("userMonthChart");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: "Users",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(21, 18, 45, 1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: month_count,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    maxTicksLimit: 13
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    maxTicksLimit: 10
                                    //   max: 10,
                                },
                                gridLines: {
                                    display: false,
                                }
                            }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log('API request failed. Error: ' + error); // Log the error message to the console
            }
        });
    }



    function getMonthlyUsers() {
        $.ajax({
            url: 'APIs/getUsersMonthlyStats.php',
            method: 'GET',
            success: function(response) {
                // console.log(response);

                let i = 0;
                let NoOfDays = [];
                let Data = [];
                $.each(response, function(index, value) {
                    NoOfDays[i] = index;
                    Data[i] = value;
                    i++;
                });

                // console.log(NoOfDays);
                // console.log(Data);

                var ctx = document.getElementById("userDaysMonthChart");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: NoOfDays,
                        datasets: [{
                            label: "Users",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(21, 18, 45, 1)",

                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,

                            pointBorderWidth: 2,
                            data: Data,
                        }],
                    },
                    options: {

                        scales: {

                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    maxTicksLimit: 13
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    maxTicksLimit: 10
                                    //   max: 10,
                                },
                                gridLines: {
                                    display: false,

                                }

                            }],

                        },

                        legend: {

                            display: false

                        }

                    }

                });









            },

            error: function(xhr, status, error) {

                console.error(error);

            }

        });

    }
</script>

<?php



include("includes/footer.php");

?>