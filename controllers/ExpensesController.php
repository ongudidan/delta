<?php

namespace app\controllers;

use app\models\Expenses;
use app\models\ExpensesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpensesController implements the CRUD actions for Expenses model.
 */
class ExpensesController extends Controller
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

    /**
     * Lists all Expenses models.
     *
     * @return string
     */
    public function actionIndex()
    {

            $model = new Expenses();

            if ($this->request->isPost) {
                $model->createExpense($this->request);
            } else {
                $model->loadDefaultValues();
            }

            $searchModel = new ExpensesSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
    }

    /**
     * Displays a single Expenses model.
     * @param int $expense_id Expense ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($expense_id)
    {

            return $this->render('view', [
                'model' => $this->findModel($expense_id),
            ]);

    }

    /**
     * Creates a new Expenses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {

            $model = new Expenses();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    Yii::$app->session->setFlash('success', 'Expense added successfully');
                    return $this->redirect(['view', 'expense_id' => $model->expense_id]);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);

    }

    /**
     * Updates an existing Expenses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $expense_id Expense ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($expense_id)
    {

            $model = $this->findModel($expense_id);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Expense updated successfully');
                return $this->redirect(['view', 'expense_id' => $model->expense_id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing Expenses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $expense_id Expense ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($expense_id)
    {

            $this->findModel($expense_id)->delete();
            Yii::$app->session->setFlash('success', 'Expense deleted successfully');

            return $this->redirect(['index']);

    }

    /**
     * Finds the Expenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $expense_id Expense ID
     * @return Expenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($expense_id)
    {
        if (($model = Expenses::findOne(['expense_id' => $expense_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
