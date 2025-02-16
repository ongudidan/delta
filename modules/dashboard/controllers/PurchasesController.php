<?php

namespace app\modules\dashboard\controllers;

use app\models\Purchases;
use app\modules\dashboard\models\PurchasesSearch;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class PurchasesController extends Controller
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
     * Lists all Purchases models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchasesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchases model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Purchases model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Purchases();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // If purchase_date is in dd/mm/yyyy format, convert it to Unix timestamp
                if ($model->purchase_date) {
                    // Convert the purchase_date from dd/mm/yyyy to Unix timestamp
                    $date = DateTime::createFromFormat('d/m/Y', $model->purchase_date);
                    if ($date) {
                        $model->purchase_date = $date->getTimestamp(); // Convert to Unix timestamp
                    } else {
                        // Handle error in case date format is invalid
                        Yii::$app->session->setFlash('error', 'Invalid date format.');
                        return $this->render('index', [
                            'model' => $model,
                        ]);
                    }
                }

                // Save the model
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'purchase created successfully!');

                    return $this->redirect(['index', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
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
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // If purchase_date is in dd/mm/yyyy format, convert it to yyyy-mm-dd
            if ($model->purchase_date) {
                // Convert the purchase_date to Unix timestamp if it's in dd/mm/yyyy format
                $date = DateTime::createFromFormat('d/m/Y', $model->purchase_date);
                if ($date) {
                    $model->purchase_date = $date->getTimestamp(); // Convert to Unix timestamp
                } else {
                    // Handle error in case date format is invalid
                    Yii::$app->session->setFlash('error', 'Invalid date format.');
                    return $this->render('index', [
                        'model' => $model,
                    ]);
                }
            }

            // Save the updated model
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Purchase updated successfully!');

                return $this->redirect(['index', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
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
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Purchase deleted successfully!');

        return $this->redirect(['index']);
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
