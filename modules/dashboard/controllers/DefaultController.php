<?php

namespace app\modules\dashboard\controllers;

use app\models\Expenses;
use app\models\Products;
use app\models\Purchases;
use app\models\Sales;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `dashboard` module
 */
class DefaultController extends Controller
{
    public $layout = 'DashboardLayout';

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        // 'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }

    public function actionIndex($week = null)
    {
        $query = Products::find()->where(['<', 'quantity', 3]); // Adjust the query as needed

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10, // Adjust as needed
            ],
        ]);

        $lowStockProducts = $this->getLowStockProducts();

        $year = date('Y');
        $currentWeek = $week ? (int)$week : (int)date('W');

        // Calculate previous and next weeks
        $prevWeek = $currentWeek > 1 ? $currentWeek - 1 : null;
        $nextWeek = $currentWeek < 52 ? $currentWeek + 1 : null;

        // Fetch all sales records for the specific week
        $startOfWeek = strtotime("{$year}-W{$currentWeek}-1");
        $endOfWeek = strtotime("{$year}-W{$currentWeek}-7 23:59:59");

        // Fetch sales data for the specified week
        $sales = Sales::find()
            ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
            ->all();

        // Calculate total sales quantity for the week
        $totalSalesQuantity = Sales::find()
            ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
            ->sum('quantity');

        // Calculate total expenditure for the week
        $totalExpenditure = Expenses::find()
            ->where(['between', 'updated_at', $startOfWeek, $endOfWeek])
            ->sum('amount');

        // Calculate total income for the week
        $totalIncome = Sales::find()
            ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
            ->sum('total_amount');

        // Calculate total profit for the week
        $totalProfit = 0;
        foreach ($sales as $sale) {
            $sale->calculatedProfit = $sale->calculateProfit();
            $totalProfit += $sale->calculatedProfit;
        }

        $netProfit = $totalProfit - $totalExpenditure;

        // Fetch the weekly sales data
        $dataPoints = Sales::getWeeklySales();

        // Fetch the weekly report data
        $reportData = Sales::getWeeklyReport($startOfWeek, $endOfWeek);

        // Pass data to the view
        return $this->render('index', [
            'sales' => $sales,
            'totalSalesQuantity' => $totalSalesQuantity,
            'totalExpenditure' => $totalExpenditure,
            'totalIncome' => $totalIncome,
            'netProfit' => $netProfit,
            'dataPoints' => $dataPoints,
            'reportData' => $reportData,
            'prevWeek' => $prevWeek,
            'nextWeek' => $nextWeek,
            'currentWeek' => $currentWeek,
            'lowStockProducts' => $lowStockProducts,
            'dataProvider' => $dataProvider,

        ]);
    }

    // Separate function to get low-stock products
    protected function getLowStockProducts()
    {
        $products = Products::find()->all();
        $lowStockProducts = []; // Array to hold products with low stock

        foreach ($products as $product) {
            // Calculate total sales and purchases quantity
            $totalSalesQuantity = Sales::find()->where(['product_id' => $product->product_id])->sum('quantity');
            $totalPurchasesQuantity = Purchases::find()->where(['product_id' => $product->product_id])->sum('quantity');
            $totalQuantity = $totalPurchasesQuantity - $totalSalesQuantity;

            // If the total quantity is 5 or below, add it to the result array
            if ($totalQuantity <= 3) {
                $lowStockProducts[] = [
                    'product' => $product,
                    'totalSalesQuantity' => $totalSalesQuantity,
                    'totalPurchasesQuantity' => $totalPurchasesQuantity,
                    'totalQuantity' => $totalQuantity
                ];
            }
        }

        return $lowStockProducts; // Return the array of low stock products
    }


    public function actionUserProfile()
    {
        // Ensure the user is authenticated
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']); // Redirect to login page if not logged in
        }

        // Load the logged-in user's model
        $model = Yii::$app->user->identity;

        // Check if the form is submitted
        if ($this->request->isPost && $model->load($this->request->post())) {
            // Validate and save
            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'User information saved successfully.');
                return $this->redirect(['user-profile']);
            }
            // If validation fails, errors will display in the form fields automatically
        }

        // Render the form with the loaded model
        return $this->render('user-profile', [
            'model' => $model,
        ]);
    }


    public function actionChangePassword()
    {
        $model = Yii::$app->user->identity; // Get the currently logged-in user
        $model->scenario = 'changePassword'; // Set scenario for validation rules

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Password changed successfully.');
            return $this->redirect(['default/user-profile']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }



    ///////////////////////////////////////////
    public function actionGetSalesAndPurchasesByMonth()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Your existing code
            // Get current year
            $currentYear = date('Y');

            // Query the sale_product table to get the total sales per month for the current year
            $salesData = (new Query())
            ->select([
                'MONTH(FROM_UNIXTIME(sale_date)) AS month', // Use sale_date instead of created_at
                'SUM(total_amount) AS total_sales',
            ])
            ->from('{{%sales}}')
                ->where([
                    'YEAR(FROM_UNIXTIME(sale_date))' => $currentYear, // Filter by sale_date
                ])
                ->groupBy(['month'])
                ->orderBy(['month' => SORT_ASC])
                ->all();


            // Query the purchase_product table to get the total purchases per month for the current year
            $purchasesData = (new Query())
                ->select([
                    'MONTH(FROM_UNIXTIME(purchase_date)) AS month',
                    'SUM(total_cost) AS total_purchases',
                ])
                ->from('{{%purchases}}')
                ->where([
                    'YEAR(FROM_UNIXTIME(purchase_date))' => $currentYear,
                ])
                ->groupBy(['month'])
                ->orderBy(['month' => SORT_ASC])
                ->all();

            // Initialize arrays to hold sales and purchases data
            $monthlyData = [];
            $overallTotalSales = 0;
            $overallTotalPurchases = 0;

            // Initialize array for the months
            $months = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Aug',
                9 => 'Sep',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dec'
            ];

            // Prepare the chart data
            foreach (range(1, 12) as $month) {
                $monthlyData[] = [
                    'month' => $months[$month],
                    'sales' => 0,
                    'purchases' => 0,
                ];
            }

            // Populate the actual sales data and purchases data
            foreach ($salesData as $data) {
                $sales = (float) $data['total_sales'];
                $monthlyData[$data['month'] - 1]['sales'] = $sales;
                $overallTotalSales += $sales;
            }

            foreach ($purchasesData as $data) {
                $purchases = (float) $data['total_purchases'];
                $monthlyData[$data['month'] - 1]['purchases'] = $purchases;
                $overallTotalPurchases += $purchases;
            }

            // Return the sales and purchases data as JSON
            return [
                'status' => 'success',
                'data' => $monthlyData,
                'overallTotalSales' => $overallTotalSales,
                'overallTotalPurchases' => $overallTotalPurchases,
            ];
        } catch (\Exception $e) {
            // Log the exception message
            Yii::error('Error in actionGetSalesAndPurchasesByMonth: ' . $e->getMessage(), __METHOD__);

            // Return the error message in JSON format
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }


    public function actionGetMonthlySummary()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Get the first and last day of the current month
            $startOfMonth = strtotime(date('Y-m-01 00:00:00'));
            $endOfMonth = strtotime(date('Y-m-t 23:59:59'));

            // Query to calculate total sales for the current month
            $salesData = (new Query())
                ->select(['SUM(total_amount) AS total_sales'])
                ->from('{{%sales}}')
                ->where(['between', 'sale_date', $startOfMonth, $endOfMonth])
                ->scalar();

            // Query to calculate total purchases for the current month
            $purchasesData = (new Query())
                ->select(['SUM(total_cost) AS total_purchases'])
                ->from('{{%purchases}}')
                ->where(['between', 'purchase_date', $startOfMonth, $endOfMonth])
                ->scalar();

            // Query to calculate total expenses for the current month
            $expensesData = (new Query())
                ->select(['SUM(amount) AS total_expenses'])
                ->from('{{%expenses}}')
                ->where(['between', 'created_at', $startOfMonth, $endOfMonth])
                ->scalar();

            // Prepare the response
            return [
                'status' => 'success',
                'data' => [
                    'sales' => (float) $salesData ?: 0,
                    'purchases' => (float) $purchasesData ?: 0,
                    'expenses' => (float) $expensesData ?: 0,
                ],
            ];
        } catch (\Exception $e) {
            // Log the error
            Yii::error("Error fetching monthly summary: " . $e->getMessage(), __METHOD__);
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
