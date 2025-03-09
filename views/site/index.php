<?php

/** @var yii\web\View $this */

use kartik\daterange\DateRangePicker;
use yii\helpers\Url;

$this->title = 'Home';

// Define the items to display with their titles, amounts, and unique IDs for JavaScript to target
$items = [
    ['title' => 'Products Sold', 'card_class' => 'l-bg-green', 'icon' => 'fa-award', 'id' => 'totalProducts'],
    ['title' => 'Expenditure', 'amount' => Yii::$app->formatter->asCurrency($totalExpenditure), 'card_class' => 'l-bg-cyan', 'icon' => 'fa-briefcase', 'id' => 'totalExpenses'],
    ['title' => 'Income', 'amount' => Yii::$app->formatter->asCurrency($totalIncome), 'card_class' => 'l-bg-purple', 'icon' => 'fa-globe', 'id' => 'totalIncome'],
    ['title' => 'Net Profit', 'amount' => Yii::$app->formatter->asCurrency($netProfit), 'card_class' => 'l-bg-orange', 'icon' => 'fa-money-bill-alt', 'id' => 'netProfit'],
];

// Get today's date to set as default range
$today = date('d-M-y');
$defaultRange = "$today to $today";

$host = $_SERVER['HTTP_HOST'];
?>

<div class="col-12 col-sm-12 col-lg-12">
    <div class="section">
        <div class="section-body">
            <div class="row pt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-action">
                                <form>
                                    <div class="input-group">
                                        <?php
                                        echo DateRangePicker::widget([
                                            'name' => 'date_range_1',
                                            // 'value' => $defaultRange, // Set the default date range to today's date
                                            'id' => 'dateRange',
                                            'convertFormat' => true,
                                            'useWithAddon' => true,
                                            'options' => [
                                                'placeholder' => 'Select Date Range...', // Add placeholder
                                                'class' => 'form-control',
                                            ],
                                            'pluginOptions' => [
                                                'locale' => [
                                                    'format' => 'd-M-y',
                                                    'separator' => ' to ',
                                                ],
                                                'opens' => 'right'
                                            ]
                                        ]);
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <style>
                                .card-container {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 1rem;
                                    /* Adjust the gap between cards as needed */
                                }

                                .card {
                                    flex: 1 1 calc(25% - 1rem);
                                    /* Adjust the basis percentage and gap */
                                    box-sizing: border-box;
                                }

                                .card-content {
                                    white-space: nowrap;
                                    /* Prevent text from wrapping */
                                    overflow: hidden;
                                    /* Hide overflowing text */
                                    text-overflow: ellipsis;
                                    /* Show ellipsis when text overflows */
                                }

                                .card-icon {
                                    margin-right: 15px;
                                    /* Adjust spacing as needed */
                                }

                                @media (max-width: 1200px) {
                                    .card {
                                        flex: 1 1 calc(33.33% - 1rem);
                                        /* Adjust basis for medium screens */
                                    }
                                }

                                @media (max-width: 992px) {
                                    .card {
                                        flex: 1 1 calc(50% - 1rem);
                                        /* Adjust basis for small screens */
                                    }
                                }

                                @media (max-width: 768px) {
                                    .card {
                                        flex: 1 1 100%;
                                        /* Full width for extra small screens */
                                    }
                                }
                            </style>

                            <div class="card-container">
                                <?php foreach ($items as $item) { ?>
                                    <div class="card <?= $item['card_class'] ?>">
                                        <div class="card-statistic-3">
                                            <div class="card-icon card-icon-large"><i class="fa <?= $item['icon'] ?>"></i></div>
                                            <div class="card-content">
                                                <h4 class="card-title"><?= $item['title'] ?></h4>
                                                <span id="<?= $item['id'] ?>">0</span>
                                                <!-- <?= $host ?> -->
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3 d-flex">
                <div class="col-xl-6 col-md-6 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>This Week Sales</h4>
                        </div>
                        <div class="card-body">

                            <head>
                                <script>
                                    window.onload = function() {
                                        var chart = new CanvasJS.Chart("chartContainer", {
                                            animationEnabled: true,
                                            theme: "light2",
                                            axisY: {
                                                title: "Amount (in Kenya Shillings)"
                                            },
                                            data: [{
                                                type: "column",
                                                yValueFormatString: "#,##0.## KES",
                                                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                                            }]
                                        });
                                        chart.render();
                                    }
                                </script>
                            </head>
                            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header justify-content-between">
                            <h4>This Week Report</h4>

                            <!-- <ul class="pagination mb-0">
                        <li class="page-item ">
                            <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>

                        <li class="page-item ">
                            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul> -->
                        </div>
                        <div class="card-body d-flex flex-column table-responsive">
                            <table class="table table-sm flex-grow-1">
                                <thead>
                                    <tr>
                                        <th scope="col">Day</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Products Sold</th>
                                        <th scope="col">Expenses</th>
                                        <th scope="col">Profit</th>
                                        <th scope="col">Net Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalProductsSold = 0;
                                    $totalExpenses = 0;
                                    $totalProfit = 0;
                                    $totalNetProfit = 0;

                                    foreach ($reportData as $data):
                                        // Sum totals
                                        $totalProductsSold += $data['products_sold'] ?? 0;
                                        $totalExpenses += $data['expenses'] ?? 0;
                                        $totalProfit += $data['profit'] ?? 0;
                                        $totalNetProfit += $data['net_profit'] ?? 0;
                                    ?>
                                        <tr>
                                            <th scope="row"><?= strtoupper(substr($data['day'], 0, 3)) ?></th>
                                            <td><?= $data['date'] ?></td>
                                            <td><?= $data['products_sold'] ?? 0 ?></td>
                                            <td><?= Yii::$app->formatter->asCurrency($data['expenses'] ?? 0, 'KES') ?></td>
                                            <td><?= Yii::$app->formatter->asCurrency($data['profit'] ?? 0, 'KES') ?></td>
                                            <td><?= Yii::$app->formatter->asCurrency($data['net_profit'] ?? 0, 'KES') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr style="font-size: 1.1em; font-weight: bold; text-transform: uppercase;">
                                        <th scope="row" colspan="2">Total</th>
                                        <td><?= $totalProductsSold ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($totalExpenses, 'KES') ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($totalProfit, 'KES') ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($totalNetProfit, 'KES') ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-danger">Low Stock Alert: Products Below 3 Units</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Product Name</th>
                                            <th>Product Category</th>
                                            <th>Total Quantity in Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lowStockProducts as $index => $product) { ?>
                                            <tr>
                                                <td>
                                                    <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                </td>
                                                <td><?= $product['product']->product_name ?></td>
                                                <td><?= $product['product']->category->category_name ?></td>
                                                <td><?= $product['totalQuantity'] ?></td>
                                                <td>
                                                    <a href="<?= Url::to(['purchases/create', 'product_id' => $product['product']->product_id]) ?>"
                                                        class="btn btn-warning">
                                                        Add Stock
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>


                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$script = <<< JS
// Handle the change event for the date range picker
$('#dateRange').change(function() {
    var dateRange = $(this).val(); // Get the selected date range
    var dates = dateRange.split(' to '); // Split the date range into start and end dates
    
    if (dates.length !== 2) {
        alert('Invalid date range format.');
        return;
    }
    
    var startDate = dates[0];
    var endDate = dates[1];

    // Make an AJAX request to fetch data for the selected date range
    $.get('/site/get-date-range', { startDate: startDate, endDate: endDate })
    .done(function(data) {
        if (data.error) {
            alert(data.error);
        } else {
            console.log(data);
            // Update the content of the amount fields with the received data
            $('#totalProducts').text(data.totalProducts);
            $('#totalExpenses').text(data.totalExpenses);
            $('#totalIncome').text(data.totalSales);
            $('#netProfit').text(data.netProfit);
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Error occurred while fetching data: ' + textStatus + ' - ' + errorThrown);
    });
});
JS;
$this->registerJs($script);
?>