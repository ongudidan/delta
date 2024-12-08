<?php

namespace app\modules\dashboard\controllers;

use app\models\Products;
use app\models\Purchases;
use app\models\Sales;
use app\modules\dashboard\models\SalesSearch;
use DateTime;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller
{
    public $layout = 'DashboardLayout';

    /**
     * @inheritDoc
     */
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
     * Lists all Sales models.
     *
     * @return string
     */
    // public function actionIndex()
    // {
    //     $searchModel = new SalesSearch();
    //     $dataProvider = $searchModel->search($this->request->queryParams);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    public function actionIndex()
    {
        $searchModel = new SalesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Calculate profit and buying price for each sale
        $models = $dataProvider->getModels();
        foreach ($models as $sale) {
            $sale->calculatedProfit = $sale->calculateProfit();
            $sale->calculatedBuyingPrice = $sale->calculateBuyingPrice();
        }

        // Set models back to dataProvider
        $dataProvider->setModels($models);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sales model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new Sales();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
    {
        $model = new Sales();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Convert sale_date from dd/mm/yyyy to a Unix timestamp
                if (!empty($model->sale_date)) {
                    $date = DateTime::createFromFormat('d/m/Y', $model->sale_date);

                    // Check if the date conversion was successful
                    if ($date !== false) {
                        $providedDate = $date->getTimestamp();

                        // Check if the provided date is today
                        if (date('Y-m-d', $providedDate) === date('Y-m-d')) {
                            // If it's today, use the current time
                            $model->sale_date = time();
                        } else {
                            // Otherwise, set it to the start of that day
                            $model->sale_date = strtotime(date('Y-m-d 00:00:00', $providedDate));
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Invalid date format. Please use dd/mm/yyyy.');
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Sale date is required.');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                // Attempt to save the model
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Sale saved successfully!');
                    return $this->redirect(['create', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error saving the sale.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    public function actionGetProductDetails($id)
    {
        $product = Products::findOne($id);
        if ($product) {
            return json_encode(['price' => $product->selling_price]);
        }
        return json_encode(null);
    }

    public function actionCheckStock($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Find the product by ID
        $product = Products::findOne($id);
        if (!$product) {
            return ['available_stock' => 0];
        }

        // Calculate total purchased quantity for the product
        $totalPurchased = Purchases::find()
            ->where(['product_id' => $id])
            ->sum('quantity') ?? 0;

        // Calculate total sold quantity for the product
        $totalSold = Sales::find()
            ->where(['product_id' => $id])
            ->sum('quantity') ?? 0;

        // Calculate the available stock
        $availableStock = $totalPurchased - $totalSold;

        return ['available_stock' => max($availableStock, 0)];
    }


    /**
     * Updates an existing Sales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // If sale_date is provided and needs to be converted to Unix timestamp
            if ($model->sale_date) {
                // Convert the sale_date from dd/mm/yyyy to Unix timestamp
                $date = DateTime::createFromFormat('d/m/Y', $model->sale_date);
                if ($date) {
                    $model->sale_date = $date->getTimestamp(); // Convert to Unix timestamp
                } else {
                    // Handle error in case date format is invalid
                    Yii::$app->session->setFlash('error', 'Invalid date format.');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

            // Attempt to save the model
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Sale updated successfully!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // If save fails, set error flash message
                Yii::$app->session->setFlash('error', 'There was an error updating the sale.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    /**
     * Deletes an existing Sales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Sale deleted successfully!');


        return $this->redirect(['index']);
    }

    /**
     * Finds the Sales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Sales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sales::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeleteRecord()
    {
        // Unix timestamp for '01/01/1970'
        $targetTimestamp = 0;

        // Delete all records where sale_date matches the target timestamp
        $deletedCount = Sales::deleteAll(['sale_date' => $targetTimestamp]);

        if ($deletedCount > 0) {
            Yii::$app->session->setFlash('success', "$deletedCount records deleted successfully.");
        } else {
            Yii::$app->session->setFlash('info', "No records found for the specified date.");
        }

        return $this->redirect(['index']); // Adjust redirection as needed
    }
}
