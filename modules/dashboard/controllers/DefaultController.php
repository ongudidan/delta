<?php

namespace app\modules\dashboard\controllers;

use app\models\Expenses;
use app\models\Products;
use app\models\Purchases;
use app\models\Sales;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
}
