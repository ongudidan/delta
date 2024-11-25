<?php

namespace app\modules\dashboard\controllers;

use app\models\BulkPurchase;
use app\models\Model;
use app\models\Purchases;
use app\modules\dashboard\models\BulkPurchaseSearch;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BulkPurchaseController implements the CRUD actions for BulkPurchase model.
 */
class BulkPurchaseController extends Controller
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
     * Lists all BulkPurchase models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BulkPurchaseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BulkPurchase model.
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
     * Creates a new BulkPurchase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new BulkPurchase();

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
        $model = new BulkPurchase();


        $modelsPurchases = [new Purchases()];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // $model->reference_no = $model->generateReferenceNo();

                $purchaseDate = strtotime($model->date);

                $model->purchase_date = $purchaseDate;
                // print_r($purchaseDate);
                // exit;


                $modelsPurchases = Model::createMultiple(Purchases::classname());
                Model::loadMultiple($modelsPurchases, Yii::$app->request->post());

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsPurchases) && $valid;

                // print_r($model);

                if (!$valid) {
                    $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                    Yii::$app->session->setFlash('error', 'Validation failed for the purchase. Errors:<br>' . $errors);
                }

                // print_r($valid);
                // exit();

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsPurchases as $modelPurchases) {
                                $modelPurchases->bulk_purchase_id = $model->id;
                                $modelPurchases->purchase_date = $purchaseDate;

                                if (! ($flag = $modelPurchases->save(false))) {
                                    // Capture save errors for individual product purchase
                                    $errors = implode('<br>', ArrayHelper::getColumn($modelPurchases->getErrors(), 0));
                                    Yii::$app->session->setFlash('error', 'Failed to save a product purchase. Errors:<br>' . $errors);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'purchase created successfully.');

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

            'modelsPurchases' => (empty($modelsPurchases)) ? [new Purchases] : $modelsPurchases
        ]);
    }

    /**
     * Updates an existing BulkPurchase model.
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
        $modelsPurchases = $model->purchases;

        if ($this->request->isPost && $model->load($this->request->post())) {



            $purchaseDate = strtotime($model->date);

            $model->purchase_date = $purchaseDate;

            // print_r($purchaseDate);
            // exit;


            $oldIDs = ArrayHelper::map($modelsPurchases, 'id', 'id');
            $modelsPurchases = Model::createMultiple(Purchases::classname(), $modelsPurchases);
            Model::loadMultiple($modelsPurchases, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPurchases, 'id', 'id')));

            // Validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsPurchases) && $valid;

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
                            Purchases::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsPurchases as $modelPurchases) {
                            $modelPurchases->bulk_purchase_id = $model->id;
                            $modelPurchases->purchase_date = $purchaseDate;


                            if (!($flag = $modelPurchases->save(false))) {
                                // Capture save errors for individual defects
                                $errors = implode('<br>', ArrayHelper::getColumn($modelPurchases->getErrors(), 0));
                                Yii::$app->session->setFlash('error', 'Failed to save a purchased product. Errors:<br>' . $errors);
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'purchase updated successfully.');
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

            'modelsPurchases' => (empty($modelsPurchases)) ? [new Purchases] : $modelsPurchases
        ]);
    }

    /**
     * Deletes an existing BulkPurchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BulkPurchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return BulkPurchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BulkPurchase::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
