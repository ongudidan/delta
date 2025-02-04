<?php

namespace app\modules\dashboard\controllers;

use app\models\BulkExpense;
use app\models\Expenses;
use app\models\Model;
use app\models\PaymentMethods;
use app\modules\dashboard\models\BulkExpenseSearch;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BulkExpenseController implements the CRUD actions for BulkExpense model.
 */
class BulkExpenseController extends Controller
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
     * Lists all BulkExpense models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BulkExpenseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BulkExpense model.
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
     * Creates a new BulkExpense model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new BulkExpense();

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
        $model = new BulkExpense();

        // In your controller action
        $paymentMethods = PaymentMethods::find()->all();
        $paymentMethodList = ArrayHelper::map($paymentMethods, 'id', 'name');

        $modelsExpenses = [new Expenses()];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // $model->reference_no = $model->generateReferenceNo();

                $expenseDate = strtotime($model->date);

                $model->expense_date = $expenseDate;
                // print_r($expenseDate);
                // exit;


                $modelsExpenses = Model::createMultiple(Expenses::classname());
                Model::loadMultiple($modelsExpenses, Yii::$app->request->post());

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsExpenses) && $valid;

                // print_r($model);

                if (!$valid) {
                    $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                    Yii::$app->session->setFlash('error', 'Validation failed for the expense. Errors:<br>' . $errors);
                }

                // print_r($valid);
                // exit();

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsExpenses as $modelExpenses) {
                                $modelExpenses->bulk_expense_id = $model->id;
                                // $modelExpenses->expense_date = $expenseDate;

                                if (! ($flag = $modelExpenses->save(false))) {
                                    // Capture save errors for individual product expense
                                    $errors = implode('<br>', ArrayHelper::getColumn($modelExpenses->getErrors(), 0));
                                    Yii::$app->session->setFlash('error', 'Failed to save expense. Errors:<br>' . $errors);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Expense created successfully.');

                            return $this->redirect(['index', 'id' => $model->id]);
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


        return $this->renderAjax('create', [
            'model' => $model,

            'modelsExpenses' => (empty($modelsExpenses)) ? [new Expenses] : $modelsExpenses,
            'paymentMethodList' => $paymentMethodList,

        ]);
    }

    /**
     * Updates an existing BulkExpense model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $modelsExpenses = $model->expenses;

        // In your controller action
        $paymentMethods = PaymentMethods::find()->all();
        $paymentMethodList = ArrayHelper::map($paymentMethods, 'id', 'name');

        if ($this->request->isPost && $model->load($this->request->post())) {



            $purchaseDate = strtotime($model->date);

            // $model->purchase_date = $purchaseDate;

            // print_r($purchaseDate);
            // exit;


            $oldIDs = ArrayHelper::map($modelsExpenses, 'id', 'id');
            $modelsExpenses = Model::createMultiple(Expenses::classname(), $modelsExpenses);
            Model::loadMultiple($modelsExpenses, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsExpenses, 'id', 'id')));

            // Validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsExpenses) && $valid;

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
                            Expenses::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsExpenses as $modelExpenses) {
                            $modelExpenses->bulk_expense_id = $model->id;
                            // $modelExpenses->purchase_date = $purchaseDate;


                            if (!($flag = $modelExpenses->save(false))) {
                                // Capture save errors for individual defects
                                $errors = implode('<br>', ArrayHelper::getColumn($modelExpenses->getErrors(), 0));
                                Yii::$app->session->setFlash('error', 'Failed to save a expense. Errors:<br>' . $errors);
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Expense updated successfully.');
                        return $this->redirect(['index', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('error', 'Transaction failed: ' . $e->getMessage());
                    $transaction->rollBack();
                }
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,

            'modelsExpenses' => (empty($modelsExpenses)) ? [new Expenses] : $modelsExpenses,
            'paymentMethodList' => $paymentMethodList,

        ]);
    }

    /**
     * Deletes an existing BulkExpense model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Expense deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the BulkExpense model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return BulkExpense the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BulkExpense::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
