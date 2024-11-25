<?php

namespace app\controllers;

use app\models\Sales;
use app\models\SalesSearch;
use app\models\Inventory;
use app\models\Products;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller
{
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



    public function actionGetSellingPrice($productId)
    {
        // Set the response format to JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Find the product by productId
        $product = Products::findOne($productId);

        // If the product exists, return the selling_price; otherwise, return null or an error message
        if ($product) {
            return ['selling_price' => $product->selling_price];
        }

        return ['selling_price' => null]; // Or handle the error as needed
    }



    /**
     * Lists all Sales models.
     *
     * @return string
     */
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
    public function actionCreate()
    {
        $model = new Sales();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Calculate and set total_amount
                $model->total_amount = $model->getTotalAmount();

                if (date('Y-m-d', strtotime($model->sale_date)) === date('Y-m-d')) {
                    $model->sale_date = time();
                } else {
                    $model->sale_date = strtotime($model->sale_date);
                }

                // Remove inventory check and update
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Saved successfully');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
            // Calculate and set total_amount
            $model->total_amount = $model->getTotalAmount();

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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

        $model = $this->findModel($id);

        $model->delete();

        Yii::$app->session->setFlash('success', 'Deleted successfully');

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
}
