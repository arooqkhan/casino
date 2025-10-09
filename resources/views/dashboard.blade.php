@extends('admin.master.main')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: #333;
        padding: 20px;
    }

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        color: #7f8c8d;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px 30px -15px;
    }

    .card {
        flex: 1;
        min-width: 250px;
        margin: 0 15px 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 500;
        color: #7f8c8d;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .card-content {
        margin-top: 15px;
    }

    .card-value {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .card-change {
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .up {
        color: #2ecc71;
    }

    .down {
        color: #e74c3c;
    }

    .graph-container {
        background: #191e3a;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin: 0 15px;
        width: calc(100% - 30px);
    }

    .graph-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .graph-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }



    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }

    .stat-item {
        flex: 1;
        min-width: 200px;
        display: flex;
        flex-direction: column;
        padding: 15px;
        background: #191e3a;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    }

    .stat-label {
        font-size: 14px;
        color: #7f8c8d;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
    }

    .third-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .activity-card,
    .progress-card {
        background: #191e3a;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 25px;
    }

    .card-heading {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .activity-item {
        display: flex;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f1f1;
    }

    .activity-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 16px;
        color: white;
    }

    .activity-details {
        flex: 1;
    }

    .activity-title {
        font-weight: 500;
        margin-bottom: 5px;
    }

    .activity-time {
        font-size: 13px;
        color: #7f8c8d;
    }

    .progress-item {
        margin-bottom: 25px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .progress-label {
        font-size: 14px;
        color: #2c3e50;
    }

    .progress-percentage {
        font-size: 14px;
        font-weight: 500;
        color: #3498db;
    }

    .progress-bar {
        height: 8px;
        background: #f1f1f1;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
    }

    @media (max-width: 992px) {
        .third-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .card {
            min-width: 100%;
        }

        .stats-container {
            flex-direction: column;
        }
    }
</style>
</head>

<body>
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Welcome Dashboard</h1>
            <p>Here's what's happening with your payments today</p>
        </div>

        <!-- First Row: Three Cards -->
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Total Balance</div>
                    <div class="card-icon" style="background-color: rgba(52, 152, 219, 0.1); color: #3498db;">
                        <i>💰</i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-value">${{$totalamount}}</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Withdraw PAYMENTS</div>
                    <div class="card-icon" style="background-color: rgba(243, 156, 18, 0.1); color: #f39c12;">
                        <i>⏱️</i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-value">${{$withdrawamount}}</div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Deposit PAYMENTS</div>
                    <div class="card-icon" style="background-color: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                        <i>✅</i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-value">${{$depositamount}}</div>

                </div>
            </div>
        </div>

        <!-- Second Row: Graph -->
      <div class="row">
    <div class="graph-container">
        <div class="graph-header">
            <div class="graph-title">Payment Overview</div>
        </div>

        <!-- Chart -->
        <div class="chart-container">
            <canvas id="paymentChart"></canvas>
        </div>

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-item">
                <span class="stat-label">Total Balance</span>
                <span class="stat-value">${{ number_format($totalamount, 2) }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Withdraw Balance</span>
                <span class="stat-value">${{ number_format($withdrawamount, 2) }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Deposit Balance</span>
                <span class="stat-value">${{ number_format($depositamount, 2) }}</span>
            </div>
        </div>
    </div>
</div>

        <!-- Third Row: Additional Content -->
        <div class="third-row">
            <!-- <div class="activity-card">
                <div class="card-heading">Recent Activity</div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: #3498db;">$$</div>
                        <div class="activity-details">
                            <div class="activity-title">Payment from John Doe</div>
                            <div class="activity-time">Today, 10:30 AM • $245.00</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: #2ecc71;">$$</div>
                        <div class="activity-details">
                            <div class="activity-title">Payment completed</div>
                            <div class="activity-time">Today, 9:15 AM • $1,245.00</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: #e74c3c;">$$</div>
                        <div class="activity-details">
                            <div class="activity-title">Payment failed</div>
                            <div class="activity-time">Today, 8:45 AM • $85.00</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: #f39c12;">$$</div>
                        <div class="activity-details">
                            <div class="activity-title">New subscription</div>
                            <div class="activity-time">Yesterday, 4:20 PM • $29.99/month</div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- <div class="progress-card">
                <div class="card-heading">Payment Goals</div>
                <div class="progress-list">
                    <div class="progress-item">
                        <div class="progress-info">
                            <span class="progress-label">Monthly Target</span>
                            <span class="progress-percentage">72%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 72%; background-color: #3498db;"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-info">
                            <span class="progress-label">Quarterly Target</span>
                            <span class="progress-percentage">45%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 45%; background-color: #2ecc71;"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-info">
                            <span class="progress-label">Customer Growth</span>
                            <span class="progress-percentage">86%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 86%; background-color: #9b59b6;"></div>
                        </div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-info">
                            <span class="progress-label">Revenue Target</span>
                            <span class="progress-percentage">63%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 63%; background-color: #f1c40f;"></div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paymentChart').getContext('2d');

            // Sample data for the chart
            const paymentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                            label: 'Revenue',
                            data: [1850, 2150, 2400, 2780, 2390, 3150, 3450],
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderColor: '#3498db',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pending',
                            data: [800, 950, 1100, 1050, 1200, 950, 800],
                            backgroundColor: 'rgba(243, 156, 18, 0.1)',
                            borderColor: '#f39c12',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>

    @endsection