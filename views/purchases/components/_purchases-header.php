<?php

use app\models\Expenses;
use app\models\Purchases;

$todayTotalPurchase = Purchases::getPurchasesForSpecificDay();
$thisWeekTotalPurchase = Purchases::getPurchasesForSpecificWeek();
$thisMonthTotalPurchase = Purchases::getPurchasesForSpecificMonth();
$thisYearTotalPurchase = Purchases::getPurchasesForSpecificYear();

$items =
    [
        ['title' => 'Today Total Purchases Cost', 'amount' => $todayTotalPurchase, 'card_class' => 'l-bg-green', 'icon' => 'fa-award'],
        ['title' => 'This Week Purchases Cost', 'amount' => $thisWeekTotalPurchase, 'card_class' => 'l-bg-cyan', 'icon' => 'fa-briefcase'],
        ['title' => 'This Month Purchases Cost', 'amount' => $thisMonthTotalPurchase, 'card_class' => 'l-bg-purple', 'icon' => 'fa-globe'],
        ['title' => 'This Year Purchases Cost', 'amount' => $thisYearTotalPurchase, 'card_class' => 'l-bg-orange', 'icon' => 'fa-money-bill-alt'],
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