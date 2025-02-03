<?php

namespace app\modules\dashboard\controllers;

use app\models\BulkSale;
use app\models\Model;
use app\models\PaymentMethods;
use app\models\Products;
use app\models\Purchases;
use app\models\Sales;
use app\modules\dashboard\models\BulkSaleSearch;
use Exception;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * BulkSaleController implements the CRUD actions for BulkSale model.
 */
class BulkSaleController extends Controller
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
     * Lists all BulkSale models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BulkSaleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BulkSale model.
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
     * Creates a new BulkSale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new BulkSale();

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
        $model = new BulkSale();

        // In your controller action
        $paymentMethods = PaymentMethods::find()->all();
        $paymentMethodList = ArrayHelper::map($paymentMethods, 'id', 'name');


        $modelsSales = [new Sales()];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // $model->reference_no = $model->generateReferenceNo();

                $saleDate = strtotime($model->date);

                $model->sale_date = $saleDate;
                // print_r($saleDate);
                // exit;


                $modelsSales = Model::createMultiple(Sales::classname());
                Model::loadMultiple($modelsSales, Yii::$app->request->post());

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsSales) && $valid;

                // print_r($model);

                if (!$valid) {
                    $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                    Yii::$app->session->setFlash('error', 'Validation failed for the sale. Errors:<br>' . $errors);
                }

                // print_r($valid);
                // exit();

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsSales as $modelSales) {
                                $modelSales->bulk_sale_id = $model->id;
                                $modelSales->sale_date = $saleDate;

                                if (! ($flag = $modelSales->save(false))) {
                                    // Capture save errors for individual product purchase
                                    $errors = implode('<br>', ArrayHelper::getColumn($modelSales->getErrors(), 0));
                                    Yii::$app->session->setFlash('error', 'Failed to save a product purchase. Errors:<br>' . $errors);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'sale created successfully.');

                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('error', 'Transaction failed: ' . $e->getMessage());

                        $transaction->rollBack();
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->render('create', [
            'model' => $model,

            'modelsSales' => (empty($modelsSales)) ? [new Sales] : $modelsSales,
            'paymentMethodList' => $paymentMethodList,

        ]);
    }

    /**
     * Updates an existing BulkSale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $modelsSales = $model->sales;

        // In your controller action
        $paymentMethods = PaymentMethods::find()->all();
        $paymentMethodList = ArrayHelper::map($paymentMethods, 'id', 'name');


        if ($this->request->isPost && $model->load($this->request->post())) {



            $saleDate = strtotime($model->date);

            $model->sale_date = $saleDate;

            // print_r($saleDate);
            // exit;


            $oldIDs = ArrayHelper::map($modelsSales, 'id', 'id');
            $modelsSales = Model::createMultiple(Sales::classname(), $modelsSales);
            Model::loadMultiple($modelsSales, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSales, 'id', 'id')));

            // Validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsSales) && $valid;

            // Capture validation errors for main model
            if (!$valid) {
                $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                Yii::$app->session->setFlash('error', 'Validation failed for the purchase. Errors:<br>' . $errors);
            }

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            Sales::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsSales as $modelSales) {
                            $modelSales->bulk_sale_id = $model->id;
                            $modelSales->sale_date = $saleDate;


                            if (!($flag = $modelSales->save(false))) {
                                // Capture save errors for individual defects
                                $errors = implode('<br>', ArrayHelper::getColumn($modelSales->getErrors(), 0));
                                Yii::$app->session->setFlash('error', 'Failed to save a purchased product. Errors:<br>' . $errors);
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'sale updated successfully.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('error', 'Transaction failed: ' . $e->getMessage());
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,

            'modelsSales' => (empty($modelsSales)) ? [new Sales] : $modelsSales,
            'paymentMethodList' => $paymentMethodList,

        ]);
    }
    /**
     * Deletes an existing BulkSale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'sale deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the BulkSale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return BulkSale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BulkSale::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetProductDetails($id)
    {
        $product = Products::findOne($id);
        if ($product) {
            return json_encode(['price' => $product->selling_price]);
        }
        return json_encode(null);
    }

    public function actionCheckStock($id, $bulkSaleId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Find the product by ID
        $product = Products::findOne($id);
        if (!$product) {
            return ['available_stock' => 0]; // Return 0 if the product does not exist
        }

        // Calculate total purchased quantity for the product
        $totalPurchased = Purchases::find()
            ->where(['product_id' => $id])
            ->sum('quantity') ?? 0;

        // Calculate total sold quantity for the product
        $totalSold = Sales::find()
            ->where(['product_id' => $id])
            ->sum('quantity') ?? 0;

        // If a bulk sale ID is provided, consider the bulk sale stock as well
        if ($bulkSaleId !== null) {
            $totalBulkSold = Sales::find()
                ->where(['bulk_sale_id' => $bulkSaleId])
                ->sum('quantity') ?? 0;
        }


        // Calculate the available stock
        $availableStock = max(($totalPurchased - $totalSold) + $totalBulkSold, 0);

        // Return the available stock in the response
        return ['available_stock' => max($availableStock, 0)];
    }


    public function actionSearch($q)
    {
        $products = Products::find()
            ->select(['product_id as id', 'product_name as text']) // Format results for Select2
            ->where(['like', 'product_name', $q])
            ->limit(20) // Limit results for performance
            ->asArray()
            ->all();

        return $this->asJson($products);
    }

    public function actionSearchPaymentMethods($q)
    {
        $paymentMethods = PaymentMethods::find()
            ->select(['id', 'name as text']) // Format results for Select2
            ->where(['like', 'name', $q])
            ->limit(20)
            ->asArray()
            ->all();

        return $this->asJson($paymentMethods);
    }

    // public function actionValidate()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;
    //     $model = new Sales();
    //     // Perform validation based on submitted data
    //     if ($model->load(Yii::$app->request->post())) {
    //         return ActiveForm::validate($model);
    //     }
    //     return [];
    // }
    public function actionValidate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $models = []; // Store multiple sales models
        $postData = Yii::$app->request->post('Sales', []);

        foreach ($postData as $index => $data) {
            $models[$index] = new Sales();
            $models[$index]->load($data, '');
        }

        return ActiveForm::validateMultiple($models);
    }


}
