<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Expenses;
use app\models\Products;
use app\models\Purchases;
use app\models\Sales;
use app\models\SignupForm;
use app\models\User;
use Exception;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
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
                'class' => VerbFilter::class,
                'actions' => [
                    // 'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
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

    // public function actionIndex($week = null)
    // {
    //     $query = Products::find()->where(['<', 'quantity', 3]); // Adjust the query as needed

    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //         'pagination' => [
    //             'pageSize' => 10, // Adjust as needed
    //         ],
    //     ]);

    //     $lowStockProducts = $this->getLowStockProducts();

    //     $year = date('Y');
    //     $currentWeek = $week ? (int)$week : (int)date('W');

    //     // Calculate previous and next weeks
    //     $prevWeek = $currentWeek > 1 ? $currentWeek - 1 : null;
    //     $nextWeek = $currentWeek < 52 ? $currentWeek + 1 : null;

    //     // Calculate the start and end of the week
    //     $startOfWeek = strtotime("{$year}-W{$currentWeek}-1");
    //     $endOfWeek = strtotime("{$year}-W{$currentWeek}-7 23:59:59");

    //     // Fetch sales data for the specified week
    //     $sales = Sales::find()
    //         ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
    //         ->all();

    //     // Calculate total sales quantity for the week
    //     $totalSalesQuantity = Sales::find()
    //         ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
    //         ->sum('quantity');

    //     // Calculate total expenditure for the week
    //     $totalExpenditure = Expenses::find()
    //         ->where(['between', 'updated_at', $startOfWeek, $endOfWeek])
    //         ->sum('amount');

    //     // Calculate total income for the week
    //     $totalIncome = Sales::find()
    //         ->where(['between', 'sale_date', $startOfWeek, $endOfWeek])
    //         ->sum('total_amount');

    //     // Calculate total profit for the week
    //     $totalProfit = 0;
    //     foreach ($sales as $sale) {
    //         $sale->calculatedProfit = $sale->calculateProfit();
    //         $totalProfit += $sale->calculatedProfit;
    //     }

    //     $netProfit = $totalProfit - $totalExpenditure;

    //     // Fetch the weekly report data using the unified function
    //     $reportData = Sales::getWeeklyReport($startOfWeek, $endOfWeek);

    //     // Pass data to the view
    //     return $this->render('index', [
    //         'sales' => $sales,
    //         'totalSalesQuantity' => $totalSalesQuantity,
    //         'totalExpenditure' => $totalExpenditure,
    //         'totalIncome' => $totalIncome,
    //         'netProfit' => $netProfit,
    //         'reportData' => $reportData,
    //         'prevWeek' => $prevWeek,
    //         'nextWeek' => $nextWeek,
    //         'currentWeek' => $currentWeek,
    //         'lowStockProducts' => $lowStockProducts,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }


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
        // Ensure that the user is authenticated
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']); // redirect to login page if not logged in
        }

        // Load the logged-in user's model
        $model = Yii::$app->user->identity;

        // Check if the form is submitted
        if ($this->request->isPost && $model->load($this->request->post())) {
            // Validate the model
            if ($model->validate()) {
                // Save the model without validation
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'User information saved successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save user information.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'All the fields are required');
            }
            // Redirect to the user profile page
            return $this->redirect(['user-profile']);
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
            return $this->redirect(['site/user-profile']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'LoginLayout';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    // In your Controller (e.g., SiteController.php)
    public function actionExport()
    {
        try {
            $db = Yii::$app->db;
            $tables = $db->schema->getTableNames();
            $output = '';

            // Temporarily disable foreign key checks
            $db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

            foreach ($tables as $table) {
                // Drop existing table if it exists
                $output .= "DROP TABLE IF EXISTS `$table`;\n";

                // Get the CREATE TABLE statement
                $createTable = $db->createCommand("SHOW CREATE TABLE `$table`")->queryOne();
                if ($createTable) {
                    $output .= $createTable['Create Table'] . ";\n\n"; // Get the CREATE TABLE statement
                } else {
                    throw new Exception("Failed to get create table statement for `$table`.");
                }

                // Get all rows from the table
                $rows = $db->createCommand("SELECT * FROM `$table`")->queryAll();
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            $values[] = $db->quoteValue($value); // Quote each value to prevent SQL injection
                        }
                        // Add an INSERT statement for each row
                        $output .= "INSERT INTO `$table` VALUES (" . implode(',', $values) . ");\n";
                    }
                    $output .= "\n"; // Separate each table's insert statements
                } else {
                    // If the table is empty, add a comment
                    $output .= "-- No data to insert for table `$table`\n\n";
                }
            }

            // Re-enable foreign key checks
            $db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

            // Set headers to download the file
            Yii::$app->response->headers->set('Content-Type', 'application/sql');
            Yii::$app->response->headers->set('Content-Disposition', 'attachment; filename="export.sql"');
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

            return $output; // Return the SQL content
        } catch (Exception $e) {
            // Handle the error
            Yii::$app->session->setFlash('error', 'Database export failed: ' . $e->getMessage());
            return $this->redirect(['index']); // Redirect back to the index or any page
        }
    }
}
