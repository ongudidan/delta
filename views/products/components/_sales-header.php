<?php

use app\models\Sales;

$todayTotalSale = Sales::getSalesForSpecificDay();
$thisWeekTotalSale = Sales::getSalesForSpecificWeek();
$thisMonthTotalSale = Sales::getSalesForSpecificMonth();
$thisYearTotalSale = Sales::getSalesForSpecificYear();

$items =
    [
        ['title' => 'Today Total Sales', 'amount' => $todayTotalSale, 'card_class' => 'l-bg-green', 'icon' => 'fa-award'],
        ['title' => 'This Week Sales', 'amount' => $thisWeekTotalSale, 'card_class' => 'l-bg-cyan', 'icon' => 'fa-briefcase'],
        ['title' => 'This Month Sales', 'amount' => $thisMonthTotalSale, 'card_class' => 'l-bg-purple', 'icon' => 'fa-globe'],
        ['title' => 'This Year Sales', 'amount' => $thisYearTotalSale, 'card_class' => 'l-bg-orange', 'icon' => 'fa-money-bill-alt'],
    ]
?>

<div class="row ">
    <?php foreach ($items as $item) { ?>
        <div class="col-xl-3 col-lg-6">
            <div class="card <?= $item['card_class'] ?>">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa <?= $item['icon'] ?>"></i></div>
                    <div class="card-content">
                        <h4 class="card-title"><?= $item['title'] ?></h4>
                        <span>Ksh. <?= number_format($item['amount'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>