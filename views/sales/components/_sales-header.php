<?php

use app\models\Sales;
use app\models\Expenses;

// Get total expenses for specific periods
$todayTotalExpense = Expenses::getExpensesForSpecificDay();
$thisWeekTotalExpense = Expenses::getExpensesForSpecificWeek();
$thisMonthTotalExpense = Expenses::getExpensesForSpecificMonth();
$thisYearTotalExpense = Expenses::getExpensesForSpecificYear();

// Get total sales for specific periods
$todayTotalSale = Sales::getSalesForSpecificDay();
$thisWeekTotalSale = Sales::getSalesForSpecificWeek();
$thisMonthTotalSale = Sales::getSalesForSpecificMonth();
$thisYearTotalSale = Sales::getSalesForSpecificYear();

// Get current date, week, month, and year
$today = date('Y-m-d');
$thisWeek = date('Y-W');
$currentMonth = date('Y-m');
$currentYear = date('Y');

// Calculate profits for each period
$dailyProfit = Sales::getDailyProfit();
$weeklyProfit = Sales::getWeeklyProfit(); // Corrected: Use getWeeklyProfit() for weekly profit
$monthlyProfit = Sales::getMonthlyProfit();
$yearlyProfit = Sales::getYearlyProfit();

// Calculate net profits by subtracting expenses from profits
$netDailyProfit = $dailyProfit - $todayTotalExpense;
$netWeeklyProfit = $weeklyProfit - $thisWeekTotalExpense;
$netMonthlyProfit = $monthlyProfit - $thisMonthTotalExpense;
$netYearlyProfit = $yearlyProfit - $thisYearTotalExpense;


$items =
    [
        ['title' => 'Today Total Sales', 'amount' => $todayTotalSale, 'profit'=> $netDailyProfit , 'card_class' => 'l-bg-green', 'icon' => 'fa-award'],
        ['title' => 'This Week Sales', 'amount' => $thisWeekTotalSale, 'profit'=> $netWeeklyProfit , 'card_class' => 'l-bg-cyan', 'icon' => 'fa-briefcase'],
        ['title' => 'This Month Sales', 'amount' => $thisMonthTotalSale, 'profit'=> $netMonthlyProfit , 'card_class' => 'l-bg-purple', 'icon' => 'fa-globe'],
        ['title' => 'This Year Sales', 'amount' => $thisYearTotalSale, 'profit'=> $netYearlyProfit , 'card_class' => 'l-bg-orange', 'icon' => 'fa-money-bill-alt'],
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
                        <span>Total Ksh. <?= number_format($item['amount'], 2) ?></span>
                    </div>
                    <div class="card-content">
                        <span>Profit Ksh. <?= number_format($item['profit'], 2) ?></span>
                    </div>
                
                </div>
            </div>
        </div>
    <?php } ?>
</div>