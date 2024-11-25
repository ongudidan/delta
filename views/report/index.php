<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */

$this->title = 'Sales and Purchases Report';

$items = [
    ['title' => 'Total daily sales', 'profit' => number_format($dailyProfit, 2), 'count' => array_sum(array_column($dailyProductCounts, 'total_quantity')), 'image' => '/web/otika/assets/img/banner/1.png'],
    ['title' => 'Total weekly sales', 'profit' => number_format($weeklyProfit, 2), 'count' => array_sum(array_column($weeklyProductCounts, 'total_quantity')), 'image' => '/web/otika/assets/img/banner/2.png'],
    ['title' => 'Total monthly sales', 'profit' => number_format($monthlyProfit, 2), 'count' => array_sum(array_column($monthlyProductCounts, 'total_quantity')), 'image' => '/web/otika/assets/img/banner/3.png'],
    ['title' => 'Total yearly sales', 'profit' => number_format($yearlyProfit, 2), 'count' => array_sum(array_column($yearlyProductCounts, 'total_quantity')), 'image' => '/web/otika/assets/img/banner/4.png'],
];

$purchaseItems = [
    ['title' => 'Total daily purchases', 'count' => array_sum(array_column($dailyPurchaseCounts, 'total_quantity'))],
    ['title' => 'Total weekly purchases', 'count' => array_sum(array_column($weeklyPurchaseCounts, 'total_quantity'))],
    ['title' => 'Total monthly purchases', 'count' => array_sum(array_column($monthlyPurchaseCounts, 'total_quantity'))],
    ['title' => 'Total yearly purchases', 'count' => array_sum(array_column($yearlyPurchaseCounts, 'total_quantity'))],
];
?>

<div class="row">
    <?php foreach ($items as $item) { ?>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                <div class="card-content">
                                    <h5 class="font-15"><?= $item['title'] ?></h5>
                                    <h2 class="mb-3 font-18">Ksh. <?= $item['count'] ?></h2>
                                    <p class="mb-0"><span class="col-green"><?= $item['profit'] ?></span> Profit</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                <div class="banner-img">
                                    <img src="<?= $item['image'] ?>" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <?php foreach ($purchaseItems as $item) { ?>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card">
                <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                <div class="card-content">
                                    <h5 class="font-15"><?= $item['title'] ?></h5>
                                    <h2 class="mb-3 font-18"><?= $item['count'] ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
