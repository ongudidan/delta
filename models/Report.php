<?php

namespace app\models;

use Yii;

class Report
{
    public static function calculateNetProfit()
    {
        // Calculate Total Sales Revenue
        $totalSales = Sales::find()->sum('total_amount');

        // Calculate Total Expenses
        $totalExpenses = Expenses::find()->sum('amount');

        // Calculate COGS using FIFO
        $cogs = 0;
        $products = Products::find()->all();

        foreach ($products as $product) {
            $soldQuantity = $product->getSales()->sum('quantity') ?: 0;

            if ($soldQuantity > 0) {
                $cogs += self::calculateCogsFifo($product, $soldQuantity);
            }
        }

        // Calculate Net Profit
        $netProfit = $totalSales - ($cogs + $totalExpenses);

        return [
            'total_sales' => $totalSales,
            'cogs' => $cogs,
            'expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];
    }

    private static function calculateCogsFifo($product, $soldQuantity)
    {
        $purchases = $product->getPurchases()
            ->orderBy(['purchase_date' => SORT_ASC]) // FIFO: Earliest purchases first
            ->all();

        $cogs = 0;
        foreach ($purchases as $purchase) {
            if ($soldQuantity <= 0) {
                break; // All sold quantity accounted for
            }

            $purchaseQuantity = $purchase->quantity;
            $quantityToDeduct = min($soldQuantity, $purchaseQuantity);

            $cogs += $quantityToDeduct * $purchase->buying_price;

            $soldQuantity -= $quantityToDeduct; // Deduct sold quantity
        }

        return $cogs;
    }
}
