<?php

namespace app\models;

use yii\base\Model;
use app\models\Sales;
use app\models\Expenses;
use app\models\Products;

class WeeklyReport extends Model
{
    public $week;
    public $startOfWeek;
    public $endOfWeek;

    public function __construct($week = null)
    {
        $this->week = $week ? (int)$week : (int)date('W');
        $year = date('Y');

        $this->startOfWeek = strtotime("{$year}-W{$this->week}-1");
        $this->endOfWeek = strtotime("{$year}-W{$this->week}-7 23:59:59");
    }

    public function getLowStockProducts()
    {
        $products = Products::find()->all();
        $lowStockProducts = [];

        foreach ($products as $product) {
            $totalSalesQuantity = Sales::find()->where(['product_id' => $product->product_id])->sum('quantity');
            $totalPurchasesQuantity = Purchases::find()->where(['product_id' => $product->product_id])->sum('quantity');
            $totalQuantity = $totalPurchasesQuantity - $totalSalesQuantity;

            if ($totalQuantity <= 3) {
                $lowStockProducts[] = [
                    'product' => $product,
                    'totalSalesQuantity' => $totalSalesQuantity,
                    'totalPurchasesQuantity' => $totalPurchasesQuantity,
                    'totalQuantity' => $totalQuantity
                ];
            }
        }

        return $lowStockProducts;
    }

    public function getSales()
    {
        return Sales::find()
            ->where(['between', 'sale_date', $this->startOfWeek, $this->endOfWeek])
            ->all();
    }

    public function getTotalSalesQuantity()
    {
        return Sales::find()
            ->where(['between', 'sale_date', $this->startOfWeek, $this->endOfWeek])
            ->sum('quantity');
    }

    public function getTotalExpenditure()
    {
        return Expenses::find()
            ->where(['between', 'updated_at', $this->startOfWeek, $this->endOfWeek])
            ->sum('amount');
    }

    public function getTotalIncome()
    {
        return Sales::find()
            ->where(['between', 'sale_date', $this->startOfWeek, $this->endOfWeek])
            ->sum('total_amount');
    }

    public function calculateNetProfit()
    {
        $totalProfit = 0;
        $sales = $this->getSales();

        foreach ($sales as $sale) {
            $sale->calculatedProfit = $sale->calculateProfit();
            $totalProfit += $sale->calculatedProfit;
        }

        return $totalProfit - $this->getTotalExpenditure();
    }

    public function getPrevAndNextWeeks()
    {
        return [
            'prevWeek' => $this->week > 1 ? $this->week - 1 : null,
            'nextWeek' => $this->week < 52 ? $this->week + 1 : null,
        ];
    }

    public function getWeeklyReport()
    {
        return Sales::find()
            ->select(['product_id', 'SUM(quantity) AS totalQuantity', 'SUM(total_amount) AS totalAmount'])
            ->where(['between', 'sale_date', $this->startOfWeek, $this->endOfWeek])
            ->groupBy('product_id')
            ->asArray()
            ->all();
    }
}
