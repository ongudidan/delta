<?php

namespace app\models;

use Yii;

class Report
{
    private const CACHE_KEY = 'report_net_profit';
    private const CACHE_DURATION = 3600; // Cache duration in seconds (1 hour)

    public static function calculateNetProfit()
    {
        $cache = Yii::$app->cache;

        // Try to get data from cache
        $data = $cache->get(self::CACHE_KEY);

        if ($data === false) {
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

            $data = [
                'total_sales' => $totalSales,
                'cogs' => $cogs,
                'expenses' => $totalExpenses,
                'net_profit' => $netProfit,
            ];

            // Store data in cache
            $cache->set(self::CACHE_KEY, $data, self::CACHE_DURATION);
        }

        return $data;
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

    public static function invalidateCache()
    {
        Yii::$app->cache->delete(self::CACHE_KEY);
    }
}
