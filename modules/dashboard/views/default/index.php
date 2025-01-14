<?php

use app\models\Expenses;
use app\models\Products;
use app\models\Purchases;
use app\models\Sales;
use yii\helpers\Html;
use yii\helpers\Url;


// // Fetch total number of products
// $totalProducts = Products::find()->count();

// // Fetch total expenses by summing up the 'amount' field in the Expenses table
// $totalExpenses = Yii::$app->formatter->asCurrency(Expenses::find()->sum('amount'));

// // Fetch total income by summing up the 'total_amount' field in the Sales table
// $totalIncome = Yii::$app->formatter->asCurrency(Sales::find()->sum('total_amount'));

// // Fetch total purchases by summing up the 'total_cost' field in the Purchases table
// $totalPurchases = Yii::$app->formatter->asCurrency(Purchases::find()->sum('total_cost'));

// // Calculate net profit as the difference between total income, total purchases, and total expenses
// $netProfit = Yii::$app->formatter->asCurrency(
//     Sales::find()->sum('total_amount') - 
//     Expenses::find()->sum('amount') - 
//     Purchases::find()->sum('total_cost')
// );


// Get the Unix timestamp for the start of 2025
$yearStart = strtotime('1 January 2025 00:00:00');

// Fetch total number of products
$totalProducts = Products::find()->count();

// Fetch total expenses by summing up the 'amount' field in the Expenses table from 2025 onwards
$totalExpenses = Yii::$app->formatter->asCurrency(
    Expenses::find()
        ->where(['>=', 'created_at', $yearStart]) // Filter from 2025 onwards
        ->sum('amount')
);

// Fetch total income by summing up the 'total_amount' field in the Sales table from 2025 onwards
$totalIncome = Yii::$app->formatter->asCurrency(
    Sales::find()
        ->where(['>=', 'sale_date', $yearStart]) // Filter from 2025 onwards
        ->sum('total_amount')
);

// Fetch total purchases by summing up the 'total_cost' field in the Purchases table from 2025 onwards
$totalPurchases = Yii::$app->formatter->asCurrency(
    Purchases::find()
        ->where(['>=', 'purchase_date', $yearStart]) // Filter from 2025 onwards
        ->sum('total_cost')
);

// Calculate net profit as the difference between total income, total purchases, and total expenses
$netProfit = Yii::$app->formatter->asCurrency(
    Sales::find()
        ->where(['>=', 'sale_date', $yearStart]) // Filter from 2025 onwards
        ->sum('total_amount') -
        Expenses::find()
        ->where(['>=', 'created_at', $yearStart]) // Filter from 2025 onwards
        ->sum('amount') -
        Purchases::find()
        ->where(['>=', 'purchase_date', $yearStart]) // Filter from 2025 onwards
        ->sum('total_cost')
);





// Fetch the earliest year of product creation using UNIX timestamp conversion
$startYear = '2025';

// Fetch the latest year of product creation using UNIX timestamp conversion
// $latestYear = Products::find()
//     ->select("YEAR(FROM_UNIXTIME(created_at)) as year")
//     ->orderBy(['created_at' => SORT_DESC])
//     ->scalar();

$latestYear = date('Y');


?>

<div class="col-12 col-sm-12 col-lg-12">
    <div class="section">
        <div class="section-body">

            <!-- top section for the boxes start -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Purchases (<?= Html::encode($startYear) ?> - <?= Html::encode($latestYear) ?>)</h6>
                                    <h3><?= $totalPurchases ?? 0 ?></h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-boxes fa-3x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Sales (<?= Html::encode($startYear) ?> - <?= Html::encode($latestYear) ?>)</h6>
                                    <h3><?= $totalIncome ?? 0 ?></h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-dollar-sign fa-3x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Expenses (<?= Html::encode($startYear) ?> - <?= Html::encode($latestYear) ?>)</h6>
                                    <h3><?= $totalExpenses ?? 0 ?></h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-money-bill-wave fa-3x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Net Profit (<?= Html::encode($startYear) ?> - <?= Html::encode($latestYear) ?>)</h6>
                                    <h3><?= $netProfit ?? 0 ?></h3>
                                </div>
                                <div class="db-icon">
                                    <i class="fas fa-chart-line fa-3x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- top section for the boxes end -->

            <!-- charst section start  -->
            <div class="row">
                <div class="col-md-12 col-lg-6">

                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title"><?= date('Y'); ?> Sales & Purchases</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="apexcharts-area"></div>
                        </div>
                    </div>

                </div>
                <div class="col-md-12 col-lg-6">

                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title"><?= date('F Y'); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="donut-chart"></div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- charst section end  -->

            <!-- weekly report section start  -->
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">This Week Report</h5>
                            <ul class="chart-list-out student-ellips">
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table star-student table-hover table-center table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">Day</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Products Sold</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Expenses</th>
                                            <th scope="col">Profit</th>
                                            <th scope="col">Net Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalProductsSold = 0;
                                        $totalSales = 0;
                                        $totalExpenses = 0;
                                        $totalProfit = 0;
                                        $totalNetProfit = 0;

                                        // Define a list of unique colors
                                        $rowColors = [
                                            'table-cyan',     // Light Cyan
                                            'table-lime',     // Light Lime
                                            'table-amber',    // Light Amber
                                            'table-teal',     // Light Teal
                                            'table-pink',     // Light Pink
                                            'table-orange',   // Light Orange
                                            'table-indigo',   // Light Indigo
                                            'table-brown'     // Light Brown
                                        ];


                                        // Keep track of used colors
                                        $usedColors = [];

                                        foreach ($reportData as $data):
                                            // Accumulate totals
                                            $totalProductsSold += $data['products_sold'] ?? 0;
                                            $totalSales += $data['sales'] ?? 0;
                                            $totalExpenses += $data['expenses'] ?? 0;
                                            $totalProfit += $data['profit'] ?? 0;
                                            $totalNetProfit += $data['net_profit'] ?? 0;

                                            // Assign a unique color
                                            foreach ($rowColors as $color) {
                                                if (!in_array($color, $usedColors)) {
                                                    $rowClass = $color;
                                                    $usedColors[] = $color;
                                                    break;
                                                }
                                            }
                                        ?>
                                            <tr class="<?= $rowClass ?>">
                                                <th scope="row"><?= strtoupper(substr($data['day'], 0, 3)) ?></th>
                                                <td><?= Yii::$app->formatter->asDate($data['date'], 'php:Y-m-d') ?></td>
                                                <td><?= $data['products_sold'] ?? 0 ?></td>
                                                <td><?= Yii::$app->formatter->asCurrency($data['sales'] ?? 0, 'KES') ?></td>
                                                <td><?= Yii::$app->formatter->asCurrency($data['expenses'] ?? 0, 'KES') ?></td>
                                                <td><?= Yii::$app->formatter->asCurrency($data['profit'] ?? 0, 'KES') ?></td>
                                                <td><?= Yii::$app->formatter->asCurrency($data['net_profit'] ?? 0, 'KES') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7">&nbsp;</td>
                                        </tr>
                                        <tr style="font-size: 1.1em; font-weight: bold; text-transform: uppercase;">
                                            <th scope="row" colspan="2">Total</th>
                                            <td><?= $totalProductsSold ?></td>
                                            <td><?= Yii::$app->formatter->asCurrency($totalSales, 'KES') ?></td>
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
            </div>
            <!-- weekly report section end  -->


            <!-- low product stock alert section start -->
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header">
                            <h5 class="text-danger">Low Stock Alert: Products Below 3 Units</h5>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-center table-borderless table-striped star-student" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
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
                                                        class="btn btn-sm btn-warning py-0 px-1" style="font-size: 0.75rem;">
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
            <!-- low product stock alert section end -->


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