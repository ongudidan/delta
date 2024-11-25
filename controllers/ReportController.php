<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Sales;
use app\models\Purchases;
use app\models\Inventory;

class ReportController extends Controller
{
    public function actionIndex() {}

    function calculateProfit($salesId)
    {
        $sale = Sales::findOne($salesId);
        $productId = $sale->product_id;
        $quantityToSell = $sale->quantity;
        $totalProfit = 0;

        $purchases = (new \yii\db\Query())
            ->select(['p.purchase_id', 'p.buying_price', 'p.quantity', 'i.inventory_id'])
            ->from('purchases p')
            ->join('JOIN', 'inventory i', 'i.purchase_id = p.purchase_id')
            ->where(['p.product_id' => $productId])
            ->andWhere(['>', 'i.quantity', 0])
            ->orderBy(['p.created_at' => SORT_ASC])
            ->all();

        foreach ($purchases as $purchase) {
            if ($quantityToSell <= 0) {
                break;
            }

            $purchaseQuantity = min($purchase['quantity'], $quantityToSell);
            $cost = $purchaseQuantity * $purchase['buying_price'];
            $profit = ($purchaseQuantity * $sale->sell_price) - $cost;
            $totalProfit += $profit;

            // Update inventory
            Inventory::updateAll(
                ['quantity' => new \yii\db\Expression('quantity - :qty', [':qty' => $purchaseQuantity])],
                ['inventory_id' => $purchase['inventory_id']]
            );

            $quantityToSell -= $purchaseQuantity;
        }

        return $totalProfit;
    }
}
