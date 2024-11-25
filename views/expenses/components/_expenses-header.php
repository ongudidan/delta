<?php

use app\models\Expenses;

$todayTotalExpense = Expenses::getExpensesForSpecificDay();
$thisWeekTotalExpense = Expenses::getExpensesForSpecificWeek();
$thisMonthTotalExpense = Expenses::getExpensesForSpecificMonth();
$thisYearTotalExpense = Expenses::getExpensesForSpecificYear();

$items =
    [
        ['title' => 'Today Total Expenses', 'amount' => $todayTotalExpense, 'card_class' => 'l-bg-green', 'icon' => 'fa-award'],
        ['title' => 'This Week Expenses', 'amount' => $thisWeekTotalExpense, 'card_class' => 'l-bg-cyan', 'icon' => 'fa-briefcase'],
        ['title' => 'This Month Expenses', 'amount' => $thisMonthTotalExpense, 'card_class' => 'l-bg-purple', 'icon' => 'fa-globe'],
        ['title' => 'This Year Expenses', 'amount' => $thisYearTotalExpense, 'card_class' => 'l-bg-orange', 'icon' => 'fa-money-bill-alt'],
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