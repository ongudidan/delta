<?php

namespace app\controllers;

use app\models\Purchases;
use app\models\PurchasesSearch;
use app\models\Inventory;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class PurchasesController extends Controller
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
                            'actions' => ['logout', 'updaate', 'delete', 'create', 'view', 'index'],
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
     * Lists all Purchases models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('purchase-view')) {

            $model = new Purchases();

            if ($this->request->isPost) {
                $model->createPurchase($this->request);
            } else {
                $model->loadDefaultValues();
            }

            $searchModel = new PurchasesSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Displays a single Purchases model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->can('purchase-view')) {

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Creates a new Purchases model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('purchase-create')) {
    
            $model = new Purchases();
    
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    // Calculate and set total_amount
                    $model->total_cost = $model->getTotalAmount();
    
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Stock added successfully');
    
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            } else {
                $model->loadDefaultValues();
            }
    
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }
    

    /**
     * Updates an existing Purchases model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('purchase-update')) {
            $model = $this->findModel($id);
    
            if ($this->request->isPost && $model->load($this->request->post())) {
                // Calculate and set total_amount
                $model->total_cost = $model->getTotalAmount();
    
                if ($model->save()) {
                    return $this->redirect(['index', 'id' => $model->id]);
                }
            }
    
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }
    

    /**
     * Deletes an existing Purchases model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('purchase-delete')) {
    
            $model = $this->findModel($id);
    
            $model->delete();
    
            Yii::$app->session->setFlash('success', 'Deleted successfully');
    
            return $this->redirect(['index']);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }
    

    /**
     * Finds the Purchases model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Purchases the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchases::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
