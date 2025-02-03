<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "sales".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property float $sell_price
 * @property float $total_amount
 * @property string $sale_date
 *
 * @property Products $product
 */
class Sales extends \yii\db\ActiveRecord
{
    public $calculatedProfit; // Temporary attribute to hold the calculated profit
    public $calculatedBuyingPrice; // Temporary attribute to hold the calculated buying price


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Report::invalidateCache();
        $this->invalidateWeeklyCache();

    }

    public function afterDelete()
    {
        parent::afterDelete();
        Report::invalidateCache();
        $this->invalidateWeeklyCache();

    }

    // Invalidate the weekly report cache after saving a sale
    private function invalidateWeeklyCache()
    {
        $year = date('Y');
        $week = (int)date('W', strtotime($this->sale_date));
        $cacheKey = "weekly_report_{$year}_week_{$week}";
        Yii::$app->cache->delete($cacheKey);
    }

    public function behaviors()
    {
        return [
            // [
            //     'class' => TimestampBehavior::class,
            //     'createdAtAttribute' => false,
            //     'updatedAtAttribute' => 'sale_date',

            // ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'sell_price', 'payment_method_id', 'total_amount'], 'required'],
            [['product_id', 'bulk_sale_id', 'quantity'], 'integer'],
            [['quantity'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'message' => 'Quantity must be at least 1.'],
            [['sell_price','total_amount', 'quantity'], 'number'],
            [['sale_date', 'created_by', 'updated_by'], 'safe'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'product_id']],
            [['quantity'], 'checkStock'],  // Custom validation to check stock
        ];
    }

    /**
     * Custom validation to check if the quantity is available in stock.
     */
    public function checkStock($attribute, $params)
    {
        // Get available stock for the product, including bulk sale stock
        $availableStock = $this->getAvailableStock($this->product_id, $this->bulk_sale_id);

        // If the quantity entered exceeds available stock, add an error
        if ($this->quantity > $availableStock) {
            $this->addError($attribute, 'Available stock: ' . $availableStock);
        }
    }

    /**
     * Calculate the available stock for a product, including bulk sale products.
     */
    private function getAvailableStock($productId, $bulkSaleId = null)
    {
        // Get the total purchased quantity for the product
        $totalPurchased = Purchases::find()
            ->where(['product_id' => $productId])
            ->sum('quantity') ?? 0;

        // Get the total sold quantity for the product
        $totalSold = Sales::find()
            ->where(['product_id' => $productId])
            ->sum('quantity') ?? 0;

        // Initialize bulk sale stock
        $totalBulkSold = 0;

        // If a bulk sale ID is provided, consider the bulk sale stock as well
        if ($bulkSaleId !== null) {
            $totalBulkSold = Sales::find()
                ->where([
                    'bulk_sale_id' => $bulkSaleId,
                    'product_id' => $productId,
                ])
                ->sum('quantity') ?? 0;
        }

        // Calculate the available stock (individual product + bulk sales)
        $availableStock = max(($totalPurchased - $totalSold) + $totalBulkSold, 0);

        return $availableStock;
    }


    // /**
    //  * Gets the product related to this sales record.
    //  */
    // public function getProduct()
    // {
    //     return $this->hasOne(Products::class, ['product_id' => 'product_id']);
    // }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product Name',
            'quantity' => 'Quantity',
            'sell_price' => 'Selling Price',
            'total_amount' => 'Total Amount',
            'sale_date' => 'Sale Date',
            'payment_method_id' => 'Payment Method',
            'paymentMethod.name' => 'Payment Method',

        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['product_id' => 'product_id']);
    }

    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethods::class, ['id' => 'payment_method_id']);
    }

    public function getTotalAmount()
    {
        return $this->quantity * $this->sell_price;
    }

    /////////////////////////////////////////////////////////////////
    public static function getSalesForSpecificDay()
    {
        $startOfDay = strtotime('today');
        $endOfDay = strtotime('tomorrow') - 1;

        return self::find()
            ->where(['between', 'sale_date', $startOfDay, $endOfDay])
            ->sum('total_amount') ?: 0;
    }

    public static function getSalesForSpecificWeek()
    {
        $timestamp = strtotime('today');
        $week = date('W', $timestamp);
        $year = date('Y', $timestamp);

        // Get the start of the week (Sunday)
        $startOfWeek = strtotime($year . 'W' . $week . '0'); // Sunday as the start of the week
        // Get the end of the week (Saturday)
        $endOfWeek = strtotime($year . 'W' . $week . '6 23:59:59'); // Saturday as the end of the week

        return self::find()
            ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
            ->sum('total_amount') ?: 0;
    }


    public static function getSalesForSpecificMonth()
    {
        $timestamp = strtotime('today');
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        // Get the start and end of the month
        $startOfMonth = strtotime($year . '-' . $month . '-01');
        $endOfMonth = strtotime($year . '-' . $month . '-' . date('t', $timestamp) . ' 23:59:59');

        return self::find()
            ->where(['between', 'sale_date', $startOfMonth, $endOfMonth])
            ->sum('total_amount') ?: 0;
    }


    public static function getSalesForSpecificYear()
    {
        $timestamp = strtotime('today');
        $year = date('Y', $timestamp);

        // Get the start and end of the year
        $startOfYear = strtotime($year . '-01-01');
        $endOfYear = strtotime($year . '-12-31 23:59:59');

        return self::find()
            ->where(['between', 'sale_date', $startOfYear, $endOfYear])
            ->sum('total_amount') ?: 0;
    }

    ////////////////////////////////////////////

    /**
     * Calculates the profit for this sale using FIFO method without altering purchase records.
     *
     * @return float
     */
    public function calculateProfit()
    {
        $profit = 0;
        $remainingQuantity = $this->quantity; // Quantity sold in this sale
        $sellingPrice = $this->sell_price; // Selling price per unit

        // Fetch all purchases sorted by the purchase date (oldest first)
        $purchases = Purchases::find()
            ->where(['product_id' => $this->product_id])
            ->orderBy(['purchase_date' => SORT_ASC])
            ->all();

        foreach ($purchases as $purchase) {
            if ($remainingQuantity <= 0) {
                break; // If no quantity left to calculate profit for, exit loop
            }

            // Calculate how much of the current purchase record is used for this sale
            $usedQuantity = min($remainingQuantity, $purchase->quantity);

            // Calculate profit for this portion
            $profit += $usedQuantity * ($sellingPrice - $purchase->buying_price);

            // Decrease remaining quantity to be calculated
            $remainingQuantity -= $usedQuantity;
        }

        return $profit;
    }

    public function calculateBuyingPrice()
    {
        $profit = 0;
        $remainingQuantity = $this->quantity; // Quantity sold in this sale
        $sellingPrice = $this->sell_price; // Selling price per unit
        $buyingPrice = 0; // Initialize buying price

        // Fetch all purchases sorted by the purchase date (oldest first)
        $purchases = Purchases::find()
            ->where(['product_id' => $this->product_id])
            ->orderBy(['purchase_date' => SORT_ASC])
            ->all();

        foreach ($purchases as $purchase) {
            if ($remainingQuantity <= 0) {
                break; // If no quantity left to calculate profit for, exit loop
            }

            // Calculate how much of the current purchase record is used for this sale
            $usedQuantity = min($remainingQuantity, $purchase->quantity);

            // Calculate profit for this portion
            $profit += $usedQuantity * ($sellingPrice - $purchase->buying_price);

            // Set buying price for this purchase
            $buyingPrice = $purchase->buying_price;

            // Decrease remaining quantity to be calculated
            $remainingQuantity -= $usedQuantity;
        }

        return $buyingPrice;
    }


    public static function getDailyProfit()
    {
        $timestamp = strtotime('today');
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        $sales = self::find()
            ->where(['DAY(FROM_UNIXTIME(sale_date))' => $day])
            ->andWhere(['MONTH(FROM_UNIXTIME(sale_date))' => $month])
            ->andWhere(['YEAR(FROM_UNIXTIME(sale_date))' => $year])
            ->all();

        $totalProfit = 0;
        foreach ($sales as $sale) {
            $totalProfit += $sale->calculateProfit();
        }

        return $totalProfit ?: 0;
    }

    public static function getWeeklyProfit()
    {
        $timestamp = strtotime('this week');
        $weekStart = date('Y-m-d', strtotime('monday this week', $timestamp));
        $weekEnd = date('Y-m-d', strtotime('sunday this week', $timestamp));

        $sales = self::find()
            ->where(['between', 'FROM_UNIXTIME(sale_date)', $weekStart, $weekEnd])
            ->all();

        $totalProfit = 0;
        foreach ($sales as $sale) {
            $totalProfit += $sale->calculateProfit();
        }

        return $totalProfit ?: 0;
    }

    public static function getMonthlyProfit()
    {
        $timestamp = strtotime('first day of this month');
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        $sales = self::find()
            ->where(['MONTH(FROM_UNIXTIME(sale_date))' => $month])
            ->andWhere(['YEAR(FROM_UNIXTIME(sale_date))' => $year])
            ->all();

        $totalProfit = 0;
        foreach ($sales as $sale) {
            $totalProfit += $sale->calculateProfit();
        }

        return $totalProfit ?: 0;
    }

    public static function getYearlyProfit()
    {
        $timestamp = strtotime('first day of January');
        $year = date('Y', $timestamp);

        $sales = self::find()
            ->where(['YEAR(FROM_UNIXTIME(sale_date))' => $year])
            ->all();

        $totalProfit = 0;
        foreach ($sales as $sale) {
            $totalProfit += $sale->calculateProfit();
        }

        return $totalProfit ?: 0;
    }

    public static function getWeeklySales()
    {
        // Get the current day of the week
        $currentDay = date('w');

        // If today is Sunday, start of the week is today, otherwise last Sunday
        if ($currentDay == 0) {
            $startOfWeek = strtotime("today midnight");
        } else {
            $startOfWeek = strtotime("last Sunday midnight");
        }

        // End of the week is next Sunday (the start of the next week), minus 1 second (i.e., Saturday 23:59:59)
        $endOfWeek = strtotime("next Sunday midnight") - 1;

        // Initialize the data points array
        $dataPoints = [];

        // Loop through each day of the current week (Sunday to Saturday)
        for ($currentDay = $startOfWeek; $currentDay <= $endOfWeek; $currentDay = strtotime('+1 day', $currentDay)) {
            $dayStart = strtotime("midnight", $currentDay); // Start of the current day
            $dayEnd = strtotime("23:59:59", $currentDay);   // End of the current day

            // Fetch sales data for the current day
            $salesData = (new \yii\db\Query())
                ->select(['total_sales' => new Expression('SUM(sell_price)')])
                ->from('sales')
                ->where(['between', 'sale_date', $dayStart, $dayEnd])
                ->scalar(); // Get the total sales amount for the day

            // Get the day of the week (e.g., 'Sunday', 'Monday')
            $dayName = date('l', $currentDay);

            // Add the data to the data points array
            $dataPoints[] = [
                'y' => (float) ($salesData ?? 0), // Convert to float and default to 0 if null
                'label' => $dayName
            ];
        }

        return $dataPoints;
    }

    public static function getWeeklyReport()
    {
        // Get the current date
        $currentDate = time();

        // Calculate the start of the week (Sunday)
        $startOfWeek = strtotime('last Sunday', $currentDate);

        // Calculate the end of the week (Saturday)
        $endOfWeek = strtotime('next Saturday', $startOfWeek);

        // Array to store the daily report
        $reportData = [];

        // Iterate over each day of the week, starting from Sunday
        for ($day = 0; $day < 7; $day++) {
            $currentDay = strtotime("+$day day", $startOfWeek);

            // Start and end of the current day in UNIX timestamp
            $dayStart = strtotime(date('Y-m-d 00:00:00', $currentDay));
            $dayEnd = strtotime(date('Y-m-d 23:59:59', $currentDay));

            // Calculate sales for the day
            $salesData = Sales::find()
                ->where(['between', 'sale_date', $dayStart, $dayEnd])
                ->all();

            $salesTotal = Sales::find()
                ->where(['between', 'sale_date', $dayStart, $dayEnd])
                ->sum('total_amount') ?? 0;

            $productsSold = Sales::find()
                ->where(['between', 'sale_date', $dayStart, $dayEnd])
                ->sum('quantity') ?? 0;

            // Calculate expenses for the day
            $expenses = Expenses::find()
                ->where(['between', 'updated_at', $dayStart, $dayEnd])
                ->sum('amount') ?? 0;

            // Calculate profit for each sale
            $dailyProfit = 0;
            foreach ($salesData as $sale) {
                $dailyProfit += $sale->calculateProfit();
            }

            // Calculate net profit
            $netProfit = $dailyProfit - $expenses;

            // Add daily data to the report
            $reportData[] = [
                'day' => date('l', $currentDay),
                'date' => date('Y-m-d', $currentDay),
                'products_sold' => $productsSold,
                'sales' => $salesTotal,
                'expenses' => $expenses,
                'profit' => $dailyProfit,
                'net_profit' => $netProfit,
            ];
        }

        return $reportData;
    }

}
