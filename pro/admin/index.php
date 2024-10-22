<?php
if (!isset($file_access)) die("Direct File Access Denied");
?>
<style>
    /* Dark background for the content area */
    .content {
        background-color: #212529; /* Dark background color */
        color: white; /* White text color for readability */
        padding: 20px; /* Optional: Add some padding */
    }

    .card {
        background-color: #343a40; /* Darker background for cards */
        border: none; /* Remove card border */
    }

    .card-header {
        background-color: #495057; /* Darker header background */
        color: white; /* White text for card header */
    }

    .info-box {
        background-color: #343a40; /* Same dark background for info boxes */
        color: white; /* White text color */
    }
</style>

<div class="content">
    <h5 class="mt-4 mb-2">Hi, <?php echo $fullname ?></h5>
    
    <div class="row">
        <!-- Doughnut chart for Routes and Passengers -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Routes and Passengers</h3>
                </div>
                <div class="card-body">
                    <canvas id="routesPassengersDoughnutChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut chart for Trains and Schedules -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buses and Schedules</h3>
                </div>
                <div class="card-body">
                    <canvas id="trainsSchedulesDoughnutChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut chart for Feedback and Payments -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Feedback and Payments</h3>
                </div>
                <div class="card-body">
                    <canvas id="feedbackPaymentsDoughnutChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Info Boxes -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Passengers</span>
                    <span class="info-box-number"><?php echo $reg =  $conn->query("SELECT * FROM passenger")->num_rows; ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fa fa-train"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bus</span>
                    <span class="info-box-number"><?php echo $comp = $conn->query("SELECT * FROM train")->num_rows; ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-secondary">
                <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Schedules</span>
                    <span class="info-box-number"><?php echo $schedules = $conn->query("SELECT * FROM schedule")->num_rows; ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fa fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Payments</span>
                    <span class="info-box-number">₹
                    <?php 
                        $row = $conn->query("SELECT SUM(order_amount) AS order_amount FROM payment")->fetch_assoc(); 
                        echo $row['order_amount'] == null ? '0' : $row['order_amount']; 
                    ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-primary">
                <span class="info-box-icon"><i class="fa fa-route"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Routes</span>
                    <span class="info-box-number"><?php echo $routes = $conn->query("SELECT * FROM route")->num_rows; ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fa fa-comment-dots"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Feedbacks Received</span>
                    <span class="info-box-number"><?php echo $feedbacks = $conn->query("SELECT * FROM feedback")->num_rows; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Prepare data for the combined doughnut chart for Routes and Passengers
    const routesPassengersData = {
        labels: [
            'Passengers',
            'Routes'
        ],
        datasets: [{
            label: 'Routes and Passengers',
            data: [
                <?php echo "$reg, $routes"; ?>
            ],
            backgroundColor: [
                'rgba(255, 0, 0, 0.6)',  // red for passengers
                'rgba(0, 0, 255, 0.6)',  // blue for routes
            ],
            borderColor: [
                'rgba(255, 0, 0, 1)',   // red
                'rgba(0, 0, 255, 1)',   // blue
            ],
            borderWidth: 1
        }]
    };

    // Config for the Routes and Passengers doughnut chart
    const routesPassengersConfig = {
        type: 'doughnut',
        data: routesPassengersData,
        options: {
            responsive: true,
            animation: {
                animateScale: true,
                animateRotate: true
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed > 0) {
                                label += ': ' + context.parsed;
                            }
                            return label;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Routes and Passengers'
                }
            }
        },
    };

    // Render the Routes and Passengers doughnut chart
    const routesPassengersDoughnutChart = new Chart(
        document.getElementById('routesPassengersDoughnutChart'),
        routesPassengersConfig
    );

    // Prepare data for the combined doughnut chart for Trains and Schedules
    const trainsSchedulesData = {
        labels: [
            'Trains',
            'Schedules'
        ],
        datasets: [{
            label: 'Trains and Schedules',
            data: [
                <?php echo "$comp, $schedules"; ?>
            ],
            backgroundColor: [
                'rgba(0, 255, 255, 0.6)',  // cyan for trains
                'rgba(128, 128, 128, 0.6)', // grey for schedules
            ],
            borderColor: [
                'rgba(0, 255, 255, 1)',   // cyan
                'rgba(128, 128, 128, 1)', // grey
            ],
            borderWidth: 1
        }]
    };

    // Config for the Trains and Schedules doughnut chart
    const trainsSchedulesConfig = {
        type: 'doughnut',
        data: trainsSchedulesData,
        options: {
            responsive: true,
            animation: {
                animateScale: true,
                animateRotate: true
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed > 0) {
                                label += ': ' + context.parsed;
                            }
                            return label;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Trains and Schedules'
                }
            }
        },
    };

    // Render the Trains and Schedules doughnut chart
    const trainsSchedulesDoughnutChart = new Chart(
        document.getElementById('trainsSchedulesDoughnutChart'),
        trainsSchedulesConfig
    );

    // Prepare data for the combined doughnut chart for Feedback and Payments
    const feedbackPaymentsData = {
        labels: [
            'Feedbacks',
            'Payments'
        ],
        datasets: [{
            label: 'Feedback and Payments',
            data: [
                <?php echo "$feedbacks, {$row['order_amount']}"; ?>
            ],
            backgroundColor: [
                'rgba(255, 255, 0, 0.6)', // yellow for feedbacks
                'rgba(0, 128, 0, 0.6)'    // green for payments
            ],
            borderColor: [
                'rgba(255, 255, 0, 1)',   // yellow
                'rgba(0, 128, 0, 1)'      // green
            ],
            borderWidth: 1
        }]
    };

    // Config for the Feedback and Payments doughnut chart
    const feedbackPaymentsConfig = {
        type: 'doughnut',
        data: feedbackPaymentsData,
        options: {
            responsive: true,
            animation: {
                animateScale: true,
                animateRotate: true
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed > 0) {
                                label += ': ' + context.parsed;
                            }
                            return label;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Feedback and Payments'
                }
            }
        },
    };

    // Render the Feedback and Payments doughnut chart
    const feedbackPaymentsDoughnutChart = new Chart(
        document.getElementById('feedbackPaymentsDoughnutChart'),
        feedbackPaymentsConfig
    );
</script>
